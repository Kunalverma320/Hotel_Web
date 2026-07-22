<?php

namespace App\Services;

use App\Models\JournalEntry;
use App\Models\JournalEntryLine;
use App\Repositories\FinanceRepository;
use App\Traits\HasAuditLog;
use Illuminate\Support\Facades\DB;

class FinanceService
{
    use HasAuditLog;

    public function __construct(
        protected FinanceRepository $financeRepo,
    ) {}

    public function createJournalEntry(array $data): JournalEntry
    {
        return DB::transaction(function () use ($data) {
            $lines = $data['lines'];
            unset($data['lines']);

            $totalDebit = collect($lines)->sum('debit');
            $totalCredit = collect($lines)->sum('credit');

            if ($totalDebit !== $totalCredit) {
                throw new \InvalidArgumentException('Debit and credit totals must be equal.');
            }

            $data['voucher_number'] = $data['voucher_number'] ?? $this->generateVoucherNumber($data['type'] ?? 'general');
            $data['status'] = 'draft';

            $entry = JournalEntry::create($data);

            foreach ($lines as $line) {
                $entry->lines()->create($line);
            }

            $this->logActivity($entry, 'created', null, $entry->load('lines')->toArray());

            return $entry->fresh('lines');
        });
    }

    public function postJournalEntry(int|string $id): JournalEntry
    {
        return DB::transaction(function () use ($id) {
            $entry = JournalEntry::findOrFail($id);
            $oldData = $entry->toArray();

            $entry->update(['status' => 'posted', 'posted_at' => now()]);

            $this->logActivity($entry, 'posted', $oldData, $entry->fresh()->toArray());

            return $entry->fresh();
        });
    }

    public function reverseJournalEntry(int|string $id): JournalEntry
    {
        return DB::transaction(function () use ($id) {
            $original = JournalEntry::with('lines')->findOrFail($id);

            $reversalLines = $original->lines->map(function ($line) {
                return [
                    'account_id' => $line->account_id,
                    'debit' => $line->credit,
                    'credit' => $line->debit,
                    'description' => "Reversal: {$line->description}",
                ];
            })->toArray();

            $reversal = $this->createJournalEntry([
                'hotel_id' => $original->hotel_id,
                'type' => 'reversal',
                'reference_type' => JournalEntry::class,
                'reference_id' => $original->id,
                'description' => "Reversal of {$original->voucher_number}",
                'date' => now()->toDateString(),
                'lines' => $reversalLines,
            ]);

            $original->update(['status' => 'reversed', 'reversed_at' => now()]);

            $this->logActivity($original, 'reversed', $original->toArray(), $reversal->toArray());

            return $reversal;
        });
    }

    public function recordIncome(array $data): JournalEntry
    {
        return $this->createJournalEntry(array_merge($data, ['type' => 'income']));
    }

    public function recordExpense(array $data): JournalEntry
    {
        return $this->createJournalEntry(array_merge($data, ['type' => 'expense']));
    }

    public function getTrialBalance(int|string $hotelId, string $date): array
    {
        return $this->financeRepo->trialBalance($hotelId);
    }

    public function getProfitAndLoss(int|string $hotelId, string $from, string $to): array
    {
        return $this->financeRepo->profitAndLoss($hotelId, $from, $to);
    }

    public function getBalanceSheet(int|string $hotelId, string $date): array
    {
        return $this->financeRepo->balanceSheet($hotelId);
    }

    public function generateVoucherNumber(string $type): string
    {
        $prefix = match ($type) {
            'income' => 'RV',
            'expense' => 'PV',
            'journal' => 'JV',
            'reversal' => 'RV',
            default => 'JV',
        };

        $date = now()->format('Ymd');
        $lastEntry = JournalEntry::where('voucher_number', 'like', "{$prefix}{$date}%")
            ->orderByDesc('voucher_number')
            ->first();

        if ($lastEntry) {
            $sequence = (int) substr($lastEntry->voucher_number, -4) + 1;
        } else {
            $sequence = 1;
        }

        return sprintf('%s%s%04d', $prefix, $date, $sequence);
    }
}

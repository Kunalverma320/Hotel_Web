<?php

namespace App\Repositories;

use App\Models\FinanceAccount;
use Illuminate\Support\Facades\DB;

class FinanceRepository extends BaseRepository
{
    public function __construct(FinanceAccount $model)
    {
        parent::__construct($model);
    }

    public function byType(string $type): \Illuminate\Database\Eloquent\Collection
    {
        return $this->model->where('type', $type)->get();
    }

    public function trialBalance(int|string $hotelId): \Illuminate\Database\Eloquent\Collection
    {
        return $this->model->where('hotel_id', $hotelId)
            ->whereHas('journalEntries')
            ->withSum('journalEntries as total_debit', 'debit')
            ->withSum('journalEntries as total_credit', 'credit')
            ->get();
    }

    public function profitAndLoss(int|string $hotelId, string $from, string $to): array
    {
        $income = $this->model->where('hotel_id', $hotelId)
            ->where('type', 'income')
            ->whereHas('journalEntries', function ($query) use ($from, $to) {
                $query->whereBetween('date', [$from, $to]);
            })
            ->withSum('journalEntries as total', 'credit')
            ->get()
            ->sum('total');

        $expenses = $this->model->where('hotel_id', $hotelId)
            ->where('type', 'expense')
            ->whereHas('journalEntries', function ($query) use ($from, $to) {
                $query->whereBetween('date', [$from, $to]);
            })
            ->withSum('journalEntries as total', 'debit')
            ->get()
            ->sum('total');

        return [
            'income' => $income,
            'expenses' => $expenses,
            'net_profit' => $income - $expenses,
        ];
    }

    public function balanceSheet(int|string $hotelId): array
    {
        $assets = $this->model->where('hotel_id', $hotelId)
            ->where('type', 'asset')
            ->withSum('journalEntries as total', 'debit')
            ->get()
            ->sum('total');

        $liabilities = $this->model->where('hotel_id', $hotelId)
            ->where('type', 'liability')
            ->withSum('journalEntries as total', 'credit')
            ->get()
            ->sum('total');

        $equity = $this->model->where('hotel_id', $hotelId)
            ->where('type', 'equity')
            ->withSum('journalEntries as total', 'credit')
            ->get()
            ->sum('total');

        return [
            'assets' => $assets,
            'liabilities' => $liabilities,
            'equity' => $equity,
            'total_liabilities_and_equity' => $liabilities + $equity,
        ];
    }
}

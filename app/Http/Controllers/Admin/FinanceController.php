<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Income;
use App\Models\Expense;
use App\Models\JournalEntry;
use App\Models\JournalEntryLine;
use App\Models\ChartOfAccount;
use App\Models\GeneralLedger;
use Illuminate\Http\Request;

class FinanceController extends Controller
{
    public function index()
    {
        $totalIncome = Income::whereMonth('income_date', now()->month)->sum('amount');
        $totalExpense = Expense::whereMonth('expense_date', now()->month)->sum('amount');
        $pendingExpenses = Expense::where('status', 'pending')->sum('amount');
        $recentIncomes = Income::latest()->take(5)->get();
        $recentExpenses = Expense::latest()->take(5)->get();
        $accountBalances = ChartOfAccount::where('is_group', false)->withSum('ledgerEntries as balance', 'debit - credit')->get();

        return view('admin.finance.dashboard', compact(
            'totalIncome', 'totalExpense', 'pendingExpenses',
            'recentIncomes', 'recentExpenses', 'accountBalances'
        ));
    }

    public function income(Request $request)
    {
        $query = Income::query();

        if ($request->filled('date_from')) {
            $query->where('income_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('income_date', '<=', $request->date_to);
        }

        $incomes = $query->latest('income_date')->paginate(20);
        $totalAmount = (clone $query)->sum('amount');

        return view('admin.finance.income', compact('incomes', 'totalAmount'));
    }

    public function incomeCreate()
    {
        $accounts = ChartOfAccount::where('is_group', false)->orderBy('name')->get();
        return view('admin.finance.income-create', compact('accounts'));
    }

    public function incomeStore(Request $request)
    {
        $validated = $request->validate([
            'income_date'   => 'required|date',
            'category'      => 'required|string|max:255',
            'description'   => 'required|string|max:500',
            'amount'        => 'required|numeric|min:0.01',
            'payment_method' => 'required|in:cash,bank_transfer,cheque,online',
            'account_id'    => 'required|exists:chart_of_accounts,id',
            'reference'     => 'nullable|string|max:255',
            'notes'         => 'nullable|string',
        ]);

        Income::create($validated);

        GeneralLedger::create([
            'account_id' => $validated['account_id'],
            'date'       => $validated['income_date'],
            'type'       => 'income',
            'reference'  => $validated['reference'] ?? null,
            'description' => $validated['description'],
            'debit'      => $validated['amount'],
            'credit'     => 0,
        ]);

        return redirect()->route('admin.finance.income')->with('success', 'Income recorded successfully.');
    }

    public function expense(Request $request)
    {
        $query = Expense::query();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('date_from')) {
            $query->where('expense_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('expense_date', '<=', $request->date_to);
        }

        $expenses = $query->latest('expense_date')->paginate(20);
        $totalAmount = (clone $query)->sum('amount');

        return view('admin.finance.expense', compact('expenses', 'totalAmount'));
    }

    public function expenseCreate()
    {
        $accounts = ChartOfAccount::where('is_group', false)->orderBy('name')->get();
        return view('admin.finance.expense-create', compact('accounts'));
    }

    public function expenseStore(Request $request)
    {
        $validated = $request->validate([
            'expense_date'  => 'required|date',
            'category'      => 'required|string|max:255',
            'description'   => 'required|string|max:500',
            'amount'        => 'required|numeric|min:0.01',
            'payment_method' => 'required|in:cash,bank_transfer,cheque,online',
            'account_id'    => 'required|exists:chart_of_accounts,id',
            'reference'     => 'nullable|string|max:255',
            'notes'         => 'nullable|string',
        ]);

        $validated['status'] = 'pending';
        $validated['created_by'] = auth()->id();

        Expense::create($validated);

        return redirect()->route('admin.finance.expense')->with('success', 'Expense recorded successfully.');
    }

    public function expenseApprove($id)
    {
        $expense = Expense::findOrFail($id);
        $expense->update(['status' => 'approved', 'approved_by' => auth()->id()]);

        GeneralLedger::create([
            'account_id'  => $expense->account_id,
            'date'        => $expense->expense_date,
            'type'        => 'expense',
            'reference'   => $expense->reference ?? null,
            'description' => $expense->description,
            'debit'       => 0,
            'credit'      => $expense->amount,
        ]);

        return redirect()->route('admin.finance.expense')->with('success', 'Expense approved successfully.');
    }

    public function cashBook(Request $request)
    {
        $dateFrom = $request->get('date_from', now()->startOfMonth()->toDateString());
        $dateTo = $request->get('date_to', now()->toDateString());

        $cashAccounts = ChartOfAccount::where('name', 'like', '%Cash%')->pluck('id');
        $entries = GeneralLedger::whereIn('account_id', $cashAccounts)
            ->whereBetween('date', [$dateFrom, $dateTo])
            ->orderBy('date')
            ->orderBy('id')
            ->get();

        $openingBalance = GeneralLedger::whereIn('account_id', $cashAccounts)
            ->where('date', '<', $dateFrom)
            ->sum(\DB::raw('debit - credit'));

        return view('admin.finance.cashbook', compact('entries', 'openingBalance', 'dateFrom', 'dateTo'));
    }

    public function bankBook(Request $request)
    {
        $dateFrom = $request->get('date_from', now()->startOfMonth()->toDateString());
        $dateTo = $request->get('date_to', now()->toDateString());

        $bankAccounts = ChartOfAccount::where('name', 'like', '%Bank%')->pluck('id');
        $entries = GeneralLedger::whereIn('account_id', $bankAccounts)
            ->whereBetween('date', [$dateFrom, $dateTo])
            ->orderBy('date')
            ->orderBy('id')
            ->get();

        $openingBalance = GeneralLedger::whereIn('account_id', $bankAccounts)
            ->where('date', '<', $dateFrom)
            ->sum(\DB::raw('debit - credit'));

        return view('admin.finance.bankbook', compact('entries', 'openingBalance', 'dateFrom', 'dateTo'));
    }

    public function journal(Request $request)
    {
        $query = JournalEntry::with('lines.account');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $entries = $query->latest('entry_date')->paginate(20);

        return view('admin.finance.journal', compact('entries'));
    }

    public function journalCreate()
    {
        $accounts = ChartOfAccount::where('is_group', false)->orderBy('name')->get();
        $entryNumber = 'JE-' . date('Ymd') . '-' . str_pad(JournalEntry::whereDate('created_at', today())->count() + 1, 4, '0', STR_PAD_LEFT);

        return view('admin.finance.journal-create', compact('accounts', 'entryNumber'));
    }

    public function journalStore(Request $request)
    {
        $validated = $request->validate([
            'entry_date'       => 'required|date',
            'description'      => 'required|string|max:500',
            'reference'        => 'nullable|string|max:255',
            'lines'            => 'required|array|min:2',
            'lines.*.account_id' => 'required|exists:chart_of_accounts,id',
            'lines.*.debit'    => 'required|numeric|min:0',
            'lines.*.credit'   => 'required|numeric|min:0',
            'lines.*.line_description' => 'nullable|string|max:255',
        ]);

        $totalDebit = collect($validated['lines'])->sum('debit');
        $totalCredit = collect($validated['lines'])->sum('credit');

        if (round($totalDebit, 2) !== round($totalCredit, 2)) {
            return back()->withErrors(['lines' => 'Total debits must equal total credits.'])->withInput();
        }

        $entry = JournalEntry::create([
            'entry_number' => $request->entry_number ?? 'JE-' . date('Ymd') . '-' . str_pad(JournalEntry::whereDate('created_at', today())->count() + 1, 4, '0', STR_PAD_LEFT),
            'entry_date'   => $validated['entry_date'],
            'description'  => $validated['description'],
            'reference'    => $validated['reference'] ?? null,
            'total_debit'  => $totalDebit,
            'total_credit' => $totalCredit,
            'status'       => 'draft',
            'created_by'   => auth()->id(),
        ]);

        foreach ($validated['lines'] as $line) {
            JournalEntryLine::create([
                'journal_entry_id' => $entry->id,
                'account_id'       => $line['account_id'],
                'debit'            => $line['debit'],
                'credit'           => $line['credit'],
                'description'      => $line['line_description'] ?? null,
            ]);
        }

        return redirect()->route('admin.finance.journal.show', $entry->id)->with('success', 'Journal entry created successfully.');
    }

    public function journalShow($id)
    {
        $entry = JournalEntry::with('lines.account')->findOrFail($id);
        return view('admin.finance.journal-show', compact('entry'));
    }

    public function journalPost($id)
    {
        $entry = JournalEntry::with('lines')->findOrFail($id);

        if ($entry->status === 'posted') {
            return back()->with('error', 'Journal entry is already posted.');
        }

        foreach ($entry->lines as $line) {
            GeneralLedger::create([
                'account_id'   => $line->account_id,
                'date'         => $entry->entry_date,
                'type'         => 'journal',
                'reference'    => $entry->entry_number,
                'description'  => $line->description ?? $entry->description,
                'debit'        => $line->debit,
                'credit'       => $line->credit,
            ]);
        }

        $entry->update(['status' => 'posted']);

        return redirect()->route('admin.finance.journal.show', $id)->with('success', 'Journal entry posted successfully.');
    }

    public function chartOfAccounts()
    {
        $accounts = ChartOfAccount::with('children')->whereNull('parent_id')->orderBy('code')->get();
        return view('admin.finance.chart-of-accounts', compact('accounts'));
    }

    public function chartOfAccountsCreate()
    {
        $parentAccounts = ChartOfAccount::where('is_group', true)->orderBy('code')->get();
        return view('admin.finance.coa-form', ['account' => null, 'parentAccounts' => $parentAccounts]);
    }

    public function chartOfAccountsStore(Request $request)
    {
        $validated = $request->validate([
            'code'        => 'required|string|unique:chart_of_accounts,code',
            'name'        => 'required|string|max:255',
            'type'        => 'required|in:asset,liability,equity,income,expense',
            'parent_id'   => 'nullable|exists:chart_of_accounts,id',
            'is_group'    => 'required|boolean',
            'description' => 'nullable|string',
            'status'      => 'required|in:active,inactive',
        ]);

        ChartOfAccount::create($validated);

        return redirect()->route('admin.finance.coa')->with('success', 'Account created successfully.');
    }

    public function ledger($accountId)
    {
        $account = ChartOfAccount::findOrFail($accountId);
        $entries = GeneralLedger::where('account_id', $accountId)->orderBy('date')->orderBy('id')->paginate(30);
        $runningBalance = 0;

        return view('admin.finance.ledger', compact('account', 'entries', 'runningBalance'));
    }
}

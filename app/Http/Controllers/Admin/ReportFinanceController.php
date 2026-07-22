<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChartOfAccount;
use App\Models\GeneralLedger;
use App\Models\Income;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportFinanceController extends Controller
{
    public function trialBalance()
    {
        $accounts = ChartOfAccount::where('is_group', false)
            ->leftJoin('general_ledgers', 'chart_of_accounts.id', '=', 'general_ledgers.account_id')
            ->select(
                'chart_of_accounts.*',
                DB::raw('COALESCE(SUM(general_ledgers.debit), 0) as total_debit'),
                DB::raw('COALESCE(SUM(general_ledgers.credit), 0) as total_credit')
            )
            ->groupBy('chart_of_accounts.id', 'chart_of_accounts.code', 'chart_of_accounts.name',
                       'chart_of_accounts.type', 'chart_of_accounts.parent_id',
                       'chart_of_accounts.is_group', 'chart_of_accounts.description',
                       'chart_of_accounts.status', 'chart_of_accounts.created_at',
                       'chart_of_accounts.updated_at')
            ->havingRaw('COALESCE(SUM(general_ledgers.debit), 0) != 0 OR COALESCE(SUM(general_ledgers.credit), 0) != 0')
            ->orderBy('chart_of_accounts.code')
            ->get();

        $totalDebit = $accounts->sum('total_debit');
        $totalCredit = $accounts->sum('total_credit');

        return view('admin.finance.trial-balance', compact('accounts', 'totalDebit', 'totalCredit'));
    }

    public function profitAndLoss(Request $request)
    {
        $dateFrom = $request->get('date_from', now()->startOfYear()->toDateString());
        $dateTo = $request->get('date_to', now()->toDateString());

        $incomeAccounts = ChartOfAccount::where('type', 'income')->where('is_group', false)->pluck('id');
        $expenseAccounts = ChartOfAccount::where('type', 'expense')->where('is_group', false)->pluck('id');

        $income = GeneralLedger::whereIn('account_id', $incomeAccounts)
            ->whereBetween('date', [$dateFrom, $dateTo])
            ->selectRaw('account_id, SUM(credit) - SUM(debit) as amount')
            ->with('account')
            ->groupBy('account_id')
            ->get();

        $expenses = GeneralLedger::whereIn('account_id', $expenseAccounts)
            ->whereBetween('date', [$dateFrom, $dateTo])
            ->selectRaw('account_id, SUM(debit) - SUM(credit) as amount')
            ->with('account')
            ->groupBy('account_id')
            ->get();

        $totalIncome = $income->sum('amount');
        $totalExpenses = $expenses->sum('amount');
        $netProfit = $totalIncome - $totalExpenses;

        return view('admin.finance.profit-loss', compact(
            'income', 'expenses', 'totalIncome', 'totalExpenses', 'netProfit',
            'dateFrom', 'dateTo'
        ));
    }

    public function balanceSheet()
    {
        $assetAccounts = ChartOfAccount::where('type', 'asset')->where('is_group', false)
            ->leftJoin('general_ledgers', 'chart_of_accounts.id', '=', 'general_ledgers.account_id')
            ->select('chart_of_accounts.*', DB::raw('COALESCE(SUM(general_ledgers.debit - general_ledgers.credit), 0) as balance'))
            ->groupBy('chart_of_accounts.id', 'chart_of_accounts.code', 'chart_of_accounts.name',
                       'chart_of_accounts.type', 'chart_of_accounts.parent_id',
                       'chart_of_accounts.is_group', 'chart_of_accounts.description',
                       'chart_of_accounts.status', 'chart_of_accounts.created_at',
                       'chart_of_accounts.updated_at')
            ->get();

        $liabilityAccounts = ChartOfAccount::where('type', 'liability')->where('is_group', false)
            ->leftJoin('general_ledgers', 'chart_of_accounts.id', '=', 'general_ledgers.account_id')
            ->select('chart_of_accounts.*', DB::raw('COALESCE(SUM(general_ledgers.credit - general_ledgers.debit), 0) as balance'))
            ->groupBy('chart_of_accounts.id', 'chart_of_accounts.code', 'chart_of_accounts.name',
                       'chart_of_accounts.type', 'chart_of_accounts.parent_id',
                       'chart_of_accounts.is_group', 'chart_of_accounts.description',
                       'chart_of_accounts.status', 'chart_of_accounts.created_at',
                       'chart_of_accounts.updated_at')
            ->get();

        $equityAccounts = ChartOfAccount::where('type', 'equity')->where('is_group', false)
            ->leftJoin('general_ledgers', 'chart_of_accounts.id', '=', 'general_ledgers.account_id')
            ->select('chart_of_accounts.*', DB::raw('COALESCE(SUM(general_ledgers.credit - general_ledgers.debit), 0) as balance'))
            ->groupBy('chart_of_accounts.id', 'chart_of_accounts.code', 'chart_of_accounts.name',
                       'chart_of_accounts.type', 'chart_of_accounts.parent_id',
                       'chart_of_accounts.is_group', 'chart_of_accounts.description',
                       'chart_of_accounts.status', 'chart_of_accounts.created_at',
                       'chart_of_accounts.updated_at')
            ->get();

        $totalAssets = $assetAccounts->sum('balance');
        $totalLiabilities = $liabilityAccounts->sum('balance');
        $totalEquity = $equityAccounts->sum('balance');

        return view('admin.finance.balance-sheet', compact(
            'assetAccounts', 'liabilityAccounts', 'equityAccounts',
            'totalAssets', 'totalLiabilities', 'totalEquity'
        ));
    }

    public function gstReport(Request $request)
    {
        $dateFrom = $request->get('date_from', now()->startOfMonth()->toDateString());
        $dateTo = $request->get('date_to', now()->toDateString());

        $incomeWithGst = Income::whereBetween('income_date', [$dateFrom, $dateTo])->get();
        $expenseWithGst = Expense::whereBetween('expense_date', [$dateFrom, $dateTo])->where('status', 'approved')->get();

        $totalSales = $incomeWithGst->sum('amount');
        $totalPurchases = $expenseWithGst->sum('amount');
        $gstOnSales = $totalSales * 0.18;
        $gstOnPurchases = $totalPurchases * 0.18;
        $gstPayable = $gstOnSales - $gstOnPurchases;

        return view('admin.finance.gst-report', compact(
            'totalSales', 'totalPurchases', 'gstOnSales', 'gstOnPurchases', 'gstPayable',
            'dateFrom', 'dateTo'
        ));
    }

    public function tdsReport(Request $request)
    {
        $dateFrom = $request->get('date_from', now()->startOfYear()->toDateString());
        $dateTo = $request->get('date_to', now()->toDateString());

        $expenses = Expense::whereBetween('expense_date', [$dateFrom, $dateTo])
            ->where('status', 'approved')
            ->get();

        $totalExpenses = $expenses->sum('amount');
        $tdsAmount = $totalExpenses * 0.10;

        return view('admin.finance.tds-report', compact('expenses', 'totalExpenses', 'tdsAmount', 'dateFrom', 'dateTo'));
    }

    public function taxReport(Request $request)
    {
        $dateFrom = $request->get('date_from', now()->startOfYear()->toDateString());
        $dateTo = $request->get('date_to', now()->toDateString());

        $totalIncome = Income::whereBetween('income_date', [$dateFrom, $dateTo])->sum('amount');
        $totalExpense = Expense::whereBetween('expense_date', [$dateFrom, $dateTo])->where('status', 'approved')->sum('amount');
        $taxableIncome = $totalIncome - $totalExpense;

        $incomeTax = max(0, $taxableIncome * 0.30);
        $cess = $incomeTax * 0.04;
        $totalTax = $incomeTax + $cess;

        return view('admin.finance.tax-report', compact(
            'totalIncome', 'totalExpense', 'taxableIncome', 'incomeTax', 'cess', 'totalTax',
            'dateFrom', 'dateTo'
        ));
    }
}

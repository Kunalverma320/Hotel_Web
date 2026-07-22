@extends('admin.layouts.app')

@section('title', 'Finance Dashboard')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Finance Dashboard</h4>
    <div>
        <span class="text-muted">{{ now()->format('F Y') }}</span>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-3">
        <div class="card border-start border-4 border-success">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="text-muted mb-1">Monthly Income</h6>
                        <h3 class="text-success mb-0">₹{{ number_format($totalIncome, 2) }}</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-arrow-up fa-2x text-success opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-start border-4 border-danger">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="text-muted mb-1">Monthly Expense</h6>
                        <h3 class="text-danger mb-0">₹{{ number_format($totalExpense, 2) }}</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-arrow-down fa-2x text-danger opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-start border-4 border-warning">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="text-muted mb-1">Pending Expenses</h6>
                        <h3 class="text-warning mb-0">₹{{ number_format($pendingExpenses, 2) }}</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-clock fa-2x text-warning opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-start border-4 border-primary">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="text-muted mb-1">Net Profit</h6>
                        <h3 class="text-primary mb-0">₹{{ number_format($totalIncome - $totalExpense, 2) }}</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-chart-line fa-2x text-primary opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header"><h6 class="mb-0">Account Balances</h6></div>
            <div class="card-body table-responsive">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Account</th>
                            <th>Type</th>
                            <th class="text-end">Balance</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($accountBalances as $account)
                            <tr>
                                <td>
                                    <a href="{{ route('admin.finance.ledger', $account->id) }}">
                                        {{ $account->code }} - {{ $account->name }}
                                    </a>
                                </td>
                                <td><span class="badge bg-secondary">{{ ucfirst($account->type) }}</span></td>
                                <td class="text-end fw-bold">₹{{ number_format($account->ledger_entries_sum_balance ?? 0, 2) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="text-center text-muted">No accounts with balances.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header"><h6 class="mb-0">Quick Links</h6></div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.finance.income-create') }}" class="btn btn-outline-success text-start">
                        <i class="fas fa-plus-circle me-2"></i> Record Income
                    </a>
                    <a href="{{ route('admin.finance.expense-create') }}" class="btn btn-outline-danger text-start">
                        <i class="fas fa-minus-circle me-2"></i> Record Expense
                    </a>
                    <a href="{{ route('admin.finance.journal-create') }}" class="btn btn-outline-primary text-start">
                        <i class="fas fa-file-invoice me-2"></i> Journal Entry
                    </a>
                    <a href="{{ route('admin.finance.coa') }}" class="btn btn-outline-info text-start">
                        <i class="fas fa-sitemap me-2"></i> Chart of Accounts
                    </a>
                    <a href="{{ route('admin.finance.trial-balance') }}" class="btn btn-outline-secondary text-start">
                        <i class="fas fa-balance-scale me-2"></i> Trial Balance
                    </a>
                    <a href="{{ route('admin.finance.profit-loss') }}" class="btn btn-outline-warning text-start">
                        <i class="fas fa-chart-pie me-2"></i> Profit & Loss
                    </a>
                    <a href="{{ route('admin.finance.balance-sheet') }}" class="btn btn-outline-dark text-start">
                        <i class="fas fa-file-alt me-2"></i> Balance Sheet
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header"><h6 class="mb-0">Recent Income</h6></div>
            <div class="card-body">
                @forelse($recentIncomes as $income)
                    <div class="d-flex justify-content-between py-2 border-bottom">
                        <div>
                            <strong>{{ $income->category }}</strong>
                            <br><small class="text-muted">{{ $income->description }}</small>
                        </div>
                        <div class="text-end">
                            <span class="text-success fw-bold">+₹{{ number_format($income->amount, 2) }}</span>
                            <br><small class="text-muted">{{ $income->income_date }}</small>
                        </div>
                    </div>
                @empty
                    <p class="text-muted text-center">No recent income.</p>
                @endforelse
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header"><h6 class="mb-0">Recent Expenses</h6></div>
            <div class="card-body">
                @forelse($recentExpenses as $expense)
                    <div class="d-flex justify-content-between py-2 border-bottom">
                        <div>
                            <strong>{{ $expense->category }}</strong>
                            <br><small class="text-muted">{{ $expense->description }}</small>
                        </div>
                        <div class="text-end">
                            <span class="text-danger fw-bold">-₹{{ number_format($expense->amount, 2) }}</span>
                            <br><small class="text-muted">
                                {{ $expense->expense_date }}
                                <span class="badge bg-{{ $expense->status === 'approved' ? 'success' : 'warning' }} ms-1">
                                    {{ ucfirst($expense->status) }}
                                </span>
                            </small>
                        </div>
                    </div>
                @empty
                    <p class="text-muted text-center">No recent expenses.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

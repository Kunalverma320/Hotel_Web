@extends('admin.layouts.app')

@section('title', 'Expenses')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Expenses</h4>
    <a href="{{ route('admin.finance.expense-create') }}" class="btn btn-danger">
        <i class="fas fa-plus me-1"></i> Record Expense
    </a>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.finance.expense') }}" class="row g-3">
            <div class="col-md-2">
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                </select>
            </div>
            <div class="col-md-3">
                <input type="date" name="date_from" class="form-control" placeholder="From" value="{{ request('date_from') }}">
            </div>
            <div class="col-md-3">
                <input type="date" name="date_to" class="form-control" placeholder="To" value="{{ request('date_to') }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-outline-primary w-100">
                    <i class="fas fa-filter me-1"></i> Filter
                </button>
            </div>
            <div class="col-md-2">
                <a href="{{ route('admin.finance.expense') }}" class="btn btn-outline-secondary w-100">Clear</a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body table-responsive">
        <table class="table table-hover table-striped align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Date</th>
                    <th>Category</th>
                    <th>Description</th>
                    <th>Amount</th>
                    <th>Method</th>
                    <th>Status</th>
                    <th width="100">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($expenses as $expense)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($expense->expense_date)->format('d M Y') }}</td>
                        <td><span class="badge bg-warning text-dark">{{ $expense->category }}</span></td>
                        <td>{{ Str::limit($expense->description, 50) }}</td>
                        <td class="text-danger fw-bold">₹{{ number_format($expense->amount, 2) }}</td>
                        <td>{{ ucfirst(str_replace('_', ' ', $expense->payment_method)) }}</td>
                        <td>
                            @if($expense->status === 'approved')
                                <span class="badge bg-success">Approved</span>
                            @else
                                <span class="badge bg-warning">Pending</span>
                            @endif
                        </td>
                        <td>
                            @if($expense->status === 'pending')
                                <form action="{{ route('admin.finance.expense-approve', $expense->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-success" onclick="return confirm('Approve this expense?')">
                                        <i class="fas fa-check"></i> Approve
                                    </button>
                                </form>
                            @else
                                <span class="text-muted small">Approved</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">No expenses found.</td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr class="table-light fw-bold">
                    <td colspan="3">Total</td>
                    <td class="text-danger">₹{{ number_format($totalAmount, 2) }}</td>
                    <td colspan="3"></td>
                </tr>
            </tfoot>
        </table>
        {{ $expenses->withQueryString()->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection

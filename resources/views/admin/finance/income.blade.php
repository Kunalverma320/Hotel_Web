@extends('admin.layouts.app')

@section('title', 'Income')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Income</h4>
    <a href="{{ route('admin.finance.income-create') }}" class="btn btn-success">
        <i class="fas fa-plus me-1"></i> Record Income
    </a>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.finance.income') }}" class="row g-3">
            <div class="col-md-3">
                <label class="form-label">From</label>
                <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">To</label>
                <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label">&nbsp;</label>
                <button type="submit" class="btn btn-outline-primary w-100">
                    <i class="fas fa-filter me-1"></i> Filter
                </button>
            </div>
            <div class="col-md-2">
                <label class="form-label">&nbsp;</label>
                <a href="{{ route('admin.finance.income') }}" class="btn btn-outline-secondary w-100">Clear</a>
            </div>
            <div class="col-md-2">
                <label class="form-label">Total</label>
                <div class="form-control fw-bold text-success">₹{{ number_format($totalAmount, 2) }}</div>
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
                    <th>Payment Method</th>
                    <th>Reference</th>
                    <th>Account</th>
                </tr>
            </thead>
            <tbody>
                @forelse($incomes as $income)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($income->income_date)->format('d M Y') }}</td>
                        <td><span class="badge bg-info">{{ $income->category }}</span></td>
                        <td>{{ Str::limit($income->description, 50) }}</td>
                        <td class="text-success fw-bold">₹{{ number_format($income->amount, 2) }}</td>
                        <td>{{ ucfirst(str_replace('_', ' ', $income->payment_method)) }}</td>
                        <td>{{ $income->reference ?? '-' }}</td>
                        <td>{{ $income->account->name ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">No income records found.</td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr class="table-light fw-bold">
                    <td colspan="3">Total</td>
                    <td class="text-success">₹{{ number_format($totalAmount, 2) }}</td>
                    <td colspan="3"></td>
                </tr>
            </tfoot>
        </table>
        {{ $incomes->withQueryString()->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection

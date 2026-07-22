@extends('admin.layouts.app')

@section('title', 'Profit & Loss Statement')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Profit & Loss Statement</h4>
    <div class="d-flex gap-2">
        <button onclick="window.print()" class="btn btn-outline-secondary">
            <i class="fas fa-print me-1"></i> Print
        </button>
        <a href="{{ route('admin.finance.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Back
        </a>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.finance.profit-loss') }}" class="row g-3">
            <div class="col-md-4">
                <label class="form-label">From</label>
                <input type="date" name="date_from" class="form-control" value="{{ $dateFrom }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">To</label>
                <input type="date" name="date_to" class="form-control" value="{{ $dateTo }}">
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-primary me-2">
                    <i class="fas fa-filter me-1"></i> Generate
                </button>
                <a href="{{ route('admin.finance.profit-loss') }}" class="btn btn-outline-secondary">Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="text-center mb-4">
            <h5 class="fw-bold">Profit & Loss Statement</h5>
            <p class="text-muted">
                From {{ \Carbon\Carbon::parse($dateFrom)->format('d M Y') }}
                to {{ \Carbon\Carbon::parse($dateTo)->format('d M Y') }}
            </p>
        </div>

        <div class="row">
            <div class="col-md-6">
                <h6 class="text-success mb-3"><i class="fas fa-arrow-up me-1"></i> Income</h6>
                <table class="table table-sm">
                    <thead>
                        <tr><th>Account</th><th class="text-end">Amount (₹)</th></tr>
                    </thead>
                    <tbody>
                        @forelse($income as $inc)
                            <tr>
                                <td>{{ $inc->account->name ?? '-' }}</td>
                                <td class="text-end text-success">{{ number_format($inc->amount, 2) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="2" class="text-muted">No income records.</td></tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr class="fw-bold">
                            <td>Total Income</td>
                            <td class="text-end text-success">₹{{ number_format($totalIncome, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="col-md-6">
                <h6 class="text-danger mb-3"><i class="fas fa-arrow-down me-1"></i> Expenses</h6>
                <table class="table table-sm">
                    <thead>
                        <tr><th>Account</th><th class="text-end">Amount (₹)</th></tr>
                    </thead>
                    <tbody>
                        @forelse($expenses as $exp)
                            <tr>
                                <td>{{ $exp->account->name ?? '-' }}</td>
                                <td class="text-end text-danger">{{ number_format($exp->amount, 2) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="2" class="text-muted">No expense records.</td></tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr class="fw-bold">
                            <td>Total Expenses</td>
                            <td class="text-end text-danger">₹{{ number_format($totalExpenses, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <hr>

        <div class="row">
            <div class="col-md-6 offset-md-6">
                <table class="table table-bordered">
                    <tr class="{{ $netProfit >= 0 ? 'table-success' : 'table-danger' }}">
                        <td class="fw-bold fs-5">Net {{ $netProfit >= 0 ? 'Profit' : 'Loss' }}</td>
                        <td class="text-end fw-bold fs-5">
                            ₹{{ number_format(abs($netProfit), 2) }}
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    @media print {
        .no-print { display: none !important; }
        .card { border: none !important; box-shadow: none !important; }
        body { font-size: 11px; }
        .table-success, .table-danger { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
    }
</style>
@endpush
@endsection

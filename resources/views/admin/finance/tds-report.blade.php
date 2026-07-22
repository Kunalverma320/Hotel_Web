@extends('admin.layouts.app')

@section('title', 'TDS Report')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">TDS Report</h4>
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
        <form method="GET" action="{{ route('admin.finance.tds-report') }}" class="row g-3">
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
                <a href="{{ route('admin.finance.tds-report') }}" class="btn btn-outline-secondary">Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="text-center mb-4">
            <h5 class="fw-bold">TDS Report</h5>
            <p class="text-muted">
                Period: {{ \Carbon\Carbon::parse($dateFrom)->format('d M Y') }}
                to {{ \Carbon\Carbon::parse($dateTo)->format('d M Y') }}
            </p>
        </div>

        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card text-center border-primary">
                    <div class="card-body">
                        <h6 class="text-muted">Total Expenses</h6>
                        <h4 class="text-primary">₹{{ number_format($totalExpenses, 2) }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center border-danger">
                    <div class="card-body">
                        <h6 class="text-muted">TDS @ 10%</h6>
                        <h4 class="text-danger">₹{{ number_format($tdsAmount, 2) }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center border-success">
                    <div class="card-body">
                        <h6 class="text-muted">Net Payable</h6>
                        <h4 class="text-success">₹{{ number_format($totalExpenses - $tdsAmount, 2) }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <h6 class="mb-3">Expense Details</h6>
        <div class="table-responsive">
            <table class="table table-hover table-sm">
                <thead class="table-dark">
                    <tr>
                        <th>Date</th>
                        <th>Category</th>
                        <th>Description</th>
                        <th>Amount</th>
                        <th>TDS (10%)</th>
                        <th>Net Amount</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($expenses as $expense)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($expense->expense_date)->format('d M Y') }}</td>
                            <td>{{ $expense->category }}</td>
                            <td>{{ Str::limit($expense->description, 40) }}</td>
                            <td>₹{{ number_format($expense->amount, 2) }}</td>
                            <td class="text-danger">₹{{ number_format($expense->amount * 0.10, 2) }}</td>
                            <td class="text-success">₹{{ number_format($expense->amount * 0.90, 2) }}</td>
                            <td><span class="badge bg-success">Approved</span></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">No approved expenses in this period.</td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr class="table-light fw-bold">
                        <td colspan="3">Total</td>
                        <td>₹{{ number_format($totalExpenses, 2) }}</td>
                        <td class="text-danger">₹{{ number_format($tdsAmount, 2) }}</td>
                        <td class="text-success">₹{{ number_format($totalExpenses - $tdsAmount, 2) }}</td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

@push('styles')
<style>
    @media print {
        .no-print { display: none !important; }
        .card { border: none !important; box-shadow: none !important; }
        body { font-size: 11px; }
        .table-dark, .border-primary, .border-danger, .border-success {
            -webkit-print-color-adjust: exact; print-color-adjust: exact;
        }
    }
</style>
@endpush
@endsection

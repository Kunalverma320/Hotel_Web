@extends('admin.layouts.app')

@section('title', 'Tax Report')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Tax Report</h4>
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
        <form method="GET" action="{{ route('admin.finance.tax-report') }}" class="row g-3">
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
                <a href="{{ route('admin.finance.tax-report') }}" class="btn btn-outline-secondary">Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="text-center mb-4">
            <h5 class="fw-bold">Tax Report (Income Tax)</h5>
            <p class="text-muted">
                Assessment Year: {{ \Carbon\Carbon::parse($dateFrom)->format('Y') }}-{{ \Carbon\Carbon::parse($dateTo)->addYear()->format('Y') }}
            </p>
        </div>

        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card text-center border-success">
                    <div class="card-body">
                        <h6 class="text-muted">Total Income</h6>
                        <h4 class="text-success">₹{{ number_format($totalIncome, 2) }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center border-danger">
                    <div class="card-body">
                        <h6 class="text-muted">Total Expenses</h6>
                        <h4 class="text-danger">₹{{ number_format($totalExpense, 2) }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center border-primary">
                    <div class="card-body">
                        <h6 class="text-muted">Taxable Income</h6>
                        <h4 class="text-primary">₹{{ number_format($taxableIncome, 2) }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 offset-md-3">
                <div class="card">
                    <div class="card-header"><h6 class="mb-0">Tax Computation</h6></div>
                    <div class="card-body">
                        <table class="table table-sm mb-0">
                            <tr>
                                <td>Total Income</td>
                                <td class="text-end">₹{{ number_format($totalIncome, 2) }}</td>
                            </tr>
                            <tr>
                                <td>Less: Total Expenses</td>
                                <td class="text-end text-danger">(-₹{{ number_format($totalExpense, 2) }})</td>
                            </tr>
                            <tr class="border-top fw-bold">
                                <td>Taxable Income</td>
                                <td class="text-end">₹{{ number_format($taxableIncome, 2) }}</td>
                            </tr>
                            <tr>
                                <td>Income Tax @ 30%</td>
                                <td class="text-end">₹{{ number_format($incomeTax, 2) }}</td>
                            </tr>
                            <tr>
                                <td>Health & Education Cess @ 4%</td>
                                <td class="text-end">₹{{ number_format($cess, 2) }}</td>
                            </tr>
                            <tr class="border-top table-primary fw-bold fs-5">
                                <td>Total Tax Liability</td>
                                <td class="text-end">₹{{ number_format($totalTax, 2) }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="alert alert-info mt-4">
            <small>
                <i class="fas fa-info-circle me-1"></i>
                <strong>Note:</strong> This is an approximate tax computation based on the New Tax Regime (Section 115BAC).
                Actual tax liability may vary based on deductions, exemptions, and other applicable provisions.
                Please consult a tax professional for accurate assessment.
            </small>
        </div>
    </div>
</div>

@push('styles')
<style>
    @media print {
        .no-print { display: none !important; }
        .card { border: none !important; box-shadow: none !important; }
        body { font-size: 11px; }
        .table-primary, .border-success, .border-danger, .border-primary {
            -webkit-print-color-adjust: exact; print-color-adjust: exact;
        }
    }
</style>
@endpush
@endsection

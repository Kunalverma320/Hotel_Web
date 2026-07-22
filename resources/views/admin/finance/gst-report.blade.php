@extends('admin.layouts.app')

@section('title', 'GST Report')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">GST Report</h4>
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
        <form method="GET" action="{{ route('admin.finance.gst-report') }}" class="row g-3">
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
                <a href="{{ route('admin.finance.gst-report') }}" class="btn btn-outline-secondary">Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="text-center mb-4">
            <h5 class="fw-bold">GST Report</h5>
            <p class="text-muted">
                Period: {{ \Carbon\Carbon::parse($dateFrom)->format('d M Y') }}
                to {{ \Carbon\Carbon::parse($dateTo)->format('d M Y') }}
            </p>
        </div>

        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card text-center border-primary">
                    <div class="card-body">
                        <h6 class="text-muted">Total Sales</h6>
                        <h4 class="text-primary">₹{{ number_format($totalSales, 2) }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center border-success">
                    <div class="card-body">
                        <h6 class="text-muted">Total Purchases</h6>
                        <h4 class="text-success">₹{{ number_format($totalPurchases, 2) }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center border-info">
                    <div class="card-body">
                        <h6 class="text-muted">GST on Sales (18%)</h6>
                        <h4 class="text-info">₹{{ number_format($gstOnSales, 2) }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center border-warning">
                    <div class="card-body">
                        <h6 class="text-muted">GST on Purchases (18%)</h6>
                        <h4 class="text-warning">₹{{ number_format($gstOnPurchases, 2) }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 offset-md-3">
                <table class="table table-bordered">
                    <tr class="{{ $gstPayable >= 0 ? 'table-danger' : 'table-success' }}">
                        <td class="fw-bold fs-5">
                            {{ $gstPayable >= 0 ? 'GST Payable' : 'GST Receivable' }}
                        </td>
                        <td class="text-end fw-bold fs-5">
                            ₹{{ number_format(abs($gstPayable), 2) }}
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <hr>
        <h6 class="mb-3">GST Breakdown</h6>
        <div class="table-responsive">
            <table class="table table-sm table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>Component</th>
                        <th class="text-end">CGST (9%)</th>
                        <th class="text-end">SGST (9%)</th>
                        <th class="text-end">Total (18%)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>GST on Sales</td>
                        <td class="text-end">₹{{ number_format($gstOnSales / 2, 2) }}</td>
                        <td class="text-end">₹{{ number_format($gstOnSales / 2, 2) }}</td>
                        <td class="text-end">₹{{ number_format($gstOnSales, 2) }}</td>
                    </tr>
                    <tr>
                        <td>GST on Purchases (ITC)</td>
                        <td class="text-end">₹{{ number_format($gstOnPurchases / 2, 2) }}</td>
                        <td class="text-end">₹{{ number_format($gstOnPurchases / 2, 2) }}</td>
                        <td class="text-end">₹{{ number_format($gstOnPurchases, 2) }}</td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr class="fw-bold {{ $gstPayable >= 0 ? 'table-danger' : 'table-success' }}">
                        <td>Net GST {{ $gstPayable >= 0 ? 'Payable' : 'Refundable' }}</td>
                        <td class="text-end">₹{{ number_format(abs($gstPayable) / 2, 2) }}</td>
                        <td class="text-end">₹{{ number_format(abs($gstPayable) / 2, 2) }}</td>
                        <td class="text-end">₹{{ number_format(abs($gstPayable), 2) }}</td>
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
        .border-primary, .border-success, .border-info, .border-warning,
        .table-primary, .table-success, .table-danger, .table-warning, .table-info, .table-danger {
            -webkit-print-color-adjust: exact; print-color-adjust: exact;
        }
    }
</style>
@endpush
@endsection

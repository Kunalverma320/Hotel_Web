@extends('admin.layouts.app')

@section('title', 'Supplier Payments - ' . $supplier->name)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Payments: {{ $supplier->name }}</h4>
    <div class="d-flex gap-2">
        <button onclick="window.print()" class="btn btn-outline-secondary">
            <i class="fas fa-print me-1"></i> Print
        </button>
        <a href="{{ route('admin.suppliers.show', $supplier->id) }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Back
        </a>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-4">
        <div class="card text-center border-success">
            <div class="card-body">
                <h6 class="text-muted">Total Paid</h6>
                <h3 class="text-success mb-0">₹{{ number_format($totalPaid, 2) }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center border-primary">
            <div class="card-body">
                <h6 class="text-muted">Total Payments</h6>
                <h3 class="text-primary mb-0">{{ $payments->total() }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center border-info">
            <div class="card-body">
                <h6 class="text-muted">Supplier</h6>
                <h5 class="text-info mb-0">{{ $supplier->name }}</h5>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body table-responsive">
        <table class="table table-hover table-striped align-middle">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Date</th>
                    <th>Amount</th>
                    <th>Payment Method</th>
                    <th>Reference</th>
                    <th>Notes</th>
                    <th>Recorded By</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payments as $payment)
                    <tr>
                        <td>{{ $payment->id }}</td>
                        <td>{{ \Carbon\Carbon::parse($payment->payment_date)->format('d M Y') }}</td>
                        <td class="text-success fw-bold">₹{{ number_format($payment->amount, 2) }}</td>
                        <td>
                            @php
                                $methodIcons = [
                                    'cash' => 'fas fa-money-bill',
                                    'bank_transfer' => 'fas fa-university',
                                    'cheque' => 'fas fa-file-invoice',
                                    'online' => 'fas fa-globe',
                                ];
                            @endphp
                            <i class="{{ $methodIcons[$payment->payment_method] ?? 'fas fa-credit-card' }} me-1"></i>
                            {{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}
                        </td>
                        <td>{{ $payment->reference ?? '-' }}</td>
                        <td>{{ $payment->notes ?? '-' }}</td>
                        <td>{{ $payment->creator->name ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">No payments found.</td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr class="table-light fw-bold">
                    <td colspan="2">Total</td>
                    <td class="text-success">₹{{ number_format($totalPaid, 2) }}</td>
                    <td colspan="4"></td>
                </tr>
            </tfoot>
        </table>
        {{ $payments->links('pagination::bootstrap-5') }}
    </div>
</div>

@push('styles')
<style>
    @media print {
        .no-print { display: none !important; }
        .card { border: none !important; box-shadow: none !important; }
        body { font-size: 11px; }
        .table-dark { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
    }
</style>
@endpush
@endsection

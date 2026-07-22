@extends('admin.layouts.app')

@section('title', 'Supplier Ledger - ' . $supplier->name)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Ledger: {{ $supplier->name }}</h4>
    <div class="d-flex gap-2">
        <button onclick="window.print()" class="btn btn-outline-secondary">
            <i class="fas fa-print me-1"></i> Print
        </button>
        <a href="{{ route('admin.suppliers.show', $supplier->id) }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Back
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body table-responsive">
        <table class="table table-hover table-striped" id="ledgerTable">
            <thead class="table-dark">
                <tr>
                    <th>Date</th>
                    <th>Type</th>
                    <th>Reference</th>
                    <th class="text-end">Debit (₹)</th>
                    <th class="text-end">Credit (₹)</th>
                    <th class="text-end">Balance (₹)</th>
                </tr>
            </thead>
            <tbody>
                @forelse($ledger as $entry)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($entry['date'])->format('d M Y') }}</td>
                        <td>
                            <span class="badge bg-{{ $entry['type'] === 'Purchase' ? 'primary' : 'success' }}">
                                {{ $entry['type'] }}
                            </span>
                        </td>
                        <td><code>{{ $entry['reference'] }}</code></td>
                        <td class="text-end">
                            @if($entry['debit'] > 0)
                                <span class="text-danger">{{ number_format($entry['debit'], 2) }}</span>
                            @else
                                -
                            @endif
                        </td>
                        <td class="text-end">
                            @if($entry['credit'] > 0)
                                <span class="text-success">{{ number_format($entry['credit'], 2) }}</span>
                            @else
                                -
                            @endif
                        </td>
                        <td class="text-end fw-bold">{{ number_format($entry['balance'], 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">No ledger entries found.</td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr class="table-light fw-bold">
                    <td colspan="3">Closing Balance</td>
                    <td class="text-end text-danger">₹{{ number_format($ledger->where('type', 'Purchase')->sum('debit'), 2) }}</td>
                    <td class="text-end text-success">₹{{ number_format($ledger->where('type', 'Payment')->sum('credit'), 2) }}</td>
                    <td class="text-end">₹{{ number_format($runningBalance, 2) }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

@push('styles')
<style>
    @media print {
        .no-print { display: none !important; }
        .card { border: none !important; box-shadow: none !important; }
        body { font-size: 12px; }
    }
</style>
@endpush
@endsection

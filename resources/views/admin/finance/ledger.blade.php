@extends('admin.layouts.app')

@section('title', 'Account Ledger - ' . $account->name)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Ledger: {{ $account->code }} - {{ $account->name }}</h4>
    <div class="d-flex gap-2">
        <button onclick="window.print()" class="btn btn-outline-secondary">
            <i class="fas fa-print me-1"></i> Print
        </button>
        <a href="{{ route('admin.finance.coa') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Back
        </a>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <small class="text-muted">Account Type</small>
                <h6 class="mb-0">{{ ucfirst($account->type) }}</h6>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <small class="text-muted">Total Debit</small>
                <h6 class="text-success mb-0">₹{{ number_format($entries->sum('debit'), 2) }}</h6>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <small class="text-muted">Total Credit</small>
                <h6 class="text-danger mb-0">₹{{ number_format($entries->sum('credit'), 2) }}</h6>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <small class="text-muted">Balance</small>
                <h6 class="text-primary mb-0">₹{{ number_format($entries->sum('debit') - $entries->sum('credit'), 2) }}</h6>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body table-responsive">
        <table class="table table-hover table-sm">
            <thead class="table-dark">
                <tr>
                    <th>Date</th>
                    <th>Type</th>
                    <th>Reference</th>
                    <th>Description</th>
                    <th class="text-end">Debit (₹)</th>
                    <th class="text-end">Credit (₹)</th>
                    <th class="text-end">Balance (₹)</th>
                </tr>
            </thead>
            <tbody>
                @forelse($entries as $entry)
                    @php $runningBalance += $entry->debit - $entry->credit; @endphp
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($entry->date)->format('d M Y') }}</td>
                        <td><span class="badge bg-secondary">{{ ucfirst($entry->type) }}</span></td>
                        <td><code>{{ $entry->reference ?? '-' }}</code></td>
                        <td>{{ $entry->description }}</td>
                        <td class="text-end">
                            @if($entry->debit > 0)
                                <span class="text-success">{{ number_format($entry->debit, 2) }}</span>
                            @else - @endif
                        </td>
                        <td class="text-end">
                            @if($entry->credit > 0)
                                <span class="text-danger">{{ number_format($entry->credit, 2) }}</span>
                            @else - @endif
                        </td>
                        <td class="text-end fw-bold">₹{{ number_format($runningBalance, 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">No entries found for this account.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        {{ $entries->links('pagination::bootstrap-5') }}
    </div>
</div>

@push('styles')
<style>
    @media print {
        .no-print { display: none !important; }
        .card { border: none !important; }
        body { font-size: 11px; }
    }
</style>
@endpush
@endsection

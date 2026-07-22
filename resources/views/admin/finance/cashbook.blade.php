@extends('admin.layouts.app')

@section('title', 'Cash Book')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="fas fa-money-bill-wave me-2"></i>Cash Book</h4>
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
        <form method="GET" action="{{ route('admin.finance.cashbook') }}" class="row g-3">
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
                    <i class="fas fa-filter me-1"></i> Filter
                </button>
                <a href="{{ route('admin.finance.cashbook') }}" class="btn btn-outline-secondary">Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body table-responsive">
        <table class="table table-hover table-sm" id="printArea">
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
                <tr class="table-light">
                    <td colspan="6"><strong>Opening Balance</strong></td>
                    <td class="text-end fw-bold">₹{{ number_format($openingBalance, 2) }}</td>
                </tr>
                @php $runningBalance = $openingBalance; @endphp
                @forelse($entries as $entry)
                    @php
                        $runningBalance += $entry->debit - $entry->credit;
                    @endphp
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
                        <td colspan="7" class="text-center text-muted py-4">No entries found for this period.</td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr class="table-light fw-bold">
                    <td colspan="4">Closing Balance</td>
                    <td class="text-end text-success">₹{{ number_format($entries->sum('debit'), 2) }}</td>
                    <td class="text-end text-danger">₹{{ number_format($entries->sum('credit'), 2) }}</td>
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
        .card { border: none !important; }
        body { font-size: 11px; }
    }
</style>
@endpush
@endsection

@extends('admin.layouts.app')

@section('title', 'Journal Entry #' . $entry->entry_number)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Journal Entry #{{ $entry->entry_number }}</h4>
    <div class="d-flex gap-2">
        @if($entry->status === 'draft')
            <form action="{{ route('admin.finance.journal-post', $entry->id) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-success" onclick="return confirm('Post this journal entry? This action cannot be undone.')">
                    <i class="fas fa-check me-1"></i> Post Entry
                </button>
            </form>
        @endif
        <button onclick="window.print()" class="btn btn-outline-secondary">
            <i class="fas fa-print me-1"></i> Print
        </button>
        <a href="{{ route('admin.finance.journal') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Back
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header"><h6 class="mb-0">Entry Details</h6></div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <table class="table table-borderless mb-0">
                            <tr><th>Entry #</th><td><code>{{ $entry->entry_number }}</code></td></tr>
                            <tr><th>Date</th><td>{{ \Carbon\Carbon::parse($entry->entry_date)->format('d M Y') }}</td></tr>
                            <tr><th>Status</th>
                                <td>
                                    <span class="badge bg-{{ $entry->status === 'posted' ? 'success' : 'warning' }}">
                                        {{ ucfirst($entry->status) }}
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-8">
                        <table class="table table-borderless mb-0">
                            <tr><th>Description</th><td>{{ $entry->description }}</td></tr>
                            <tr><th>Reference</th><td>{{ $entry->reference ?? '-' }}</td></tr>
                            <tr><th>Created By</th><td>{{ $entry->creator->name ?? '-' }}</td></tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header"><h6 class="mb-0">Journal Lines</h6></div>
            <div class="card-body table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Account</th>
                            <th>Description</th>
                            <th class="text-end">Debit (₹)</th>
                            <th class="text-end">Credit (₹)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($entry->lines as $line)
                            <tr>
                                <td>
                                    <strong>{{ $line->account->code ?? '' }}</strong> -
                                    {{ $line->account->name ?? '-' }}
                                </td>
                                <td>{{ $line->description ?? '-' }}</td>
                                <td class="text-end">
                                    @if($line->debit > 0)
                                        <span class="text-success fw-bold">{{ number_format($line->debit, 2) }}</span>
                                    @else - @endif
                                </td>
                                <td class="text-end">
                                    @if($line->credit > 0)
                                        <span class="text-danger fw-bold">{{ number_format($line->credit, 2) }}</span>
                                    @else - @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="table-light fw-bold">
                            <td colspan="2" class="text-end">Totals</td>
                            <td class="text-end text-success">₹{{ number_format($entry->total_debit, 2) }}</td>
                            <td class="text-end text-danger">₹{{ number_format($entry->total_credit, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-body text-center">
                @if($entry->status === 'posted')
                    <div class="text-success">
                        <i class="fas fa-check-circle fa-3x mb-2"></i>
                        <h5>Posted</h5>
                        <small class="text-muted">This entry has been posted to the general ledger.</small>
                    </div>
                @else
                    <div class="text-warning">
                        <i class="fas fa-edit fa-3x mb-2"></i>
                        <h5>Draft</h5>
                        <small class="text-muted">This entry is in draft status. Post it to apply to the ledger.</small>
                    </div>
                @endif
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
    }
</style>
@endpush
@endsection

@extends('admin.layouts.app')

@section('title', 'Journal Entries')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Journal Entries</h4>
    <a href="{{ route('admin.finance.journal-create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i> New Journal Entry
    </a>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.finance.journal') }}" class="row g-3">
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="posted" {{ request('status') == 'posted' ? 'selected' : '' }}>Posted</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-outline-primary w-100">
                    <i class="fas fa-filter me-1"></i> Filter
                </button>
            </div>
            <div class="col-md-2">
                <a href="{{ route('admin.finance.journal') }}" class="btn btn-outline-secondary w-100">Clear</a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body table-responsive">
        <table class="table table-hover table-striped align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Entry #</th>
                    <th>Date</th>
                    <th>Description</th>
                    <th>Reference</th>
                    <th class="text-end">Debit</th>
                    <th class="text-end">Credit</th>
                    <th>Status</th>
                    <th width="80">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($entries as $entry)
                    <tr>
                        <td><code>{{ $entry->entry_number }}</code></td>
                        <td>{{ \Carbon\Carbon::parse($entry->entry_date)->format('d M Y') }}</td>
                        <td>{{ Str::limit($entry->description, 50) }}</td>
                        <td>{{ $entry->reference ?? '-' }}</td>
                        <td class="text-end">₹{{ number_format($entry->total_debit, 2) }}</td>
                        <td class="text-end">₹{{ number_format($entry->total_credit, 2) }}</td>
                        <td>
                            @if($entry->status === 'posted')
                                <span class="badge bg-success">Posted</span>
                            @else
                                <span class="badge bg-warning">Draft</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.finance.journal.show', $entry->id) }}" class="btn btn-sm btn-outline-info">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">No journal entries found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        {{ $entries->withQueryString()->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection

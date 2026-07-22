@extends('admin.layouts.app')

@section('title', 'Purchase Returns')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Purchase Returns</h4>
    <a href="{{ route('admin.purchases.return-create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i> Create Return
    </a>
</div>

<div class="card">
    <div class="card-body table-responsive">
        <table class="table table-hover table-striped align-middle">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>PO Number</th>
                    <th>Item</th>
                    <th>Quantity</th>
                    <th>Reason</th>
                    <th>Status</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($returns as $return)
                    <tr>
                        <td>{{ $return->id }}</td>
                        <td><code>{{ $return->purchaseOrder->po_number ?? '-' }}</code></td>
                        <td>{{ $return->inventoryItem->name ?? '-' }}</td>
                        <td>{{ $return->quantity }}</td>
                        <td>{{ Str::limit($return->reason, 50) }}</td>
                        <td>
                            @php
                                $statusColors = ['pending' => 'warning', 'approved' => 'success', 'rejected' => 'danger'];
                            @endphp
                            <span class="badge bg-{{ $statusColors[$return->status] ?? 'secondary' }}">
                                {{ ucfirst($return->status) }}
                            </span>
                        </td>
                        <td>{{ $return->created_at->format('d M Y') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">No purchase returns found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        {{ $returns->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection

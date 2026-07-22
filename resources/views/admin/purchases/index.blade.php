@extends('admin.layouts.app')

@section('title', 'Purchase Orders')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Purchase Orders</h4>
    <a href="{{ route('admin.purchases.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i> Create PO
    </a>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.purchases.index') }}" class="row g-3">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Search PO number..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    @foreach(['pending', 'approved', 'partial', 'received', 'cancelled'] as $status)
                        <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                            {{ ucfirst($status) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-outline-primary w-100">
                    <i class="fas fa-filter me-1"></i> Filter
                </button>
            </div>
            <div class="col-md-2">
                <a href="{{ route('admin.purchases.index') }}" class="btn btn-outline-secondary w-100">Clear</a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body table-responsive">
        <table class="table table-hover table-striped align-middle">
            <thead class="table-dark">
                <tr>
                    <th>PO Number</th>
                    <th>Supplier</th>
                    <th>Order Date</th>
                    <th>Expected Date</th>
                    <th>Total Amount</th>
                    <th>Status</th>
                    <th width="140">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                    <tr>
                        <td><code>{{ $order->po_number }}</code></td>
                        <td>{{ $order->supplier->name ?? '-' }}</td>
                        <td>{{ $order->order_date ? \Carbon\Carbon::parse($order->order_date)->format('d M Y') : '-' }}</td>
                        <td>{{ $order->expected_date ? \Carbon\Carbon::parse($order->expected_date)->format('d M Y') : '-' }}</td>
                        <td>₹{{ number_format($order->total_amount, 2) }}</td>
                        <td>
                            @php
                                $statusColors = [
                                    'pending' => 'warning',
                                    'approved' => 'info',
                                    'partial' => 'primary',
                                    'received' => 'success',
                                    'cancelled' => 'danger',
                                ];
                            @endphp
                            <span class="badge bg-{{ $statusColors[$order->status] ?? 'secondary' }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.purchases.show', $order->id) }}" class="btn btn-outline-info" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if($order->status === 'pending')
                                    <form action="{{ route('admin.purchases.approve', $order->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-success btn-sm" title="Approve" onclick="return confirm('Approve this PO?')">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                @endif
                                @if(!in_array($order->status, ['cancelled', 'received']))
                                    <form action="{{ route('admin.purchases.cancel', $order->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm" title="Cancel" onclick="return confirm('Cancel this PO?')">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">No purchase orders found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        {{ $orders->withQueryString()->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection

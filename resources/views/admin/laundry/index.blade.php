@extends('admin.layouts.app')

@section('title', 'Laundry Orders')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-tshirt"></i> Laundry Orders</h4>
    <div>
        <a href="{{ route('admin.laundry.reports') }}" class="btn btn-outline-secondary me-2">
            <i class="bi bi-graph-up"></i> Reports
        </a>
        <a href="{{ route('admin.laundry.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> New Order
        </a>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.laundry.index') }}" class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="received" {{ request('status') == 'received' ? 'selected' : '' }}>Received</option>
                    <option value="washing" {{ request('status') == 'washing' ? 'selected' : '' }}>Washing</option>
                    <option value="ironing" {{ request('status') == 'ironing' ? 'selected' : '' }}>Ironing</option>
                    <option value="ready" {{ request('status') == 'ready' ? 'selected' : '' }}>Ready</option>
                    <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Delivered</option>
                </select>
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-primary me-2"><i class="bi bi-search"></i> Filter</button>
                <a href="{{ route('admin.laundry.index') }}" class="btn btn-outline-secondary">Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Room</th>
                        <th>Guest</th>
                        <th>Items</th>
                        <th>Priority</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td>{{ $order->id }}</td>
                            <td><strong>{{ $order->room->room_number ?? 'N/A' }}</strong></td>
                            <td>{{ $order->guest_name ?? '-' }}</td>
                            <td>
                                <span class="badge bg-info">{{ $order->items->count() }} items</span>
                                @foreach($order->items->take(2) as $item)
                                    <br><small>{{ $item->quantity }}x {{ $item->laundryItem->name ?? 'Item' }}</small>
                                @endforeach
                            </td>
                            <td>
                                @switch($order->priority)
                                    @case('urgent')
                                        <span class="badge bg-danger">Urgent</span>
                                        @break
                                    @case('express')
                                        <span class="badge bg-warning text-dark">Express</span>
                                        @break
                                    @default
                                        <span class="badge bg-secondary">Normal</span>
                                @endswitch
                            </td>
                            <td><strong>${{ number_format($order->total_amount, 2) }}</strong></td>
                            <td>
                                @switch($order->status)
                                    @case('received')
                                        <span class="badge bg-info">Received</span>
                                        @break
                                    @case('washing')
                                        <span class="badge bg-primary">Washing</span>
                                        @break
                                    @case('ironing')
                                        <span class="badge bg-warning text-dark">Ironing</span>
                                        @break
                                    @case('ready')
                                        <span class="badge bg-success">Ready</span>
                                        @break
                                    @case('delivered')
                                        <span class="badge bg-secondary">Delivered</span>
                                        @break
                                    @default
                                        <span class="badge bg-secondary">{{ ucfirst($order->status) }}</span>
                                @endswitch
                            </td>
                            <td>{{ $order->created_at->format('M d, Y') }}</td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.laundry.show', $order->id) }}" class="btn btn-outline-primary"><i class="bi bi-eye"></i></a>
                                    @if($order->status !== 'delivered')
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">Status</button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="{{ route('admin.laundry.update-status', [$order->id, 'washing']) }}">Washing</a></li>
                                                <li><a class="dropdown-item" href="{{ route('admin.laundry.update-status', [$order->id, 'ironing']) }}">Ironing</a></li>
                                                <li><a class="dropdown-item" href="{{ route('admin.laundry.update-status', [$order->id, 'ready']) }}">Ready</a></li>
                                                <li><a class="dropdown-item" href="{{ route('admin.laundry.update-status', [$order->id, 'delivered']) }}">Delivered</a></li>
                                            </ul>
                                        </div>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-4 text-muted">No laundry orders found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer">
        {{ $orders->links() }}
    </div>
</div>
@endsection

@extends('admin.layouts.app')

@section('title', 'Room Service Orders')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-cone-striped"></i> Room Service Orders</h4>
    <a href="{{ route('admin.roomservice.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> New Order
    </a>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.roomservice.index') }}" class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="preparing" {{ request('status') == 'preparing' ? 'selected' : '' }}>Preparing</option>
                    <option value="on_the_way" {{ request('status') == 'on_the_way' ? 'selected' : '' }}>On the Way</option>
                    <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Delivered</option>
                </select>
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-primary me-2"><i class="bi bi-search"></i> Filter</button>
                <a href="{{ route('admin.roomservice.index') }}" class="btn btn-outline-secondary">Reset</a>
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
                        <th>Total</th>
                        <th>Status</th>
                        <th>Time</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td>{{ $order->id }}</td>
                            <td><strong>{{ $order->room->room_number ?? 'N/A' }}</strong></td>
                            <td>{{ $order->guest_name ?? $order->room->guest->name ?? '-' }}</td>
                            <td>
                                <span class="badge bg-info">{{ $order->items->count() }} items</span>
                                @foreach($order->items->take(2) as $item)
                                    <br><small>{{ $item->quantity }}x {{ $item->foodItem->name ?? 'Item' }}</small>
                                @endforeach
                                @if($order->items->count() > 2)
                                    <br><small class="text-muted">+{{ $order->items->count() - 2 }} more</small>
                                @endif
                            </td>
                            <td><strong>${{ number_format($order->total_amount, 2) }}</strong></td>
                            <td>
                                @switch($order->status)
                                    @case('pending')
                                        <span class="badge bg-warning text-dark">Pending</span>
                                        @break
                                    @case('preparing')
                                        <span class="badge bg-info">Preparing</span>
                                        @break
                                    @case('on_the_way')
                                        <span class="badge bg-primary">On the Way</span>
                                        @break
                                    @case('delivered')
                                        <span class="badge bg-success">Delivered</span>
                                        @break
                                    @default
                                        <span class="badge bg-secondary">{{ ucfirst($order->status) }}</span>
                                @endswitch
                            </td>
                            <td>{{ $order->created_at->format('h:i A') }}</td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.roomservice.show', $order->id) }}" class="btn btn-outline-primary"><i class="bi bi-eye"></i></a>
                                    @if($order->status !== 'delivered')
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">Status</button>
                                            <ul class="dropdown-menu">
                                                @if($order->status === 'pending')
                                                    <li><a class="dropdown-item" href="{{ route('admin.roomservice.update-status', [$order->id, 'preparing']) }}">Preparing</a></li>
                                                @endif
                                                @if(in_array($order->status, ['pending', 'preparing']))
                                                    <li><a class="dropdown-item" href="{{ route('admin.roomservice.update-status', [$order->id, 'on_the_way']) }}">On the Way</a></li>
                                                @endif
                                                <li><a class="dropdown-item" href="{{ route('admin.roomservice.update-status', [$order->id, 'delivered']) }}">Delivered</a></li>
                                            </ul>
                                        </div>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">No room service orders found.</td>
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

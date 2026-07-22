@extends('admin.layouts.app')

@section('title', 'Kitchen Orders Board')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-fire"></i> Kitchen Orders</h4>
    <div>
        <button class="btn btn-outline-primary" id="refreshBtn"><i class="bi bi-arrow-clockwise"></i> Refresh</button>
    </div>
</div>

<div class="row g-3" id="kanbanBoard">
    <div class="col-lg-3">
        <div class="card border-warning h-100">
            <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center">
                <h6 class="mb-0"><i class="bi bi-hourglass-split"></i> Pending</h6>
                <span class="badge bg-dark">{{ $orders->where('status', 'pending')->count() }}</span>
            </div>
            <div class="card-body p-2 kanban-column" style="min-height:400px;background:#fff8e1;" data-status="pending">
                @foreach($orders->where('status', 'pending') as $order)
                    <div class="card mb-2 shadow-sm order-card">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <strong class="text-primary">#{{ $order->id }}</strong>
                                    <small class="text-muted ms-1">Table {{ $order->table->table_number ?? 'Room' }}</small>
                                </div>
                                <small class="text-muted">{{ $order->created_at->format('h:i A') }}</small>
                            </div>
                            <div class="mb-2">
                                @foreach($order->items as $item)
                                    <div class="d-flex justify-content-between">
                                        <small>{{ $item->quantity }}x {{ $item->foodItem->name ?? 'Item' }}</small>
                                    </div>
                                @endforeach
                            </div>
                            @if($order->notes)
                                <small class="text-muted d-block mb-2"><i class="bi bi-chat-left-text"></i> {{ $order->notes }}</small>
                            @endif
                            <div class="btn-group btn-group-sm w-100">
                                <a href="{{ route('admin.restaurant.update-order-status', [$order->id, 'preparing']) }}" class="btn btn-warning">
                                    <i class="bi bi-arrow-right"></i> Start Preparing
                                </a>
                                <a href="{{ route('admin.restaurant.update-order-status', [$order->id, 'cancelled']) }}" class="btn btn-outline-danger">
                                    <i class="bi bi-x-lg"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
                @if($orders->where('status', 'pending')->isEmpty())
                    <div class="text-center text-muted py-4">
                        <i class="bi bi-inbox" style="font-size:32px;"></i>
                        <p class="mt-2 mb-0">No pending orders</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-3">
        <div class="card border-primary h-100">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h6 class="mb-0"><i class="bi bi-fire"></i> Preparing</h6>
                <span class="badge bg-light text-dark">{{ $orders->where('status', 'preparing')->count() }}</span>
            </div>
            <div class="card-body p-2 kanban-column" style="min-height:400px;background:#e3f2fd;" data-status="preparing">
                @foreach($orders->where('status', 'preparing') as $order)
                    <div class="card mb-2 shadow-sm order-card border-primary">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <strong class="text-primary">#{{ $order->id }}</strong>
                                    <small class="text-muted ms-1">Table {{ $order->table->table_number ?? 'Room' }}</small>
                                </div>
                                <span class="badge bg-primary">Cooking</span>
                            </div>
                            <div class="mb-2">
                                @foreach($order->items as $item)
                                    <div class="d-flex justify-content-between">
                                        <small>{{ $item->quantity }}x {{ $item->foodItem->name ?? 'Item' }}</small>
                                    </div>
                                @endforeach
                            </div>
                            <a href="{{ route('admin.restaurant.update-order-status', [$order->id, 'ready']) }}" class="btn btn-success btn-sm w-100">
                                <i class="bi bi-check-lg"></i> Mark Ready
                            </a>
                        </div>
                    </div>
                @endforeach
                @if($orders->where('status', 'preparing')->isEmpty())
                    <div class="text-center text-muted py-4">
                        <i class="bi bi-inbox" style="font-size:32px;"></i>
                        <p class="mt-2 mb-0">Nothing cooking</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-3">
        <div class="card border-success h-100">
            <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                <h6 class="mb-0"><i class="bi bi-bell"></i> Ready</h6>
                <span class="badge bg-light text-dark">{{ $orders->where('status', 'ready')->count() }}</span>
            </div>
            <div class="card-body p-2 kanban-column" style="min-height:400px;background:#e8f5e9;" data-status="ready">
                @foreach($orders->where('status', 'ready') as $order)
                    <div class="card mb-2 shadow-sm order-card border-success">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <strong class="text-primary">#{{ $order->id }}</strong>
                                    <small class="text-muted ms-1">Table {{ $order->table->table_number ?? 'Room' }}</small>
                                </div>
                                <span class="badge bg-success">Ready!</span>
                            </div>
                            <div class="mb-2">
                                @foreach($order->items as $item)
                                    <div class="d-flex justify-content-between">
                                        <small>{{ $item->quantity }}x {{ $item->foodItem->name ?? 'Item' }}</small>
                                    </div>
                                @endforeach
                            </div>
                            <a href="{{ route('admin.restaurant.update-order-status', [$order->id, 'served']) }}" class="btn btn-dark btn-sm w-100">
                                <i class="bi bi-check-all"></i> Mark Served
                            </a>
                        </div>
                    </div>
                @endforeach
                @if($orders->where('status', 'ready')->isEmpty())
                    <div class="text-center text-muted py-4">
                        <i class="bi bi-inbox" style="font-size:32px;"></i>
                        <p class="mt-2 mb-0">Nothing ready</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-3">
        <div class="card border-secondary h-100">
            <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
                <h6 class="mb-0"><i class="bi bi-check-circle"></i> Served</h6>
                <span class="badge bg-light text-dark">Today</span>
            </div>
            <div class="card-body p-2" style="min-height:400px;background:#f5f5f5;">
                @php
                    $servedToday = \App\Models\KitchenOrder::with('table')
                        ->where('status', 'served')
                        ->whereDate('updated_at', today())
                        ->latest('updated_at')
                        ->limit(10)
                        ->get();
                @endphp
                @foreach($servedToday as $order)
                    <div class="card mb-2 shadow-sm" style="opacity:0.7;">
                        <div class="card-body p-2">
                            <div class="d-flex justify-content-between">
                                <small><strong>#{{ $order->id }}</strong> - Table {{ $order->table->table_number ?? 'N/A' }}</small>
                                <small class="text-muted">{{ $order->updated_at->format('h:i A') }}</small>
                            </div>
                        </div>
                    </div>
                @endforeach
                @if($servedToday->isEmpty())
                    <div class="text-center text-muted py-4">
                        <i class="bi bi-inbox" style="font-size:32px;"></i>
                        <p class="mt-2 mb-0">No orders served today</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('refreshBtn').addEventListener('click', function() {
        location.reload();
    });
    setInterval(function() { location.reload(); }, 60000);
</script>
@endsection

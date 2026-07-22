@extends('admin.layouts.app')

@section('title', 'Purchase Order #' . $order->po_number)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">PO #{{ $order->po_number }}</h4>
    <div class="d-flex gap-2">
        @if($order->status === 'pending')
            <form action="{{ route('admin.purchases.approve', $order->id) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-success" onclick="return confirm('Approve this PO?')">
                    <i class="fas fa-check me-1"></i> Approve
                </button>
            </form>
        @endif
        @if(!in_array($order->status, ['cancelled', 'received']))
            <form action="{{ route('admin.purchases.cancel', $order->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger" onclick="return confirm('Cancel this PO?')">
                    <i class="fas fa-times me-1"></i> Cancel
                </button>
            </form>
        @endif
        <a href="{{ route('admin.purchases.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Back
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header"><h6 class="mb-0">Order Information</h6></div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless mb-0">
                            <tr><th>PO Number</th><td><code>{{ $order->po_number }}</code></td></tr>
                            <tr><th>Supplier</th><td>{{ $order->supplier->name ?? '-' }}</td></tr>
                            <tr><th>Order Date</th><td>{{ $order->order_date ? \Carbon\Carbon::parse($order->order_date)->format('d M Y') : '-' }}</td></tr>
                            <tr><th>Expected Date</th><td>{{ $order->expected_date ? \Carbon\Carbon::parse($order->expected_date)->format('d M Y') : '-' }}</td></tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        @php
                            $statusColors = [
                                'pending' => 'warning',
                                'approved' => 'info',
                                'partial' => 'primary',
                                'received' => 'success',
                                'cancelled' => 'danger',
                            ];
                        @endphp
                        <div class="text-center p-3 border rounded mb-3">
                            <small class="text-muted">Status</small>
                            <h4 class="mb-0 badge bg-{{ $statusColors[$order->status] ?? 'secondary' }} fs-6">
                                {{ ucfirst($order->status) }}
                            </h4>
                        </div>
                        <table class="table table-borderless mb-0">
                            <tr><th>Subtotal</th><td class="text-end">₹{{ number_format($order->subtotal, 2) }}</td></tr>
                            <tr><th>Tax</th><td class="text-end">₹{{ number_format($order->tax_amount, 2) }}</td></tr>
                            <tr class="border-top"><th>Total</th><td class="text-end fw-bold fs-5">₹{{ number_format($order->total_amount, 2) }}</td></tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header"><h6 class="mb-0">Order Items</h6></div>
            <div class="card-body table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Item</th>
                            <th>SKU</th>
                            <th>Quantity</th>
                            <th>Received</th>
                            <th>Unit Price</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                            <tr>
                                <td>{{ $item->inventoryItem->name ?? '-' }}</td>
                                <td><code>{{ $item->inventoryItem->sku ?? '-' }}</code></td>
                                <td>{{ $item->quantity }}</td>
                                <td>
                                    <span class="badge bg-{{ $item->received_quantity >= $item->quantity ? 'success' : 'warning' }}">
                                        {{ $item->received_quantity }}
                                    </span>
                                </td>
                                <td>₹{{ number_format($item->unit_price, 2) }}</td>
                                <td class="fw-bold">₹{{ number_format($item->total_price, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        @if(in_array($order->status, ['approved', 'partial']))
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h6 class="mb-0"><i class="fas fa-truck me-1"></i> Receive Items</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.purchases.receive', $order->id) }}" method="POST">
                    @csrf
                    <div id="receiveItems">
                        @foreach($order->items as $item)
                            @if($item->received_quantity < $item->quantity)
                            <div class="mb-3 p-2 border rounded">
                                <label class="form-label fw-bold">{{ $item->inventoryItem->name ?? 'Item' }}</label>
                                <small class="d-block text-muted mb-1">
                                    Ordered: {{ $item->quantity }} | Received: {{ $item->received_quantity }} | Pending: {{ $item->quantity - $item->received_quantity }}
                                </small>
                                <input type="hidden" name="received_items[{{ $loop->index }}][item_id]" value="{{ $item->inventory_item_id }}">
                                <input type="number" name="received_items[{{ $loop->index }}][quantity]"
                                       class="form-control form-control-sm"
                                       min="1" max="{{ $item->quantity - $item->received_quantity }}"
                                       placeholder="Qty to receive">
                            </div>
                            @endif
                        @endforeach
                    </div>
                    <button type="submit" class="btn btn-success w-100">
                        <i class="fas fa-check me-1"></i> Confirm Receipt
                    </button>
                </form>
            </div>
        </div>
        @endif

        <div class="card mb-4">
            <div class="card-header"><h6 class="mb-0">Order Notes</h6></div>
            <div class="card-body">
                <p class="text-muted mb-0">{{ $order->notes ?? 'No notes.' }}</p>
            </div>
        </div>
    </div>
</div>
@endsection

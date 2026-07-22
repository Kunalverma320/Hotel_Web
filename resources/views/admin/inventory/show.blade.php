@extends('admin.layouts.app')

@section('title', $item->name)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">{{ $item->name }}</h4>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.inventory.edit', $item->id) }}" class="btn btn-primary">
            <i class="fas fa-edit me-1"></i> Edit
        </a>
        <a href="{{ route('admin.inventory.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Back
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-6">
        <div class="card mb-4">
            <div class="card-header"><h6 class="mb-0">Item Information</h6></div>
            <div class="card-body">
                <table class="table table-borderless mb-0">
                    <tr><th width="140">SKU</th><td><code>{{ $item->sku }}</code></td></tr>
                    <tr><th>Category</th><td>{{ $item->category->name ?? '-' }}</td></tr>
                    <tr><th>Description</th><td>{{ $item->description ?? '-' }}</td></tr>
                    <tr><th>Unit</th><td>{{ $item->unit }}</td></tr>
                    <tr><th>Warehouse</th><td>{{ $item->warehouse->name ?? '-' }}</td></tr>
                    <tr><th>Barcode</th><td>{{ $item->barcode ?? '-' }}</td></tr>
                    <tr><th>Status</th>
                        <td><span class="badge bg-{{ $item->status === 'active' ? 'success' : 'secondary' }}">{{ ucfirst($item->status) }}</span></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card mb-4">
            <div class="card-header"><h6 class="mb-0">Stock & Pricing</h6></div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-4">
                        <div class="border rounded p-3">
                            <h3 class="mb-0 {{ $item->stock_quantity <= $item->minimum_stock ? 'text-danger' : 'text-success' }}">
                                {{ $item->stock_quantity }}
                            </h3>
                            <small class="text-muted">Current Stock</small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="border rounded p-3">
                            <h6 class="mb-0">{{ $item->minimum_stock }}</h6>
                            <small class="text-muted">Min Stock</small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="border rounded p-3">
                            <h6 class="mb-0">{{ $item->maximum_stock ?? '-' }}</h6>
                            <small class="text-muted">Max Stock</small>
                        </div>
                    </div>
                </div>
                <hr>
                <table class="table table-borderless mb-0">
                    <tr><th>Cost Price</th><td class="text-end">₹{{ number_format($item->cost_price, 2) }}</td></tr>
                    <tr><th>Selling Price</th><td class="text-end">₹{{ number_format($item->selling_price, 2) }}</td></tr>
                    <tr><th>Stock Value</th><td class="text-end fw-bold">₹{{ number_format($item->stock_quantity * $item->cost_price, 2) }}</td></tr>
                </table>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header"><h6 class="mb-0">Stock In</h6></div>
            <div class="card-body">
                <form action="{{ route('admin.inventory.stockIn', $item->id) }}" method="POST">
                    @csrf
                    <div class="row g-2">
                        <div class="col-md-4">
                            <input type="number" name="quantity" class="form-control" placeholder="Qty" min="1" required>
                        </div>
                        <div class="col-md-4">
                            <input type="text" name="reference" class="form-control" placeholder="Reference">
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-success w-100">
                                <i class="fas fa-plus me-1"></i> Stock In
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header"><h6 class="mb-0">Stock Out</h6></div>
            <div class="card-body">
                <form action="{{ route('admin.inventory.stockOut', $item->id) }}" method="POST">
                    @csrf
                    <div class="row g-2">
                        <div class="col-md-4">
                            <input type="number" name="quantity" class="form-control" placeholder="Qty" min="1" max="{{ $item->stock_quantity }}" required>
                        </div>
                        <div class="col-md-4">
                            <input type="text" name="reference" class="form-control" placeholder="Reference">
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-warning w-100">
                                <i class="fas fa-minus me-1"></i> Stock Out
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header"><h6 class="mb-0">Stock Movement History</h6></div>
    <div class="card-body table-responsive">
        <table class="table table-hover table-sm align-middle">
            <thead class="table-light">
                <tr>
                    <th>Date</th>
                    <th>Type</th>
                    <th>Quantity</th>
                    <th>Reference</th>
                    <th>Notes</th>
                    <th>By</th>
                </tr>
            </thead>
            <tbody>
                @forelse($stockMovements as $movement)
                    <tr>
                        <td>{{ $movement->created_at->format('d M Y H:i') }}</td>
                        <td>
                            @php
                                $typeColors = [
                                    'in' => 'success', 'out' => 'warning',
                                    'transfer_in' => 'info', 'transfer_out' => 'info',
                                    'adjustment' => 'primary',
                                ];
                            @endphp
                            <span class="badge bg-{{ $typeColors[$movement->type] ?? 'secondary' }}">
                                {{ str_replace('_', ' ', ucfirst($movement->type)) }}
                            </span>
                        </td>
                        <td>{{ $movement->quantity }}</td>
                        <td>{{ $movement->reference ?? '-' }}</td>
                        <td>{{ $movement->notes ?? '-' }}</td>
                        <td>{{ $movement->performedBy->name ?? '-' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted">No stock movements recorded.</td></tr>
                @endforelse
            </tbody>
        </table>
        {{ $stockMovements->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection

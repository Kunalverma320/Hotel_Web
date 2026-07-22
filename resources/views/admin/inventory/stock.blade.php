@extends('admin.layouts.app')

@section('title', 'Stock Management')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Stock Management</h4>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.inventory.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Back
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-6">
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h6 class="mb-0"><i class="fas fa-arrow-down me-1"></i> Stock In</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.inventory.index') }}" method="POST" id="stockInForm">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Select Item <span class="text-danger">*</span></label>
                        <select name="item_id" class="form-select" required>
                            <option value="">Choose an item...</option>
                            @foreach($items ?? [] as $item)
                                <option value="{{ $item->id }}">
                                    {{ $item->name }} (SKU: {{ $item->sku }}) - Stock: {{ $item->stock_quantity }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Quantity <span class="text-danger">*</span></label>
                        <input type="number" name="quantity" class="form-control" min="1" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Reference</label>
                        <input type="text" name="reference" class="form-control" placeholder="e.g., PO-20260101-0001">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" class="form-control" rows="2"></textarea>
                    </div>
                    <button type="submit" class="btn btn-success w-100">
                        <i class="fas fa-plus me-1"></i> Add Stock
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card mb-4">
            <div class="card-header bg-warning">
                <h6 class="mb-0"><i class="fas fa-arrow-up me-1"></i> Stock Out</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.inventory.index') }}" method="POST" id="stockOutForm">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Select Item <span class="text-danger">*</span></label>
                        <select name="item_id" class="form-select" required>
                            <option value="">Choose an item...</option>
                            @foreach($items ?? [] as $item)
                                <option value="{{ $item->id }}" data-stock="{{ $item->stock_quantity }}">
                                    {{ $item->name }} (SKU: {{ $item->sku }}) - Stock: {{ $item->stock_quantity }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Quantity <span class="text-danger">*</span></label>
                        <input type="number" name="quantity" class="form-control" min="1" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Reference</label>
                        <input type="text" name="reference" class="form-control" placeholder="e.g., Usage reference">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" class="form-control" rows="2"></textarea>
                    </div>
                    <button type="submit" class="btn btn-warning w-100">
                        <i class="fas fa-minus me-1"></i> Remove Stock
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header bg-info text-white">
        <h6 class="mb-0"><i class="fas fa-exchange-alt me-1"></i> Transfer Stock</h6>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.inventory.index') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">From Item <span class="text-danger">*</span></label>
                    <select name="from_item_id" class="form-select" required>
                        <option value="">Source item...</option>
                        @foreach($items ?? [] as $item)
                            <option value="{{ $item->id }}">{{ $item->name }} - Stock: {{ $item->stock_quantity }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">To Item <span class="text-danger">*</span></label>
                    <select name="to_item_id" class="form-select" required>
                        <option value="">Destination item...</option>
                        @foreach($items ?? [] as $item)
                            <option value="{{ $item->id }}">{{ $item->name }} - Stock: {{ $item->stock_quantity }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 mb-3">
                    <label class="form-label">Quantity <span class="text-danger">*</span></label>
                    <input type="number" name="quantity" class="form-control" min="1" required>
                </div>
                <div class="col-md-2 mb-3">
                    <label class="form-label">&nbsp;</label>
                    <button type="submit" class="btn btn-info text-white w-100">
                        <i class="fas fa-exchange-alt me-1"></i> Transfer
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header"><h6 class="mb-0">Recent Stock Movements</h6></div>
    <div class="card-body table-responsive">
        <table class="table table-hover table-sm">
            <thead class="table-light">
                <tr>
                    <th>Date</th>
                    <th>Item</th>
                    <th>Type</th>
                    <th>Quantity</th>
                    <th>Reference</th>
                </tr>
            </thead>
            <tbody>
                @forelse($movements ?? [] as $movement)
                    <tr>
                        <td>{{ $movement->created_at->format('d M Y H:i') }}</td>
                        <td>{{ $movement->inventoryItem->name ?? '-' }}</td>
                        <td><span class="badge bg-secondary">{{ $movement->type }}</span></td>
                        <td>{{ $movement->quantity }}</td>
                        <td>{{ $movement->reference ?? '-' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted">No recent movements.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

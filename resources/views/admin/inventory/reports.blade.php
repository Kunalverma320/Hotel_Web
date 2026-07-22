@extends('admin.layouts.app')

@section('title', 'Inventory Reports')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Inventory Reports</h4>
    <a href="{{ route('admin.inventory.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i> Back
    </a>
</div>

<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card text-center border-primary">
            <div class="card-body">
                <i class="fas fa-boxes fa-3x text-primary mb-3"></i>
                <h2 class="text-primary">{{ number_format($totalItems) }}</h2>
                <p class="text-muted mb-0">Total Items</p>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="card text-center border-success">
            <div class="card-body">
                <i class="fas fa-rupee-sign fa-3x text-success mb-3"></i>
                <h2 class="text-success">₹{{ number_format($totalValue, 2) }}</h2>
                <p class="text-muted mb-0">Total Stock Value</p>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="card text-center border-danger">
            <div class="card-body">
                <i class="fas fa-exclamation-triangle fa-3x text-danger mb-3"></i>
                <h2 class="text-danger">{{ number_format($lowStockCount) }}</h2>
                <p class="text-muted mb-0">Low Stock Items</p>
            </div>
        </div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header">
        <h6 class="mb-0">Stock by Category</h6>
    </div>
    <div class="card-body table-responsive">
        <table class="table table-hover">
            <thead class="table-light">
                <tr>
                    <th>Category</th>
                    <th>Total Items</th>
                    <th>Total Stock Value</th>
                    <th>Low Stock Items</th>
                </tr>
            </thead>
            <tbody>
                @foreach($categories as $category)
                    <tr>
                        <td><strong>{{ $category->name }}</strong></td>
                        <td>{{ $category->items_count }}</td>
                        <td>₹{{ number_format($category->items->sum(function($item) { return $item->stock_quantity * $item->cost_price; }), 2) }}</td>
                        <td>
                            @php
                                $lowCount = $category->items->filter(function($item) {
                                    return $item->stock_quantity <= $item->minimum_stock;
                                })->count();
                            @endphp
                            @if($lowCount > 0)
                                <span class="badge bg-danger">{{ $lowCount }}</span>
                            @else
                                <span class="badge bg-success">0</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h6 class="mb-0">Quick Actions</h6>
    </div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-3">
                <a href="{{ route('admin.inventory.lowStock') }}" class="btn btn-outline-danger w-100 py-3">
                    <i class="fas fa-exclamation-triangle fa-2x mb-2 d-block"></i>
                    Low Stock Alerts
                </a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('admin.inventory.index', ['low_stock' => 1]) }}" class="btn btn-outline-warning w-100 py-3">
                    <i class="fas fa-filter fa-2x mb-2 d-block"></i>
                    Filter Low Stock
                </a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('admin.inventory.index') }}" class="btn btn-outline-primary w-100 py-3">
                    <i class="fas fa-list fa-2x mb-2 d-block"></i>
                    All Items
                </a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('admin.inventory.categories') }}" class="btn btn-outline-info w-100 py-3">
                    <i class="fas fa-tags fa-2x mb-2 d-block"></i>
                    Categories
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

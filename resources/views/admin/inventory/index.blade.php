@extends('admin.layouts.app')

@section('title', 'Inventory Items')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Inventory Items</h4>
    <a href="{{ route('admin.inventory.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i> Add Item
    </a>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.inventory.index') }}" class="row g-3">
            <div class="col-md-3">
                <input type="text" name="search" class="form-control" placeholder="Search name, SKU, barcode..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="category" class="form-select">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <div class="form-check form-switch mt-2">
                    <input class="form-check-input" type="checkbox" name="low_stock" value="1" id="lowStockFilter" {{ request('low_stock') ? 'checked' : '' }}>
                    <label class="form-check-label" for="lowStockFilter">Low Stock Only</label>
                </div>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-outline-primary w-100">
                    <i class="fas fa-filter me-1"></i> Filter
                </button>
            </div>
            <div class="col-md-2">
                <a href="{{ route('admin.inventory.index') }}" class="btn btn-outline-secondary w-100">Clear</a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body table-responsive">
        <table class="table table-hover table-striped align-middle">
            <thead class="table-dark">
                <tr>
                    <th>SKU</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Unit</th>
                    <th>Stock</th>
                    <th>Min Stock</th>
                    <th>Cost Price</th>
                    <th>Selling Price</th>
                    <th>Status</th>
                    <th width="120">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $item)
                    <tr class="{{ $item->stock_quantity <= $item->minimum_stock ? 'table-warning' : '' }}">
                        <td><code>{{ $item->sku }}</code></td>
                        <td>
                            <a href="{{ route('admin.inventory.show', $item->id) }}">{{ $item->name }}</a>
                        </td>
                        <td>{{ $item->category->name ?? '-' }}</td>
                        <td>{{ $item->unit }}</td>
                        <td>
                            <span class="badge {{ $item->stock_quantity <= $item->minimum_stock ? 'bg-danger' : 'bg-success' }}">
                                {{ $item->stock_quantity }}
                            </span>
                        </td>
                        <td>{{ $item->minimum_stock }}</td>
                        <td>{{ number_format($item->cost_price, 2) }}</td>
                        <td>{{ number_format($item->selling_price, 2) }}</td>
                        <td>
                            <span class="badge bg-{{ $item->status === 'active' ? 'success' : 'secondary' }}">
                                {{ ucfirst($item->status) }}
                            </span>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.inventory.show', $item->id) }}" class="btn btn-outline-info" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.inventory.edit', $item->id) }}" class="btn btn-outline-primary" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.inventory.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="text-center text-muted py-4">No inventory items found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        {{ $items->withQueryString()->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection

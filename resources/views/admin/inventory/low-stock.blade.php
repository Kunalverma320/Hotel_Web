@extends('admin.layouts.app')

@section('title', 'Low Stock Alerts')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">
        <i class="fas fa-exclamation-triangle text-warning me-2"></i>Low Stock Alerts
    </h4>
    <div class="d-flex gap-2">
        <span class="badge bg-danger fs-6">{{ $items->total() }} items below minimum</span>
        <a href="{{ route('admin.inventory.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Back
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body table-responsive">
        <table class="table table-hover table-striped align-middle">
            <thead class="table-dark">
                <tr>
                    <th>SKU</th>
                    <th>Item Name</th>
                    <th>Category</th>
                    <th>Current Stock</th>
                    <th>Minimum Stock</th>
                    <th>Deficit</th>
                    <th>Status</th>
                    <th width="100">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $item)
                    <tr>
                        <td><code>{{ $item->sku }}</code></td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->category->name ?? '-' }}</td>
                        <td>
                            <strong class="text-danger">{{ $item->stock_quantity }}</strong>
                        </td>
                        <td>{{ $item->minimum_stock }}</td>
                        <td>
                            <span class="badge bg-danger">
                                {{ $item->minimum_stock - $item->stock_quantity }} below
                            </span>
                        </td>
                        <td>
                            @if($item->stock_quantity == 0)
                                <span class="badge bg-dark">Out of Stock</span>
                            @else
                                <span class="badge bg-warning">Low Stock</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.inventory.show', $item->id) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-edit"></i> Restock
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-4">
                            <div class="text-success">
                                <i class="fas fa-check-circle fa-3x mb-2"></i>
                                <p>All items are adequately stocked!</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        {{ $items->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection

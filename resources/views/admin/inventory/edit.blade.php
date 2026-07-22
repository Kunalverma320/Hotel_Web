@extends('admin.layouts.app')

@section('title', 'Edit Inventory Item')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Edit Item: {{ $item->name }}</h4>
    <a href="{{ route('admin.inventory.show', $item->id) }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i> Back
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.inventory.update', $item->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="name" class="form-label">Item Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name', $item->name) }}" required>
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="sku" class="form-label">SKU <span class="text-danger">*</span></label>
                    <input type="text" name="sku" id="sku" class="form-control @error('sku') is-invalid @enderror"
                           value="{{ old('sku', $item->sku) }}" required>
                    @error('sku') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="category_id" class="form-label">Category <span class="text-danger">*</span></label>
                    <select name="category_id" id="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $item->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="unit" class="form-label">Unit <span class="text-danger">*</span></label>
                    <select name="unit" id="unit" class="form-select @error('unit') is-invalid @enderror" required>
                        <option value="">Select Unit</option>
                        @foreach(['Piece', 'Kg', 'Litre', 'Meter', 'Box', 'Pack', 'Dozen', 'Set'] as $unit)
                            <option value="{{ $unit }}" {{ old('unit', $item->unit) == $unit ? 'selected' : '' }}>
                                {{ $unit }}
                            </option>
                        @endforeach
                    </select>
                    @error('unit') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-12 mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" rows="3">{{ old('description', $item->description) }}</textarea>
                    @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-12"><hr><h6 class="text-muted">Pricing</h6></div>

                <div class="col-md-4 mb-3">
                    <label for="cost_price" class="form-label">Cost Price <span class="text-danger">*</span></label>
                    <input type="number" name="cost_price" id="cost_price" class="form-control @error('cost_price') is-invalid @enderror"
                           value="{{ old('cost_price', $item->cost_price) }}" step="0.01" min="0" required>
                    @error('cost_price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-4 mb-3">
                    <label for="selling_price" class="form-label">Selling Price <span class="text-danger">*</span></label>
                    <input type="number" name="selling_price" id="selling_price" class="form-control @error('selling_price') is-invalid @enderror"
                           value="{{ old('selling_price', $item->selling_price) }}" step="0.01" min="0" required>
                    @error('selling_price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-4 mb-3">
                    <label for="warehouse_id" class="form-label">Warehouse</label>
                    <select name="warehouse_id" id="warehouse_id" class="form-select @error('warehouse_id') is-invalid @enderror">
                        <option value="">Select Warehouse</option>
                        @foreach($warehouses as $warehouse)
                            <option value="{{ $warehouse->id }}" {{ old('warehouse_id', $item->warehouse_id) == $warehouse->id ? 'selected' : '' }}>
                                {{ $warehouse->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('warehouse_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-12"><hr><h6 class="text-muted">Stock Levels</h6></div>

                <div class="col-md-4 mb-3">
                    <label for="stock_quantity" class="form-label">Current Stock <span class="text-danger">*</span></label>
                    <input type="number" name="stock_quantity" id="stock_quantity" class="form-control @error('stock_quantity') is-invalid @enderror"
                           value="{{ old('stock_quantity', $item->stock_quantity) }}" min="0" required>
                    @error('stock_quantity') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-4 mb-3">
                    <label for="minimum_stock" class="form-label">Minimum Stock <span class="text-danger">*</span></label>
                    <input type="number" name="minimum_stock" id="minimum_stock" class="form-control @error('minimum_stock') is-invalid @enderror"
                           value="{{ old('minimum_stock', $item->minimum_stock) }}" min="0" required>
                    @error('minimum_stock') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-4 mb-3">
                    <label for="maximum_stock" class="form-label">Maximum Stock</label>
                    <input type="number" name="maximum_stock" id="maximum_stock" class="form-control @error('maximum_stock') is-invalid @enderror"
                           value="{{ old('maximum_stock', $item->maximum_stock) }}" min="0">
                    @error('maximum_stock') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-12"><hr><h6 class="text-muted">Identification</h6></div>

                <div class="col-md-6 mb-3">
                    <label for="barcode" class="form-label">Barcode / QR Code</label>
                    <input type="text" name="barcode" id="barcode" class="form-control @error('barcode') is-invalid @enderror"
                           value="{{ old('barcode', $item->barcode) }}">
                    @error('barcode') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                    <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                        <option value="active" {{ old('status', $item->status) == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status', $item->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i> Update Item
                </button>
                <a href="{{ route('admin.inventory.show', $item->id) }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection

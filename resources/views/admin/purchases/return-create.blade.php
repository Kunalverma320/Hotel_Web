@extends('admin.layouts.app')

@section('title', 'Create Purchase Return')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Create Purchase Return</h4>
    <a href="{{ route('admin.purchases.returns') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i> Back
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.purchases.return-store') }}" method="POST">
            @csrf

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Purchase Order <span class="text-danger">*</span></label>
                    <select name="purchase_order_id" class="form-select @error('purchase_order_id') is-invalid @enderror" required>
                        <option value="">Select Purchase Order</option>
                        @foreach($orders as $order)
                            <option value="{{ $order->id }}" {{ old('purchase_order_id') == $order->id ? 'selected' : '' }}>
                                {{ $order->po_number }} - {{ $order->supplier->name ?? '' }} (₹{{ number_format($order->total_amount, 2) }})
                            </option>
                        @endforeach
                    </select>
                    @error('purchase_order_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Item to Return <span class="text-danger">*</span></label>
                    <select name="inventory_item_id" class="form-select @error('inventory_item_id') is-invalid @enderror" required>
                        <option value="">Select Item</option>
                        @foreach($items as $item)
                            <option value="{{ $item->id }}" {{ old('inventory_item_id') == $item->id ? 'selected' : '' }}>
                                {{ $item->name }} ({{ $item->sku }}) - Stock: {{ $item->stock_quantity }}
                            </option>
                        @endforeach
                    </select>
                    @error('inventory_item_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Quantity <span class="text-danger">*</span></label>
                    <input type="number" name="quantity" class="form-control @error('quantity') is-invalid @enderror"
                           value="{{ old('quantity', 1) }}" min="1" required>
                    @error('quantity') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-8 mb-3">
                    <label class="form-label">Reason <span class="text-danger">*</span></label>
                    <input type="text" name="reason" class="form-control @error('reason') is-invalid @enderror"
                           value="{{ old('reason') }}" placeholder="e.g., Damaged, Defective, Wrong item..." required>
                    @error('reason') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-12 mb-3">
                    <label class="form-label">Additional Notes</label>
                    <textarea name="notes" class="form-control" rows="2">{{ old('notes') }}</textarea>
                </div>
            </div>

            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle me-1"></i>
                <strong>Note:</strong> Creating a return will reduce the stock quantity of the selected item.
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-warning">
                    <i class="fas fa-undo me-1"></i> Create Return
                </button>
                <a href="{{ route('admin.purchases.returns') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection

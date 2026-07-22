@extends('admin.layouts.app')

@section('title', 'Create Purchase Order')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Create Purchase Order</h4>
    <a href="{{ route('admin.purchases.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i> Back
    </a>
</div>

<form action="{{ route('admin.purchases.store') }}" method="POST" id="poForm">
    @csrf

    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header"><h6 class="mb-0">PO Details</h6></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">PO Number <span class="text-danger">*</span></label>
                            <input type="text" name="po_number" class="form-control" value="{{ $poNumber }}" required>
                            @error('po_number') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Supplier <span class="text-danger">*</span></label>
                            <select name="supplier_id" class="form-select" required>
                                <option value="">Select Supplier</option>
                                @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                        {{ $supplier->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('supplier_id') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Order Date <span class="text-danger">*</span></label>
                            <input type="date" name="order_date" class="form-control" value="{{ old('order_date', date('Y-m-d')) }}" required>
                            @error('order_date') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Expected Date</label>
                            <input type="date" name="expected_date" class="form-control" value="{{ old('expected_date') }}">
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">Notes</label>
                            <textarea name="notes" class="form-control" rows="2">{{ old('notes') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Line Items</h6>
                    <button type="button" class="btn btn-sm btn-outline-success" id="addRow">
                        <i class="fas fa-plus me-1"></i> Add Row
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table" id="itemsTable">
                            <thead class="table-light">
                                <tr>
                                    <th width="35%">Item <span class="text-danger">*</span></th>
                                    <th width="15%">Quantity <span class="text-danger">*</span></th>
                                    <th width="20%">Unit Price <span class="text-danger">*</span></th>
                                    <th width="20%">Total</th>
                                    <th width="10%">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="item-row">
                                    <td>
                                        <select name="items[0][item_id]" class="form-select item-select" required>
                                            <option value="">Select Item</option>
                                            @foreach($items as $item)
                                                <option value="{{ $item->id }}" data-price="{{ $item->cost_price }}">
                                                    {{ $item->name }} ({{ $item->sku }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" name="items[0][quantity]" class="form-control item-quantity" min="1" value="1" required>
                                    </td>
                                    <td>
                                        <input type="number" name="items[0][unit_price]" class="form-control item-price" step="0.01" min="0" value="0" required>
                                    </td>
                                    <td>
                                        <span class="item-total fw-bold">₹0.00</span>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-outline-danger btn-sm remove-row">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-end fw-bold">Subtotal:</td>
                                    <td class="fw-bold" id="subtotal">₹0.00</td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    @error('items') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card mb-4 sticky-top" style="top: 80px;">
                <div class="card-header"><h6 class="mb-0">Summary</h6></div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal:</span>
                        <strong id="summarySubtotal">₹0.00</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Tax:</span>
                        <strong>₹0.00</strong>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="fs-5">Total:</span>
                        <strong class="fs-5 text-primary" id="summaryTotal">₹0.00</strong>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-save me-1"></i> Create Purchase Order
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let rowIndex = 1;

    document.getElementById('addRow').addEventListener('click', function() {
        const tbody = document.querySelector('#itemsTable tbody');
        const newRow = document.querySelector('.item-row').cloneNode(true);
        newRow.querySelectorAll('select, input').forEach(el => {
            el.name = el.name.replace(/\[\d+\]/, '[' + rowIndex + ']');
            if (el.tagName === 'SELECT') el.selectedIndex = 0;
            else el.value = el.type === 'number' ? (el.classList.contains('item-quantity') ? '1' : '0') : '';
        });
        newRow.querySelector('.item-total').textContent = '₹0.00';
        tbody.appendChild(newRow);
        rowIndex++;
        bindEvents();
    });

    function bindEvents() {
        document.querySelectorAll('.remove-row').forEach(btn => {
            btn.onclick = function() {
                if (document.querySelectorAll('.item-row').length > 1) {
                    this.closest('tr').remove();
                    calculateTotal();
                }
            };
        });

        document.querySelectorAll('.item-select').forEach(select => {
            select.onchange = function() {
                const price = this.options[this.selectedIndex]?.dataset?.price || 0;
                const row = this.closest('tr');
                row.querySelector('.item-price').value = parseFloat(price).toFixed(2);
                calculateRowTotal(row);
            };
        });

        document.querySelectorAll('.item-quantity, .item-price').forEach(input => {
            input.oninput = function() {
                calculateRowTotal(this.closest('tr'));
            };
        });
    }

    function calculateRowTotal(row) {
        const qty = parseFloat(row.querySelector('.item-quantity').value) || 0;
        const price = parseFloat(row.querySelector('.item-price').value) || 0;
        const total = qty * price;
        row.querySelector('.item-total').textContent = '₹' + total.toFixed(2);
        calculateTotal();
    }

    function calculateTotal() {
        let total = 0;
        document.querySelectorAll('.item-total').forEach(el => {
            total += parseFloat(el.textContent.replace('₹', '')) || 0;
        });
        document.getElementById('subtotal').textContent = '₹' + total.toFixed(2);
        document.getElementById('summarySubtotal').textContent = '₹' + total.toFixed(2);
        document.getElementById('summaryTotal').textContent = '₹' + total.toFixed(2);
    }

    bindEvents();
});
</script>
@endpush

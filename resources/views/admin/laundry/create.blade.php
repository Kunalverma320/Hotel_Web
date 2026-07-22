@extends('admin.layouts.app')

@section('title', 'New Laundry Order')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-tshirt"></i> New Laundry Order</h4>
    <a href="{{ route('admin.laundry.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Back
    </a>
</div>

<form action="{{ route('admin.laundry.store') }}" method="POST" id="laundryForm">
    @csrf
    <div class="row g-4">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header"><h6 class="mb-0">Order Details</h6></div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Room <span class="text-danger">*</span></label>
                            <select name="room_id" class="form-select" required>
                                <option value="">-- Select Room --</option>
                                @foreach($rooms as $room)
                                    <option value="{{ $room->id }}">Room {{ $room->room_number }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Guest Name</label>
                            <input type="text" name="guest_name" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Priority</label>
                            <select name="priority" class="form-select">
                                <option value="normal">Normal (24h)</option>
                                <option value="express">Express (6h)</option>
                                <option value="urgent">Urgent (2h)</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Notes</label>
                            <textarea name="notes" class="form-control" rows="2" placeholder="Special instructions..."></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Laundry Items</h6>
                    <button type="button" class="btn btn-sm btn-primary" onclick="addLaundryItem()">
                        <i class="bi bi-plus"></i> Add Item
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table" id="itemsTable">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
                                    <th>Subtotal</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="itemsBody">
                                <tr id="noItemsRow">
                                    <td colspan="5" class="text-center text-muted py-3">Click "Add Item" to start</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card" style="position:sticky;top:20px;">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0">Order Summary</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal:</span>
                        <strong id="subtotal">$0.00</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Tax (10%):</span>
                        <span id="tax">$0.00</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between">
                        <span class="fs-5 fw-bold">Total:</span>
                        <span class="fs-5 fw-bold text-primary" id="total">$0.00</span>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-success btn-lg w-100" id="submitBtn">
                        <i class="bi bi-check-lg"></i> Place Order
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    let itemIndex = 0;
    const laundryItems = @json($laundryItems);

    function addLaundryItem() {
        document.getElementById('noItemsRow').style.display = 'none';
        const tbody = document.getElementById('itemsBody');
        const row = document.createElement('tr');
        row.id = 'itemRow' + itemIndex;

        let options = '<option value="">-- Select --</option>';
        laundryItems.forEach(item => {
            options += `<option value="${item.id}" data-price="${item.price}">${item.name} - $${parseFloat(item.price).toFixed(2)}</option>`;
        });

        row.innerHTML = `
            <td>
                <select name="items[${itemIndex}][laundry_item_id]" class="form-select form-select-sm item-select" onchange="updatePrice(${itemIndex})" required>
                    ${options}
                </select>
            </td>
            <td>
                <input type="number" name="items[${itemIndex}][quantity]" class="form-control form-control-sm" value="1" min="1" onchange="updatePrice(${itemIndex})" style="width:80px;" required>
            </td>
            <td><span class="item-price" id="price${itemIndex}">$0.00</span></td>
            <td><span class="item-subtotal" id="subtotal${itemIndex}">$0.00</span></td>
            <td>
                <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeItem(${itemIndex})">
                    <i class="bi bi-trash"></i>
                </button>
            </td>
        `;
        tbody.appendChild(row);
        itemIndex++;
    }

    function updatePrice(index) {
        const select = document.querySelector(`[name="items[${index}][laundry_item_id]"]`);
        const qty = document.querySelector(`[name="items[${index}][quantity]"]`).value;
        const option = select.options[select.selectedIndex];
        const price = parseFloat(option.dataset.price || 0);
        const subtotal = price * qty;

        document.getElementById('price' + index).textContent = '$' + price.toFixed(2);
        document.getElementById('subtotal' + index).textContent = '$' + subtotal.toFixed(2);
        calculateTotal();
    }

    function removeItem(index) {
        document.getElementById('itemRow' + index).remove();
        calculateTotal();
    }

    function calculateTotal() {
        let subtotal = 0;
        document.querySelectorAll('.item-subtotal').forEach(el => {
            subtotal += parseFloat(el.textContent.replace('$', '')) || 0;
        });
        const tax = subtotal * 0.10;
        document.getElementById('subtotal').textContent = '$' + subtotal.toFixed(2);
        document.getElementById('tax').textContent = '$' + tax.toFixed(2);
        document.getElementById('total').textContent = '$' + (subtotal + tax).toFixed(2);
    }
</script>
@endsection

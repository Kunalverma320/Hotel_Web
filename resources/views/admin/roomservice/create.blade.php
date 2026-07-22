@extends('admin.layouts.app')

@section('title', 'New Room Service Order')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-cone-striped"></i> New Room Service Order</h4>
    <a href="{{ route('admin.roomservice.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Back
    </a>
</div>

<form action="{{ route('admin.roomservice.store') }}" method="POST" id="roomServiceForm">
    @csrf
    <div class="row g-4">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header"><h6 class="mb-0">Order Details</h6></div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Room <span class="text-danger">*</span></label>
                            <select name="room_id" class="form-select" required>
                                <option value="">-- Select Room --</option>
                                @foreach($rooms as $room)
                                    <option value="{{ $room->id }}">Room {{ $room->room_number }} - {{ $room->type ?? '' }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Guest Name</label>
                            <input type="text" name="guest_name" class="form-control" placeholder="Auto-filled from room">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Special Notes</label>
                            <textarea name="notes" class="form-control" rows="2" placeholder="Allergies, special requests, etc."></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Menu Items</h6>
                </div>
                <div class="card-body">
                    @php
                        $grouped = $menuItems->groupBy('category.name');
                    @endphp
                    @foreach($grouped as $categoryName => $items)
                        <h6 class="text-muted mt-3">{{ $categoryName ?? 'Uncategorized' }}</h6>
                        <div class="row g-2 mb-3">
                            @foreach($items as $item)
                                <div class="col-md-4 col-sm-6">
                                    <div class="card cursor-pointer add-item-card" onclick="addMenuItem({{ $item->id }}, '{{ addslashes($item->name) }}', {{ $item->price }})">
                                        <div class="card-body p-2 d-flex justify-content-between align-items-center">
                                            <div>
                                                <strong style="font-size:0.9rem;">{{ $item->name }}</strong>
                                                <br><small class="text-primary">${{ number_format($item->price, 2) }}</small>
                                            </div>
                                            <i class="bi bi-plus-circle text-primary fs-4"></i>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card" style="position:sticky;top:20px;">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0"><i class="bi bi-cart3"></i> Order Summary</h6>
                </div>
                <div class="card-body">
                    <div id="selectedItems">
                        <p class="text-muted text-center" id="noItemsMsg">Click items to add to order</p>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal:</span>
                        <strong id="subtotal">$0.00</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Tax (10%):</span>
                        <span id="tax">$0.00</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="fs-5 fw-bold">Total:</span>
                        <span class="fs-5 fw-bold text-primary" id="total">$0.00</span>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-success btn-lg w-100" id="submitBtn" disabled>
                        <i class="bi bi-check-lg"></i> Place Order
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

<style>
    .add-item-card { cursor: pointer; transition: all 0.2s; }
    .add-item-card:hover { border-color: #0d6efd; transform: translateY(-1px); }
</style>

<script>
    let selectedItems = [];

    function addMenuItem(id, name, price) {
        let existing = selectedItems.find(i => i.id === id);
        if (existing) {
            existing.quantity++;
        } else {
            selectedItems.push({ id, name, price, quantity: 1 });
        }
        renderSelectedItems();
    }

    function removeItem(id) {
        selectedItems = selectedItems.filter(i => i.id !== id);
        renderSelectedItems();
    }

    function updateItemQty(id, qty) {
        let item = selectedItems.find(i => i.id === id);
        if (item) {
            item.quantity = Math.max(1, qty);
        }
        renderSelectedItems();
    }

    function renderSelectedItems() {
        const container = document.getElementById('selectedItems');
        const noItems = document.getElementById('noItemsMsg');
        const submitBtn = document.getElementById('submitBtn');

        if (selectedItems.length === 0) {
            container.innerHTML = '<p class="text-muted text-center" id="noItemsMsg">Click items to add to order</p>';
            submitBtn.disabled = true;
            document.getElementById('subtotal').textContent = '$0.00';
            document.getElementById('tax').textContent = '$0.00';
            document.getElementById('total').textContent = '$0.00';
            return;
        }

        submitBtn.disabled = false;
        let html = '';
        let subtotal = 0;

        selectedItems.forEach((item, index) => {
            const lineTotal = item.price * item.quantity;
            subtotal += lineTotal;
            html += `
                <input type="hidden" name="items[${index}][food_item_id]" value="${item.id}">
                <input type="hidden" name="items[${index}][quantity]" value="${item.quantity}">
                <div class="d-flex justify-content-between align-items-center mb-2 p-2 bg-light rounded">
                    <div class="flex-grow-1">
                        <small class="fw-bold">${item.name}</small>
                    </div>
                    <div class="d-flex align-items-center">
                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="updateItemQty(${item.id}, ${item.quantity - 1})">-</button>
                        <span class="mx-1">${item.quantity}</span>
                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="updateItemQty(${item.id}, ${item.quantity + 1})">+</button>
                        <button type="button" class="btn btn-sm btn-outline-danger ms-1" onclick="removeItem(${item.id})"><i class="bi bi-x"></i></button>
                    </div>
                </div>
            `;
        });

        container.innerHTML = html;
        const tax = subtotal * 0.10;
        document.getElementById('subtotal').textContent = '$' + subtotal.toFixed(2);
        document.getElementById('tax').textContent = '$' + tax.toFixed(2);
        document.getElementById('total').textContent = '$' + (subtotal + tax).toFixed(2);
    }
</script>
@endsection

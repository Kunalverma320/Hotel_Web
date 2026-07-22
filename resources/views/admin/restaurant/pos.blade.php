@extends('admin.layouts.app')

@section('title', 'Point of Sale')

@section('content')
<style>
    .pos-item {
        min-height: 100px;
        cursor: pointer;
        transition: all 0.2s;
        border: 2px solid transparent;
    }
    .pos-item:hover {
        border-color: #0d6efd;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    .pos-item:active {
        transform: scale(0.95);
    }
    .cart-item {
        min-height: 50px;
    }
    .pos-touch-btn {
        min-height: 60px;
        font-size: 1.1rem;
        font-weight: 600;
    }
    .cart-sidebar {
        position: sticky;
        top: 20px;
    }
</style>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0"><i class="bi bi-cart4"></i> POS Terminal</h4>
    <div>
        <span class="badge bg-success fs-6" id="cartCount">0 Items</span>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-8">
        <div class="card mb-3">
            <div class="card-body">
                <ul class="nav nav-pills mb-3" id="posCategories" role="tablist">
                    @foreach($categories as $index => $category)
                        <li class="nav-item" role="presentation">
                            <button class="nav-link {{ $index === 0 ? 'active' : '' }}" data-bs-toggle="pill" data-bs-target="#cat{{ $category->id }}" type="button">
                                {{ $category->name }}
                            </button>
                        </li>
                    @endforeach
                </ul>
                <div class="tab-content">
                    @foreach($categories as $index => $category)
                        <div class="tab-pane fade {{ $index === 0 ? 'show active' : '' }}" id="cat{{ $category->id }}">
                            <div class="row g-2">
                                @foreach($category->foodItems as $item)
                                    <div class="col-md-3 col-sm-4 col-6">
                                        <div class="card pos-item h-100" onclick="addToCart({{ $item->id }}, '{{ addslashes($item->name) }}', {{ $item->price }})">
                                            <div class="card-body text-center p-2">
                                                <div class="bg-light rounded d-flex align-items-center justify-content-center mx-auto mb-2" style="width:60px;height:60px;">
                                                    <i class="bi bi-egg-fried text-muted" style="font-size:24px;"></i>
                                                </div>
                                                <h6 class="card-title mb-1" style="font-size:0.85rem;">{{ $item->name }}</h6>
                                                <strong class="text-primary">${{ number_format($item->price, 2) }}</strong>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card cart-sidebar">
            <div class="card-header bg-primary text-white">
                <h6 class="mb-0"><i class="bi bi-cart3"></i> Current Order</h6>
            </div>
            <div class="card-body p-0">
                <div class="mb-3 p-3">
                    <label class="form-label">Table</label>
                    <select id="selectedTable" class="form-select">
                        <option value="">Walk-in / Takeaway</option>
                        @foreach($tables as $table)
                            <option value="{{ $table->id }}">Table {{ $table->table_number }} ({{ $table->capacity }} seats)</option>
                        @endforeach
                    </select>
                </div>
                <div id="cartItems" class="px-3" style="max-height:350px;overflow-y:auto;">
                    <p class="text-muted text-center py-4" id="emptyCart">Tap items to add</p>
                </div>
            </div>
            <div class="card-footer">
                <div class="d-flex justify-content-between mb-1">
                    <span>Subtotal:</span>
                    <strong id="subtotal">$0.00</strong>
                </div>
                <div class="d-flex justify-content-between mb-1">
                    <span>Tax (10%):</span>
                    <span id="tax">$0.00</span>
                </div>
                <hr class="my-1">
                <div class="d-flex justify-content-between mb-3">
                    <span class="fs-5 fw-bold">Total:</span>
                    <span class="fs-5 fw-bold text-primary" id="total">$0.00</span>
                </div>
                <div class="d-grid gap-2">
                    <button class="btn btn-success btn-lg pos-touch-btn" onclick="processPayment('cash')">
                        <i class="bi bi-cash"></i> Cash Payment
                    </button>
                    <button class="btn btn-primary btn-lg pos-touch-btn" onclick="processPayment('card')">
                        <i class="bi bi-credit-card"></i> Card Payment
                    </button>
                    <button class="btn btn-outline-danger pos-touch-btn" onclick="clearCart()">
                        <i class="bi bi-trash"></i> Clear Order
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<form id="posForm" action="{{ route('admin.restaurant.pos') }}" method="POST" style="display:none;">
    @csrf
    <input type="hidden" name="table_id" id="formTableId">
    <input type="hidden" name="payment_method" id="formPaymentMethod">
    <input type="hidden" name="items" id="formItems">
    <input type="hidden" name="total" id="formTotal">
</form>

<script>
    let cart = [];

    function addToCart(id, name, price) {
        let existing = cart.find(i => i.id === id);
        if (existing) {
            existing.quantity++;
        } else {
            cart.push({ id, name, price, quantity: 1 });
        }
        renderCart();
    }

    function removeFromCart(id) {
        cart = cart.filter(i => i.id !== id);
        renderCart();
    }

    function updateQuantity(id, qty) {
        let item = cart.find(i => i.id === id);
        if (item) {
            item.quantity = Math.max(1, qty);
        }
        renderCart();
    }

    function renderCart() {
        const container = document.getElementById('cartItems');
        const emptyMsg = document.getElementById('emptyCart');

        if (cart.length === 0) {
            container.innerHTML = '<p class="text-muted text-center py-4" id="emptyCart">Tap items to add</p>';
            document.getElementById('subtotal').textContent = '$0.00';
            document.getElementById('tax').textContent = '$0.00';
            document.getElementById('total').textContent = '$0.00';
            document.getElementById('cartCount').textContent = '0 Items';
            return;
        }

        let html = '';
        let subtotal = 0;

        cart.forEach(item => {
            const lineTotal = item.price * item.quantity;
            subtotal += lineTotal;
            html += `
                <div class="d-flex align-items-center justify-content-between py-2 border-bottom cart-item">
                    <div class="flex-grow-1">
                        <strong style="font-size:0.9rem;">${item.name}</strong>
                        <br><small class="text-muted">$${item.price.toFixed(2)} each</small>
                    </div>
                    <div class="d-flex align-items-center">
                        <button class="btn btn-sm btn-outline-secondary" onclick="updateQuantity(${item.id}, ${item.quantity - 1})">-</button>
                        <span class="mx-2 fw-bold">${item.quantity}</span>
                        <button class="btn btn-sm btn-outline-secondary" onclick="updateQuantity(${item.id}, ${item.quantity + 1})">+</button>
                        <button class="btn btn-sm btn-outline-danger ms-2" onclick="removeFromCart(${item.id})"><i class="bi bi-x"></i></button>
                    </div>
                </div>
            `;
        });

        container.innerHTML = html;

        const tax = subtotal * 0.10;
        const total = subtotal + tax;
        const itemCount = cart.reduce((sum, i) => sum + i.quantity, 0);

        document.getElementById('subtotal').textContent = '$' + subtotal.toFixed(2);
        document.getElementById('tax').textContent = '$' + tax.toFixed(2);
        document.getElementById('total').textContent = '$' + total.toFixed(2);
        document.getElementById('cartCount').textContent = itemCount + ' Items';
    }

    function clearCart() {
        cart = [];
        renderCart();
    }

    function processPayment(method) {
        if (cart.length === 0) {
            alert('Cart is empty!');
            return;
        }
        document.getElementById('formPaymentMethod').value = method;
        document.getElementById('formTableId').value = document.getElementById('selectedTable').value;
        document.getElementById('formItems').value = JSON.stringify(cart);
        document.getElementById('formTotal').value = document.getElementById('total').textContent.replace('$', '');
        document.getElementById('posForm').submit();
    }
</script>
@endsection

@extends('admin.layouts.app')
@section('title', 'New Booking')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0">New Booking</h4>
        <small class="text-muted">Create a new reservation</small>
    </div>
    <a href="{{ route('admin.bookings.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i> Back</a>
</div>

<form method="POST" action="{{ route('admin.bookings.store') }}" id="bookingForm">
    @csrf
    <div class="row g-4">
        <div class="col-lg-8">
            {{-- Step 1: Guest Selection --}}
            <div class="card mb-4" id="step1Card">
                <div class="card-header d-flex align-items-center">
                    <span class="badge bg-primary rounded-pill me-2">1</span>
                    <h6 class="mb-0 fw-semibold">Guest Information</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Select Existing Guest</label>
                        <select name="guest_id" class="form-select" id="guestSelect">
                            <option value="">-- Create New Guest --</option>
                            @foreach($guests as $guest)
                                <option value="{{ $guest->id }}" {{ old('guest_id') == $guest->id ? 'selected' : '' }}>{{ $guest->full_name }} ({{ $guest->email ?? $guest->phone ?? 'N/A' }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div id="newGuestFields">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">First Name <span class="text-danger">*</span></label>
                                <input type="text" name="guest_first_name" class="form-control @error('guest_first_name') is-invalid @enderror" value="{{ old('guest_first_name') }}">
                                @error('guest_first_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Last Name <span class="text-danger">*</span></label>
                                <input type="text" name="guest_last_name" class="form-control @error('guest_last_name') is-invalid @enderror" value="{{ old('guest_last_name') }}">
                                @error('guest_last_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Email</label>
                                <input type="email" name="guest_email" class="form-control @error('guest_email') is-invalid @enderror" value="{{ old('guest_email') }}">
                                @error('guest_email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Phone</label>
                                <input type="text" name="guest_phone" class="form-control @error('guest_phone') is-invalid @enderror" value="{{ old('guest_phone') }}">
                                @error('guest_phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Step 2: Room Selection --}}
            <div class="card mb-4" id="step2Card">
                <div class="card-header d-flex align-items-center">
                    <span class="badge bg-primary rounded-pill me-2">2</span>
                    <h6 class="mb-0 fw-semibold">Room Selection</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Hotel <span class="text-danger">*</span></label>
                            <select name="hotel_id" class="form-select @error('hotel_id') is-invalid @enderror" required id="hotelSelect">
                                <option value="">Select Hotel</option>
                                @foreach($hotels as $hotel)
                                    <option value="{{ $hotel->id }}" {{ old('hotel_id') == $hotel->id ? 'selected' : '' }}>{{ $hotel->name }}</option>
                                @endforeach
                            </select>
                            @error('hotel_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Room Type <span class="text-danger">*</span></label>
                            <select name="room_type_id" class="form-select @error('room_type_id') is-invalid @enderror" required id="roomTypeSelect">
                                <option value="">Select dates first</option>
                            </select>
                            @error('room_type_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Price/Night</label>
                            <div class="form-control-plaintext fw-bold text-primary" id="priceDisplay">$0.00</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Step 3: Booking Details --}}
            <div class="card mb-4" id="step3Card">
                <div class="card-header d-flex align-items-center">
                    <span class="badge bg-primary rounded-pill me-2">3</span>
                    <h6 class="mb-0 fw-semibold">Booking Details</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Check-in Date <span class="text-danger">*</span></label>
                            <input type="date" name="check_in_date" class="form-control @error('check_in_date') is-invalid @enderror" value="{{ old('check_in_date') }}" required id="checkInDate" min="{{ date('Y-m-d') }}">
                            @error('check_in_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Check-out Date <span class="text-danger">*</span></label>
                            <input type="date" name="check_out_date" class="form-control @error('check_out_date') is-invalid @enderror" value="{{ old('check_out_date') }}" required id="checkOutDate">
                            @error('check_out_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Adults <span class="text-danger">*</span></label>
                            <input type="number" name="adults" class="form-control" value="{{ old('adults', 1) }}" required min="1" max="10">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Children</label>
                            <input type="number" name="children" class="form-control" value="{{ old('children', 0) }}" min="0" max="10">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Nights</label>
                            <div class="form-control-plaintext fw-bold" id="nightsDisplay">0</div>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Special Requests</label>
                            <textarea name="special_requests" class="form-control" rows="2" placeholder="Any special requirements...">{{ old('special_requests') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Step 4: Payment --}}
            <div class="card mb-4" id="step4Card">
                <div class="card-header d-flex align-items-center">
                    <span class="badge bg-primary rounded-pill me-2">4</span>
                    <h6 class="mb-0 fw-semibold">Advance Payment</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Advance Amount</label>
                            <input type="number" step="0.01" name="advance_amount" class="form-control" value="{{ old('advance_amount', 0) }}" min="0" id="advanceAmount">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Payment Method</label>
                            <select name="payment_method" class="form-select">
                                <option value="cash">Cash</option>
                                <option value="card">Credit/Debit Card</option>
                                <option value="bank_transfer">Bank Transfer</option>
                                <option value="online">Online</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Source</label>
                            <select name="source" class="form-select">
                                <option value="admin">Front Desk</option>
                                <option value="website">Website</option>
                                <option value="phone">Phone</option>
                                <option value="walk_in">Walk-in</option>
                                <option value="travel_agent">Travel Agent</option>
                                <option value="corporate">Corporate</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Step 5: Summary --}}
        <div class="col-lg-4">
            <div class="card sticky-top" style="top:1rem;">
                <div class="card-header"><h6 class="mb-0 fw-semibold">Booking Summary</h6></div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Room Rate</span>
                        <span class="fw-semibold" id="summaryRate">$0.00 x <span id="summaryNights">0</span> nights</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Subtotal</span>
                        <span class="fw-semibold" id="summarySubtotal">$0.00</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Tax (18%)</span>
                        <span class="fw-semibold" id="summaryTax">$0.00</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="fw-bold">Total</span>
                        <span class="fw-bold fs-5 text-primary" id="summaryTotal">$0.00</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Advance Paid</span>
                        <span class="fw-semibold text-success" id="summaryPaid">$0.00</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="fw-bold">Balance Due</span>
                        <span class="fw-bold text-danger" id="summaryBalance">$0.00</span>
                    </div>
                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-primary btn-lg"><i class="bi bi-check-circle me-1"></i> Confirm Booking</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const guestSelect = document.getElementById('guestSelect');
    const newGuestFields = document.getElementById('newGuestFields');
    const checkInDate = document.getElementById('checkInDate');
    const checkOutDate = document.getElementById('checkOutDate');
    const hotelSelect = document.getElementById('hotelSelect');
    const roomTypeSelect = document.getElementById('roomTypeSelect');
    const advanceAmount = document.getElementById('advanceAmount');
    let roomTypesData = [];

    guestSelect.addEventListener('change', function() {
        newGuestFields.style.display = this.value ? 'none' : 'block';
    });
    if (guestSelect.value) newGuestFields.style.display = 'none';

    function updateSummary() {
        const selectedOption = roomTypeSelect.options[roomTypeSelect.selectedIndex];
        const price = parseFloat(selectedOption?.dataset?.price || 0);
        const ci = checkInDate.value ? new Date(checkInDate.value) : null;
        const co = checkOutDate.value ? new Date(checkOutDate.value) : null;
        let nights = 0;
        if (ci && co && co > ci) nights = Math.ceil((co - ci) / 86400000);

        const subtotal = price * nights;
        const tax = subtotal * 0.18;
        const total = subtotal + tax;
        const paid = parseFloat(advanceAmount.value || 0);

        document.getElementById('nightsDisplay').textContent = nights;
        document.getElementById('priceDisplay').textContent = '$' + price.toFixed(2);
        document.getElementById('summaryRate').innerHTML = '$' + price.toFixed(2) + ' x <span>' + nights + '</span> nights';
        document.getElementById('summaryNights').textContent = nights;
        document.getElementById('summarySubtotal').textContent = '$' + subtotal.toFixed(2);
        document.getElementById('summaryTax').textContent = '$' + tax.toFixed(2);
        document.getElementById('summaryTotal').textContent = '$' + total.toFixed(2);
        document.getElementById('summaryPaid').textContent = '$' + paid.toFixed(2);
        document.getElementById('summaryBalance').textContent = '$' + (total - paid).toFixed(2);
    }

    checkInDate.addEventListener('change', function() {
        checkOutDate.min = this.value;
        loadRoomTypes();
        updateSummary();
    });
    checkOutDate.addEventListener('change', function() { loadRoomTypes(); updateSummary(); });
    hotelSelect.addEventListener('change', function() { loadRoomTypes(); });
    roomTypeSelect.addEventListener('change', updateSummary);
    advanceAmount.addEventListener('input', updateSummary);

    function loadRoomTypes() {
        if (!hotelSelect.value || !checkInDate.value || !checkOutDate.value) return;
        fetch(`{{ route('admin.bookings.get-available-room-types') }}?hotel_id=${hotelSelect.value}&check_in=${checkInDate.value}&check_out=${checkOutDate.value}`)
            .then(r => r.json())
            .then(data => {
                roomTypeSelect.innerHTML = '<option value="">Select Room Type</option>';
                data.forEach(rt => {
                    const opt = document.createElement('option');
                    opt.value = rt.id;
                    opt.textContent = `${rt.name} (${rt.available_rooms} available) - $${parseFloat(rt.base_price).toFixed(2)}/night`;
                    opt.dataset.price = rt.base_price;
                    roomTypeSelect.appendChild(opt);
                });
                updateSummary();
            });
    }
    updateSummary();
});
</script>
@endpush

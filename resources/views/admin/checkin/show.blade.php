@extends('admin.layouts.app')
@section('title', 'Check-in - ' . $booking->booking_number)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0">Check-in Process</h4>
        <small class="text-muted">Booking {{ $booking->booking_number }} | {{ $booking->guest->full_name ?? 'N/A' }}</small>
    </div>
    <a href="{{ route('admin.checkin') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i> Back</a>
</div>

@if($booking->status === 'checked_in' && $booking->checkIns->where('status', 'active')->count())
    <div class="alert alert-success">
        <i class="bi bi-check-circle me-1"></i> Guest has already been checked in.
        @if($booking->checkIns->where('status', 'active')->first())
            <a href="{{ route('admin.checkin.print-registration', $booking->checkIns->where('status', 'active')->first()->id) }}" class="alert-link" target="_blank">Print Registration Card</a>
        @endif
    </div>
@endif

<div class="row g-4">
    <div class="col-lg-8">
        {{-- Guest Verification --}}
        <div class="card mb-4">
            <div class="card-header bg-info text-white">
                <h6 class="mb-0 fw-semibold"><i class="bi bi-person-check me-1"></i> Step 1: Guest Verification</h6>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="text-muted small">Full Name</label>
                        <div class="fw-semibold fs-5">{{ $booking->guest->full_name ?? 'N/A' }}</div>
                    </div>
                    <div class="col-md-3">
                        <label class="text-muted small">Email</label>
                        <div>{{ $booking->guest->email ?? 'N/A' }}</div>
                    </div>
                    <div class="col-md-3">
                        <label class="text-muted small">Phone</label>
                        <div>{{ $booking->guest->phone ?? 'N/A' }}</div>
                    </div>
                    <div class="col-md-3">
                        <label class="text-muted small">Nationality</label>
                        <div>{{ $booking->guest->nationality ?? 'N/A' }}</div>
                    </div>
                    <div class="col-md-3">
                        <label class="text-muted small">ID Type</label>
                        <div>{{ $booking->guest->id_type ?? 'N/A' }}</div>
                    </div>
                    <div class="col-md-3">
                        <label class="text-muted small">ID Number</label>
                        <div>{{ $booking->guest->id_number ?? 'N/A' }}</div>
                    </div>
                    <div class="col-md-3">
                        <label class="text-muted small">Loyalty Tier</label>
                        <div><span class="badge bg-primary">{{ ucfirst($booking->guest->loyalty_tier ?? 'Standard') }}</span></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Check-in Form --}}
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h6 class="mb-0 fw-semibold"><i class="bi bi-box-arrow-in-right me-1"></i> Step 2: Process Check-in</h6>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.checkin.process', $booking->id) }}" id="checkinForm">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Assign Room <span class="text-danger">*</span></label>
                            <select name="room_id" class="form-select @error('room_id') is-invalid @enderror" required>
                                <option value="">Select Room</option>
                                @foreach($availableRooms as $room)
                                    <option value="{{ $room->id }}" {{ old('room_id') == $room->id ? 'selected' : '' }}>
                                        Room {{ $room->number }} ({{ $room->floor->name ?? 'Floor ' . ($room->floor->number ?? '-') }})
                                    </option>
                                @endforeach
                            </select>
                            @error('room_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            @if($availableRooms->isEmpty())
                                <div class="form-text text-danger">No available rooms for this room type</div>
                            @endif
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Key Cards Issued</label>
                            <input type="number" name="key_cards_issued" class="form-control" value="{{ old('key_cards_issued', 2) }}" min="1" max="10">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Deposit Amount</label>
                            <input type="number" step="0.01" name="deposit_amount" class="form-control" value="{{ old('deposit_amount') }}" min="0">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Deposit Type</label>
                            <select name="deposit_type" class="form-select">
                                <option value="">Select</option>
                                <option value="cash">Cash</option>
                                <option value="card">Card</option>
                                <option value="online">Online</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">ID Verified</label>
                            <div class="form-check form-switch mt-2">
                                <input type="hidden" name="id_verified" value="0">
                                <input type="checkbox" name="id_verified" value="1" class="form-check-input" {{ old('id_verified') ? 'checked' : '' }}>
                                <label class="form-check-label ms-2">Guest ID has been verified</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Special Requests</label>
                            <div class="form-control-plaintext small">{{ $booking->special_requests ?? 'None' }}</div>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Notes</label>
                            <textarea name="notes" class="form-control" rows="2" placeholder="Additional notes for this check-in...">{{ old('notes') }}</textarea>
                        </div>
                    </div>
                    <div class="mt-4">
                        <button type="submit" class="btn btn-success btn-lg" {{ $availableRooms->isEmpty() ? 'disabled' : '' }}>
                            <i class="bi bi-check-circle me-1"></i> Complete Check-in
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-header"><h6 class="mb-0 fw-semibold">Booking Summary</h6></div>
            <div class="card-body">
                <div class="mb-2"><span class="text-muted">Booking:</span> <span class="fw-semibold">{{ $booking->booking_number }}</span></div>
                <div class="mb-2"><span class="text-muted">Hotel:</span> {{ $booking->hotel->name ?? 'N/A' }}</div>
                <div class="mb-2"><span class="text-muted">Room Type:</span> {{ $booking->roomType->name ?? 'N/A' }}</div>
                <div class="mb-2"><span class="text-muted">Check-in:</span> {{ $booking->check_in_date->format('M d, Y') }}</div>
                <div class="mb-2"><span class="text-muted">Check-out:</span> {{ $booking->check_out_date->format('M d, Y') }}</div>
                <div class="mb-2"><span class="text-muted">Nights:</span> {{ $booking->nights }}</div>
                <div class="mb-2"><span class="text-muted">Guests:</span> {{ $booking->adults }}A + {{ $booking->children ?? 0 }}C</div>
                <hr>
                <div class="mb-2"><span class="text-muted">Room Rate:</span> ${{ number_format($booking->room_rate, 2) }}/night</div>
                <div class="mb-2"><span class="text-muted">Total:</span> <span class="fw-bold">${{ number_format($booking->total_amount, 2) }}</span></div>
                <div class="mb-2"><span class="text-muted">Paid:</span> <span class="text-success">${{ number_format($booking->paid_amount, 2) }}</span></div>
                <div><span class="text-muted">Balance:</span> <span class="text-danger fw-bold">${{ number_format($booking->balance, 2) }}</span></div>
            </div>
        </div>
    </div>
</div>
@endsection

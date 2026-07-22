@extends('admin.layouts.app')
@section('title', 'Edit Booking - ' . $booking->booking_number)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0">Edit Booking</h4>
        <small class="text-muted">{{ $booking->booking_number }}</small>
    </div>
    <a href="{{ route('admin.bookings.show', $booking->id) }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i> Back</a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.bookings.update', $booking->id) }}">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Hotel <span class="text-danger">*</span></label>
                    <select name="hotel_id" class="form-select @error('hotel_id') is-invalid @enderror" required>
                        @foreach($hotels as $hotel)
                            <option value="{{ $hotel->id }}" {{ old('hotel_id', $booking->hotel_id) == $hotel->id ? 'selected' : '' }}>{{ $hotel->name }}</option>
                        @endforeach
                    </select>
                    @error('hotel_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Room Type <span class="text-danger">*</span></label>
                    <select name="room_type_id" class="form-select @error('room_type_id') is-invalid @enderror" required>
                        @foreach($roomTypes as $type)
                            <option value="{{ $type->id }}" {{ old('room_type_id', $booking->room_type_id) == $type->id ? 'selected' : '' }}>{{ $type->name }} (${{ $type->base_price }}/night)</option>
                        @endforeach
                    </select>
                    @error('room_type_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Guest</label>
                    <select name="guest_id" class="form-select">
                        <option value="">Select Guest</option>
                        @foreach($guests as $guest)
                            <option value="{{ $guest->id }}" {{ old('guest_id', $booking->guest_id) == $guest->id ? 'selected' : '' }}>{{ $guest->full_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Check-in Date <span class="text-danger">*</span></label>
                    <input type="date" name="check_in_date" class="form-control @error('check_in_date') is-invalid @enderror" value="{{ old('check_in_date', $booking->check_in_date->format('Y-m-d')) }}" required>
                    @error('check_in_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">Check-out Date <span class="text-danger">*</span></label>
                    <input type="date" name="check_out_date" class="form-control @error('check_out_date') is-invalid @enderror" value="{{ old('check_out_date', $booking->check_out_date->format('Y-m-d')) }}" required>
                    @error('check_out_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">Adults <span class="text-danger">*</span></label>
                    <input type="number" name="adults" class="form-control" value="{{ old('adults', $booking->adults) }}" required min="1">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Children</label>
                    <input type="number" name="children" class="form-control" value="{{ old('children', $booking->children) }}" min="0">
                </div>
                <div class="col-md-12">
                    <label class="form-label">Special Requests</label>
                    <textarea name="special_requests" class="form-control" rows="2">{{ old('special_requests', $booking->special_requests) }}</textarea>
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i> Update Booking</button>
                <a href="{{ route('admin.bookings.show', $booking->id) }}" class="btn btn-outline-secondary ms-2">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection

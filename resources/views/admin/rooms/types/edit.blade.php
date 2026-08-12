@extends('admin.layouts.app')
@section('title', 'Edit Room Type - ' . $roomType->name)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0">Edit Room Type</h4>
        <small class="text-muted">{{ $roomType->name }}</small>
    </div>
    <a href="{{ route('admin.room-types.show', $roomType->id) }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i> Back</a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.room-types.update', $roomType->id) }}">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Hotel <span class="text-danger">*</span></label>
                    <select name="hotel_id" id="hotel_id_edit_select" class="form-select @error('hotel_id') is-invalid @enderror" required>
                        <option value="">Select Hotel</option>
                        @foreach($hotels as $hotel)
                            <option value="{{ $hotel->id }}" {{ old('hotel_id', $roomType->hotel_id) == $hotel->id ? 'selected' : '' }}>{{ $hotel->name }}</option>
                        @endforeach
                    </select>
                    @error('hotel_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $roomType->name) }}" required>
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-12">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="3">{{ old('description', $roomType->description) }}</textarea>
                    @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Base Price <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" name="base_price" class="form-control @error('base_price') is-invalid @enderror" value="{{ old('base_price', $roomType->base_price) }}" required>
                    @error('base_price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Weekend Price</label>
                    <input type="number" step="0.01" name="weekend_price" class="form-control" value="{{ old('weekend_price', $roomType->weekend_price) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Peak Price</label>
                    <input type="number" step="0.01" name="peak_price" class="form-control" value="{{ old('peak_price', $roomType->peak_price) }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Max Adults <span class="text-danger">*</span></label>
                    <input type="number" name="max_adults" class="form-control" value="{{ old('max_adults', $roomType->max_adults) }}" required min="1">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Max Children</label>
                    <input type="number" name="max_children" class="form-control" value="{{ old('max_children', $roomType->max_children) }}" min="0">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Max Infants</label>
                    <input type="number" name="max_infants" class="form-control" value="{{ old('max_infants', $roomType->max_infants) }}" min="0">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Bed Count</label>
                    <input type="number" name="bed_count" class="form-control" value="{{ old('bed_count', $roomType->bed_count) }}" min="0">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Bed Type</label>
                    <select name="bed_type" class="form-select">
                        <option value="">Select</option>
                        @foreach(['Single','Double','Queen','King','Twin','Sofa Bed'] as $bt)
                            <option value="{{ $bt }}" {{ old('bed_type', $roomType->bed_type) == $bt ? 'selected' : '' }}>{{ $bt }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Room Size</label>
                    <div class="input-group">
                        <input type="number" step="0.01" name="room_size" class="form-control" value="{{ old('room_size', $roomType->room_size) }}">
                        <select name="room_size_unit" class="form-select" style="max-width:100px;">
                            <option value="sqft" {{ old('room_size_unit', $roomType->room_size_unit) == 'sqft' ? 'selected' : '' }}>sqft</option>
                            <option value="sqm" {{ old('room_size_unit', $roomType->room_size_unit) == 'sqm' ? 'selected' : '' }}>sqm</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Options</label>
                    <div class="d-flex gap-3 mt-2">
                        <div class="form-check">
                            <input type="checkbox" name="smoking_allowed" value="1" class="form-check-input" {{ old('smoking_allowed', $roomType->smoking_allowed) ? 'checked' : '' }}>
                            <label class="form-check-label">Smoking</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" name="pet_allowed" value="1" class="form-check-input" {{ old('pet_allowed', $roomType->pet_allowed) ? 'checked' : '' }}>
                            <label class="form-check-label">Pets</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" name="is_active" value="1" class="form-check-input" {{ old('is_active', $roomType->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label">Active</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i> Update Room Type</button>
                <a href="{{ route('admin.room-types.show', $roomType->id) }}" class="btn btn-outline-secondary ms-2">Cancel</a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const hotelSelect = document.getElementById('hotel_id_edit_select');
    const categorySelect = document.getElementById('room_category_id_edit_select');

    if (hotelSelect && categorySelect) {
        function filterCategories() {
            const selectedHotelId = hotelSelect.value;
            const categoryOptions = categorySelect.querySelectorAll('option');
            categoryOptions.forEach(option => {
                if (option.value === '') return;
                const hotelId = option.getAttribute('data-hotel');
                if (!selectedHotelId || hotelId === selectedHotelId) {
                    option.style.display = '';
                } else {
                    option.style.display = 'none';
                    if (option.selected) {
                        categorySelect.value = '';
                    }
                }
            });
        }

        hotelSelect.addEventListener('change', filterCategories);
        filterCategories();
    }
});
</script>
@endpush
@endsection

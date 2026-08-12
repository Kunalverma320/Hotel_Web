@extends('admin.layouts.app')
@section('title', 'Add Room')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0">Add Room</h4>
        <small class="text-muted">Create a new room</small>
    </div>
    <a href="{{ route('admin.rooms.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i> Back</a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.rooms.store') }}">
            @csrf
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Hotel <span class="text-danger">*</span></label>
                    <select name="hotel_id" id="hotel_id_select" class="form-select @error('hotel_id') is-invalid @enderror" required>
                        <option value="">Select Hotel</option>
                        @foreach($hotels as $hotel)
                            <option value="{{ $hotel->id }}" {{ old('hotel_id', $selectedHotelId ?? session('current_hotel_id')) == $hotel->id ? 'selected' : '' }}>{{ $hotel->name }}</option>
                        @endforeach
                    </select>
                    @error('hotel_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Room Type <span class="text-danger">*</span></label>
                    <select name="room_type_id" id="room_type_id_select" class="form-select @error('room_type_id') is-invalid @enderror" required>
                        <option value="">Select Room Type</option>
                        @foreach($roomTypes as $type)
                            <option value="{{ $type->id }}" data-hotel="{{ $type->hotel_id }}" {{ old('room_type_id') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                        @endforeach
                    </select>
                    @error('room_type_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Room Number <span class="text-danger">*</span></label>
                    <input type="text" name="number" class="form-control @error('number') is-invalid @enderror" value="{{ old('number') }}" required placeholder="e.g. 101">
                    @error('number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Building</label>
                    <select name="building_id" id="building_id_select" class="form-select @error('building_id') is-invalid @enderror">
                        <option value="">Select Building</option>
                        @foreach($buildings as $building)
                            <option value="{{ $building->id }}" data-hotel="{{ $building->hotel_id }}" {{ old('building_id') == $building->id ? 'selected' : '' }}>{{ $building->name }}</option>
                        @endforeach
                    </select>
                    @error('building_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Floor</label>
                    <select name="floor_id" id="floor_id_select" class="form-select @error('floor_id') is-invalid @enderror">
                        <option value="">Select Floor</option>
                        @foreach($floors as $floor)
                            <option value="{{ $floor->id }}" data-hotel="{{ $floor->hotel_id }}" {{ old('floor_id') == $floor->id ? 'selected' : '' }}>{{ $floor->name }} (Floor {{ $floor->floor_number ?? $floor->number }})</option>
                        @endforeach
                    </select>
                    @error('floor_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                        <option value="available" {{ old('status') == 'available' ? 'selected' : '' }}>Available</option>
                        <option value="occupied" {{ old('status') == 'occupied' ? 'selected' : '' }}>Occupied</option>
                        <option value="maintenance" {{ old('status') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                        <option value="out_of_order" {{ old('status') == 'out_of_order' ? 'selected' : '' }}>Out of Order</option>
                    </select>
                    @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Condition</label>
                    <input type="text" name="condition" class="form-control @error('condition') is-invalid @enderror" value="{{ old('condition') }}" placeholder="e.g. Good, New">
                    @error('condition') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Active</label>
                    <div class="form-check form-switch mt-2">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" class="form-check-input" {{ old('is_active', 1) ? 'checked' : '' }}>
                    </div>
                </div>
                <div class="col-md-12">
                    <label class="form-label">Notes</label>
                    <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="3">{{ old('notes') }}</textarea>
                    @error('notes') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i> Create Room</button>
                <a href="{{ route('admin.rooms.index') }}" class="btn btn-outline-secondary ms-2">Cancel</a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const hotelSelect = document.getElementById('hotel_id_select');
    const roomTypeSelect = document.getElementById('room_type_id_select');
    const buildingSelect = document.getElementById('building_id_select');
    const floorSelect = document.getElementById('floor_id_select');

    function fetchHotelOptions(hotelId) {
        if (!hotelId) return;
        fetch(`/admin/hotels/${hotelId}/options`)
            .then(res => res.json())
            .then(data => {
                if (floorSelect) {
                    const currentVal = floorSelect.value;
                    floorSelect.innerHTML = '<option value="">Select Floor</option>';
                    data.floors.forEach(f => {
                        const opt = document.createElement('option');
                        opt.value = f.id;
                        opt.textContent = `${f.name} (Floor ${f.number})`;
                        if (currentVal == f.id) opt.selected = true;
                        floorSelect.appendChild(opt);
                    });
                }
                if (buildingSelect) {
                    const currentVal = buildingSelect.value;
                    buildingSelect.innerHTML = '<option value="">Select Building</option>';
                    data.buildings.forEach(b => {
                        const opt = document.createElement('option');
                        opt.value = b.id;
                        opt.textContent = b.name;
                        if (currentVal == b.id) opt.selected = true;
                        buildingSelect.appendChild(opt);
                    });
                }
                if (roomTypeSelect) {
                    const currentVal = roomTypeSelect.value;
                    roomTypeSelect.innerHTML = '<option value="">Select Room Type</option>';
                    data.room_types.forEach(t => {
                        const opt = document.createElement('option');
                        opt.value = t.id;
                        opt.textContent = `${t.name} ($${t.price})`;
                        if (currentVal == t.id) opt.selected = true;
                        roomTypeSelect.appendChild(opt);
                    });
                }
            });
    }

    if (hotelSelect) {
        hotelSelect.addEventListener('change', function () {
            fetchHotelOptions(this.value);
        });
        if (hotelSelect.value) {
            fetchHotelOptions(hotelSelect.value);
        }
    }
});
</script>
@endpush
@endsection

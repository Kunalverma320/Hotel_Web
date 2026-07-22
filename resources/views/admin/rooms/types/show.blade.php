@extends('admin.layouts.app')
@section('title', $roomType->name)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0">{{ $roomType->name }}</h4>
        <small class="text-muted">{{ $roomType->hotel->name ?? 'N/A' }} | {{ $roomType->roomCategory->name ?? 'Uncategorized' }}</small>
    </div>
    <div>
        <a href="{{ route('admin.room-types.edit', $roomType->id) }}" class="btn btn-primary btn-sm"><i class="bi bi-pencil me-1"></i> Edit</a>
        <a href="{{ route('admin.room-types.index') }}" class="btn btn-outline-secondary btn-sm ms-1"><i class="bi bi-arrow-left me-1"></i> Back</a>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header"><h6 class="mb-0 fw-semibold">Details</h6></div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="text-muted small">Description</label>
                        <div>{{ $roomType->description ?? 'N/A' }}</div>
                    </div>
                    <div class="col-md-3">
                        <label class="text-muted small">Base Price</label>
                        <div class="fw-semibold fs-5">${{ number_format($roomType->base_price, 2) }}</div>
                    </div>
                    <div class="col-md-3">
                        <label class="text-muted small">Weekend Price</label>
                        <div class="fw-semibold">${{ number_format($roomType->weekend_price ?? 0, 2) }}</div>
                    </div>
                    <div class="col-md-3">
                        <label class="text-muted small">Peak Price</label>
                        <div class="fw-semibold">${{ number_format($roomType->peak_price ?? 0, 2) }}</div>
                    </div>
                    <div class="col-md-3">
                        <label class="text-muted small">Max Adults</label>
                        <div>{{ $roomType->max_adults }}</div>
                    </div>
                    <div class="col-md-3">
                        <label class="text-muted small">Max Children</label>
                        <div>{{ $roomType->max_children ?? 0 }}</div>
                    </div>
                    <div class="col-md-3">
                        <label class="text-muted small">Bed</label>
                        <div>{{ $roomType->bed_type ?? '-' }} ({{ $roomType->bed_count ?? 0 }})</div>
                    </div>
                    <div class="col-md-3">
                        <label class="text-muted small">Room Size</label>
                        <div>{{ $roomType->room_size ? $roomType->room_size . ' ' . $roomType->room_size_unit : '-' }}</div>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small">Features</label>
                        <div>
                            @if($roomType->smoking_allowed)<span class="badge bg-secondary me-1">Smoking Allowed</span>@endif
                            @if($roomType->pet_allowed)<span class="badge bg-secondary me-1">Pets Allowed</span>@endif
                            <span class="badge bg-{{ $roomType->is_active ? 'success' : 'danger' }}">{{ $roomType->is_active ? 'Active' : 'Inactive' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header"><h6 class="mb-0 fw-semibold">Rooms ({{ $roomType->rooms->count() }})</h6></div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead><tr><th>#</th><th>Number</th><th>Floor</th><th>Status</th></tr></thead>
                        <tbody>
                            @forelse($roomType->rooms as $room)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td><a href="{{ route('admin.rooms.show', $room->id) }}">{{ $room->number }}</a></td>
                                    <td>{{ $room->floor->number ?? '-' }}</td>
                                    <td><span class="badge bg-{{ $room->status == 'available' ? 'success' : ($room->status == 'occupied' ? 'danger' : 'dark') }}">{{ ucfirst($room->status) }}</span></td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center py-3 text-muted">No rooms of this type</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-body text-center">
                <div class="fs-1 fw-bold text-primary">${{ number_format($roomType->base_price, 2) }}</div>
                <div class="text-muted mb-2">per night</div>
                <div class="badge bg-{{ $roomType->is_active ? 'success' : 'secondary' }} mb-3">{{ $roomType->is_active ? 'Active' : 'Inactive' }}</div>
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.room-types.edit', $roomType->id) }}" class="btn btn-outline-primary"><i class="bi bi-pencil me-1"></i> Edit</a>
                    <form method="POST" action="{{ route('admin.room-types.destroy', $roomType->id) }}" onsubmit="return confirm('Delete this room type?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-outline-danger w-100"><i class="bi bi-trash me-1"></i> Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

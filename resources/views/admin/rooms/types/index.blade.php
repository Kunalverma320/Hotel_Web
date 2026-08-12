@extends('admin.layouts.app')
@section('title', 'Room Types')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0">Room Types</h4>
        <small class="text-muted">Manage room types and pricing</small>
    </div>
    <a href="{{ route('admin.room-types.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i> Add Room Type</a>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.room-types.index') }}">
            <div class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label">Hotel</label>
                    <select name="hotel_id" id="hotel_filter" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">All Hotels</option>
                        @foreach($hotels as $hotel)
                            <option value="{{ $hotel->id }}" {{ (string)$selectedHotelId === (string)$hotel->id ? 'selected' : '' }}>{{ $hotel->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Search</label>
                    <input type="text" name="search" class="form-control form-control-sm" placeholder="Search room type name..." value="{{ request('search') }}">
                </div>
                <div class="col-md-4 d-flex gap-1">
                    <button type="submit" class="btn btn-sm btn-primary"><i class="bi bi-search"></i> Search</button>
                    <a href="{{ route('admin.room-types.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-counterclockwise"></i> Reset</a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Hotel</th>
                    <th>Base Price</th>
                    <th>Max Adults</th>
                    <th>Bed</th>
                    <th>Rooms</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($roomTypes as $type)
                    <tr>
                        <td class="fw-semibold">{{ $type->name }}</td>
                        <td>
                            @if($type->hotel)
                                <span class="badge bg-light text-dark border"><i class="bi bi-building me-1"></i>{{ $type->hotel->name }}</span>
                            @else
                                <span class="text-muted">N/A</span>
                            @endif
                        </td>
                        <td>${{ number_format($type->base_price ?? $type->base_rate ?? 0, 2) }}</td>
                        <td>{{ $type->max_adults }}</td>
                        <td>{{ $type->bed_type ?? '-' }} ({{ $type->bed_count ?? 0 }})</td>
                        <td><span class="badge bg-info">{{ $type->rooms_count ?? $type->rooms->count() }}</span></td>
                        <td><span class="badge bg-{{ $type->status ? 'success' : 'secondary' }}">{{ $type->status ? 'Active' : 'Inactive' }}</span></td>
                        <td class="text-end">
                            <a href="{{ route('admin.room-types.show', $type->id) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('admin.room-types.edit', $type->id) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></a>
                            <form method="POST" action="{{ route('admin.room-types.destroy', $type->id) }}" class="d-inline" onsubmit="return confirm('Are you sure?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="9" class="text-center py-4 text-muted">No room types found</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $roomTypes->withQueryString()->links() }}</div>
@endsection

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
                <div class="col-md-3">
                    <label class="form-label">Hotel</label>
                    <select name="hotel_id" class="form-select form-select-sm">
                        <option value="">All Hotels</option>
                        @foreach($hotels as $hotel)
                            <option value="{{ $hotel->id }}" {{ request('hotel_id') == $hotel->id ? 'selected' : '' }}>{{ $hotel->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Category</label>
                    <select name="category_id" class="form-select form-select-sm">
                        <option value="">All Categories</option>
                        @foreach($roomCategories as $cat)
                            <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Search</label>
                    <input type="text" name="search" class="form-control form-control-sm" placeholder="Search..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3 d-flex gap-1">
                    <button type="submit" class="btn btn-sm btn-primary"><i class="bi bi-search"></i></button>
                    <a href="{{ route('admin.room-types.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-counterclockwise"></i></a>
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
                    <th>Category</th>
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
                        <td>{{ $type->hotel->name ?? 'N/A' }}</td>
                        <td>{{ $type->roomCategory->name ?? '-' }}</td>
                        <td>{{ number_format($type->base_price, 2) }}</td>
                        <td>{{ $type->max_adults }}</td>
                        <td>{{ $type->bed_type ?? '-' }} ({{ $type->bed_count ?? 0 }})</td>
                        <td><span class="badge bg-info">{{ $type->rooms_count ?? $type->rooms->count() }}</span></td>
                        <td><span class="badge bg-{{ $type->is_active ? 'success' : 'secondary' }}">{{ $type->is_active ? 'Active' : 'Inactive' }}</span></td>
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

@extends('admin.layouts.app')
@section('title', 'Room Categories')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0">Room Categories</h4>
        <small class="text-muted">Manage room categories</small>
    </div>
    <a href="{{ route('admin.room-categories.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i> Add Category</a>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.room-categories.index') }}">
            <div class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label">Hotel</label>
                    <select name="hotel_id" class="form-select form-select-sm">
                        <option value="">All Hotels</option>
                        @foreach($hotels as $hotel)
                            <option value="{{ $hotel->id }}" {{ request('hotel_id') == $hotel->id ? 'selected' : '' }}>{{ $hotel->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Search</label>
                    <input type="text" name="search" class="form-control form-control-sm" placeholder="Search..." value="{{ request('search') }}">
                </div>
                <div class="col-md-4 d-flex gap-1">
                    <button type="submit" class="btn btn-sm btn-primary"><i class="bi bi-search"></i></button>
                    <a href="{{ route('admin.room-categories.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-counterclockwise"></i></a>
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
                    <th>Description</th>
                    <th>Sort Order</th>
                    <th>Room Types</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($roomCategories as $cat)
                    <tr>
                        <td class="fw-semibold">{{ $cat->name }}</td>
                        <td>{{ $cat->hotel->name ?? 'N/A' }}</td>
                        <td>{{ Str::limit($cat->description, 60) }}</td>
                        <td>{{ $cat->sort_order }}</td>
                        <td><span class="badge bg-info">{{ $cat->roomTypes->count() }}</span></td>
                        <td><span class="badge bg-{{ $cat->is_active ? 'success' : 'secondary' }}">{{ $cat->is_active ? 'Active' : 'Inactive' }}</span></td>
                        <td class="text-end">
                            <a href="{{ route('admin.room-categories.edit', $cat->id) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></a>
                            <form method="POST" action="{{ route('admin.room-categories.destroy', $cat->id) }}" class="d-inline" onsubmit="return confirm('Are you sure?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center py-4 text-muted">No categories found</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $roomCategories->withQueryString()->links() }}</div>
@endsection

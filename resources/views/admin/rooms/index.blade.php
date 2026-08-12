@extends('admin.layouts.app')
@section('title', 'Rooms')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0">Rooms</h4>
        <small class="text-muted">Manage hotel rooms</small>
    </div>
    <a href="{{ route('admin.rooms.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Add Room
    </a>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.rooms.index') }}">
            <div class="row g-3 align-items-end">
                <div class="col-md-2">
                    <label class="form-label">Hotel</label>
                    <select name="hotel_id" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">All Hotels</option>
                        @foreach($hotels as $hotel)
                            <option value="{{ $hotel->id }}" {{ (string)$selectedHotelId === (string)$hotel->id ? 'selected' : '' }}>{{ $hotel->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Room Type</label>
                    <select name="room_type_id" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">All Types</option>
                        @foreach($roomTypes as $type)
                            <option value="{{ $type->id }}" data-hotel="{{ $type->hotel_id }}" {{ request('room_type_id') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">All Status</option>
                        @foreach(['available','occupied','maintenance','out_of_order','reserved','dirty','clean','inspected'] as $s)
                            <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ ucfirst(str_replace('_',' ',$s)) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Floor</label>
                    <select name="floor_id" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">All Floors</option>
                        @foreach($floors as $floor)
                            <option value="{{ $floor->id }}" data-hotel="{{ $floor->hotel_id }}" {{ request('floor_id') == $floor->id ? 'selected' : '' }}>{{ $floor->name }} (Floor {{ $floor->floor_number ?? $floor->number }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Search</label>
                    <input type="text" name="search" class="form-control form-control-sm" placeholder="Room number..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2 d-flex gap-1">
                    <button type="submit" class="btn btn-sm btn-primary flex-fill"><i class="bi bi-search"></i> Search</button>
                    <a href="{{ route('admin.rooms.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-counterclockwise"></i></a>
                    <a href="{{ route('admin.rooms.availability') }}" class="btn btn-sm btn-outline-info" title="Availability"><i class="bi bi-calendar3"></i></a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="d-flex justify-content-between align-items-center mb-3">
    <span class="text-muted">{{ $rooms->total() }} room(s) found</span>
    <div class="btn-group btn-group-sm">
        <a href="{{ request()->fullUrlWithQuery(['view' => 'grid']) }}" class="btn btn-outline-secondary {{ $view == 'grid' ? 'active' : '' }}"><i class="bi bi-grid-3x3-gap"></i></a>
        <a href="{{ request()->fullUrlWithQuery(['view' => 'list']) }}" class="btn btn-outline-secondary {{ $view == 'list' ? 'active' : '' }}"><i class="bi bi-list"></i></a>
    </div>
</div>

@if($view == 'grid')
    <div class="row g-3">
        @forelse($rooms as $room)
            <div class="col-xl-3 col-lg-4 col-md-6">
                <div class="card h-100 room-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <h6 class="fw-bold mb-0">{{ $room->number }}</h6>
                                <small class="text-muted">{{ $room->roomType->name ?? 'N/A' }}</small>
                            </div>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-light" data-bs-toggle="dropdown"><i class="bi bi-three-dots-vertical"></i></button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="{{ route('admin.rooms.show', $room->id) }}"><i class="bi bi-eye me-2"></i>View</a></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.rooms.edit', $room->id) }}"><i class="bi bi-pencil me-2"></i>Edit</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <button class="dropdown-item text-danger" onclick="confirmDelete('{{ route('admin.rooms.destroy', $room->id) }}', 'this room')">
                                            <i class="bi bi-trash me-2"></i>Delete
                                        </button>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="mb-2">
                            <span class="badge bg-{{ $room->status == 'available' ? 'success' : ($room->status == 'occupied' ? 'danger' : ($room->status == 'maintenance' ? 'warning' : ($room->status == 'dirty' ? 'secondary' : ($room->status == 'clean' ? 'info' : 'dark')))) }}">
                                {{ ucfirst(str_replace('_', ' ', $room->status)) }}
                            </span>
                        </div>
                        <div class="small text-muted">
                            <div><i class="bi bi-building me-1"></i>{{ $room->building->name ?? '-' }} | Floor {{ $room->floor->number ?? '-' }}</div>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-0 pt-0">
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle w-100" data-bs-toggle="dropdown">Quick Status</button>
                            <ul class="dropdown-menu">
                                @foreach(['available','dirty','clean','inspected','maintenance','out_of_order'] as $status)
                                    <li>
                                        <form method="POST" action="{{ route('admin.rooms.update-status', [$room->id, $status]) }}">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="dropdown-item">{{ ucfirst(str_replace('_',' ',$status)) }}</button>
                                        </form>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-door-open" style="font-size:3rem;"></i>
                    <p class="mt-2">No rooms found</p>
                </div>
            </div>
        @endforelse
    </div>
@else
    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Number</th>
                        <th>Type</th>
                        <th>Floor</th>
                        <th>Status</th>
                        <th>Hotel</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rooms as $room)
                        <tr>
                            <td class="fw-semibold">{{ $room->number }}</td>
                            <td>{{ $room->roomType->name ?? 'N/A' }}</td>
                            <td>{{ $room->floor->number ?? '-' }}</td>
                            <td>
                                <span class="badge bg-{{ $room->status == 'available' ? 'success' : ($room->status == 'occupied' ? 'danger' : ($room->status == 'maintenance' ? 'warning' : 'dark')) }}">
                                    {{ ucfirst(str_replace('_', ' ', $room->status)) }}
                                </span>
                            </td>
                            <td>{{ $room->hotel->name ?? 'N/A' }}</td>
                            <td class="text-end">
                                <a href="{{ route('admin.rooms.show', $room->id) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a>
                                <a href="{{ route('admin.rooms.edit', $room->id) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></a>
                                <button class="btn btn-sm btn-outline-danger" onclick="confirmDelete('{{ route('admin.rooms.destroy', $room->id) }}', 'this room')"><i class="bi bi-trash"></i></button>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center py-4 text-muted">No rooms found</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endif

<div class="mt-3">{{ $rooms->withQueryString()->links() }}</div>
@endsection

@push('scripts')
<script>
function confirmDelete(url, name) {
    const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('confirmDeleteModal'));
    document.getElementById('deleteModalText').textContent = 'Are you sure you want to delete ' + name + '?';
    document.getElementById('deleteForm').action = url;
    modal.show();
}
</script>
@endpush

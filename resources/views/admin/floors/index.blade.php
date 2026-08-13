@extends('admin.layouts.app')
@section('title', 'Floors Management')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0"><i class="bi bi-layers-half text-primary me-2"></i>Floors Management</h4>
        <small class="text-muted">Manage hotel floors, levels, and room distributions</small>
    </div>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addFloorModal">
        <i class="bi bi-plus-lg me-1"></i> Add Floor
    </button>
</div>

{{-- Stat Cards --}}
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body d-flex align-items-center">
                <div class="rounded-3 bg-primary bg-opacity-10 p-3 me-3 text-primary">
                    <i class="bi bi-layers fs-3"></i>
                </div>
                <div>
                    <h6 class="text-muted mb-0 small text-uppercase fw-semibold">Total Floors</h6>
                    <h3 class="fw-bold mb-0 text-dark">{{ $totalFloors }}</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body d-flex align-items-center">
                <div class="rounded-3 bg-success bg-opacity-10 p-3 me-3 text-success">
                    <i class="bi bi-check-circle fs-3"></i>
                </div>
                <div>
                    <h6 class="text-muted mb-0 small text-uppercase fw-semibold">Active Floors</h6>
                    <h3 class="fw-bold mb-0 text-dark">{{ $activeFloors }}</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body d-flex align-items-center">
                <div class="rounded-3 bg-info bg-opacity-10 p-3 me-3 text-info">
                    <i class="bi bi-door-open fs-3"></i>
                </div>
                <div>
                    <h6 class="text-muted mb-0 small text-uppercase fw-semibold">Assigned Rooms</h6>
                    <h3 class="fw-bold mb-0 text-dark">{{ $floors->sum('rooms_count') }}</h3>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Filters --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.floors.index') }}">
            <div class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label small fw-semibold text-muted">Hotel</label>
                    <select name="hotel_id" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="all">All Hotels</option>
                        @foreach($hotels as $hotel)
                            <option value="{{ $hotel->id }}" {{ (string)$selectedHotelId === (string)$hotel->id ? 'selected' : '' }}>
                                {{ $hotel->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-semibold text-muted">Search Floor</label>
                    <input type="text" name="search" class="form-control form-control-sm" placeholder="Floor name or number..." value="{{ request('search') }}">
                </div>
                <div class="col-md-4 d-flex gap-2">
                    <button type="submit" class="btn btn-sm btn-primary flex-grow-1"><i class="bi bi-search me-1"></i> Filter</button>
                    <a href="{{ route('admin.floors.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-counterclockwise"></i> Reset</a>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Table --}}
<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
                <tr>
                    <th class="ps-3">Floor Number</th>
                    <th>Name</th>
                    <th>Hotel</th>
                    <th>Building</th>
                    <th>Rooms Count</th>
                    <th>Status</th>
                    <th class="text-end pe-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($floors as $floor)
                    <tr>
                        <td class="ps-3">
                            <span class="badge bg-primary-light text-primary fw-bold fs-6">
                                Floor {{ $floor->floor_number }}
                            </span>
                        </td>
                        <td class="fw-semibold text-dark">{{ $floor->name }}</td>
                        <td>{{ $floor->hotel->name ?? 'N/A' }}</td>
                        <td>{{ $floor->building->name ?? '-' }}</td>
                        <td>
                            <span class="badge bg-light text-dark border">
                                <i class="bi bi-door-closed me-1"></i>{{ $floor->rooms_count }} Rooms
                            </span>
                        </td>
                        <td>
                            <form action="{{ route('admin.floors.toggle-status', $floor->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm p-0 border-0">
                                    @if($floor->status)
                                        <span class="badge bg-success-subtle text-success"><i class="bi bi-check-circle me-1"></i>Active</span>
                                    @else
                                        <span class="badge bg-danger-subtle text-danger"><i class="bi bi-x-circle me-1"></i>Inactive</span>
                                    @endif
                                </button>
                            </form>
                        </td>
                        <td class="text-end pe-3">
                            <button type="button" class="btn btn-sm btn-outline-primary me-1 edit-floor-btn"
                                data-id="{{ $floor->id }}"
                                data-name="{{ $floor->name }}"
                                data-floor_number="{{ $floor->floor_number }}"
                                data-hotel_id="{{ $floor->hotel_id }}"
                                data-building_id="{{ $floor->building_id }}"
                                data-description="{{ $floor->description }}"
                                data-status="{{ $floor->status ? 1 : 0 }}"
                                data-bs-toggle="modal" data-bs-target="#editFloorModal">
                                <i class="bi bi-pencil"></i> Edit
                            </button>

                            <form action="{{ route('admin.floors.destroy', $floor->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this floor?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" {{ $floor->rooms_count > 0 ? 'disabled title="Cannot delete floor with assigned rooms"' : '' }}>
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-5">
                            <i class="bi bi-layers d-block mb-2" style="font-size: 2.5rem; opacity: 0.3;"></i>
                            No floors found. Click "Add Floor" to create your first floor.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($floors->hasPages())
        <div class="card-footer bg-white border-0 py-3">
            {{ $floors->withQueryString()->links() }}
        </div>
    @endif
</div>

{{-- Add Floor Modal --}}
<div class="modal fade" id="addFloorModal" tabindex="-1" aria-labelledby="addFloorModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form action="{{ route('admin.floors.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="addFloorModalLabel"><i class="bi bi-plus-circle text-primary me-2"></i>Add New Floor</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Hotel <span class="text-danger">*</span></label>
                        <select name="hotel_id" class="form-select" required>
                            @foreach($hotels as $hotel)
                                <option value="{{ $hotel->id }}" {{ (string)$selectedHotelId === (string)$hotel->id ? 'selected' : '' }}>
                                    {{ $hotel->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Floor Number <span class="text-danger">*</span></label>
                            <input type="number" name="floor_number" class="form-control" placeholder="e.g. 1, 2, 3" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Floor Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" placeholder="e.g. 1st Floor, Executive Floor" required>
                        </div>
                    </div>

                    @if(count($buildings) > 0)
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Building (Optional)</label>
                        <select name="building_id" class="form-select">
                            <option value="">-- Select Building --</option>
                            @foreach($buildings as $building)
                                <option value="{{ $building->id }}">{{ $building->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Description</label>
                        <textarea name="description" class="form-control" rows="2" placeholder="Optional notes or details..."></textarea>
                    </div>

                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="status" id="add_status" value="1" checked>
                        <label class="form-check-label fw-semibold" for="add_status">Active Status</label>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i> Save Floor</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Edit Floor Modal --}}
<div class="modal fade" id="editFloorModal" tabindex="-1" aria-labelledby="editFloorModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form id="editFloorForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="editFloorModalLabel"><i class="bi bi-pencil-square text-primary me-2"></i>Edit Floor</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Hotel <span class="text-danger">*</span></label>
                        <select name="hotel_id" id="edit_hotel_id" class="form-select" required>
                            @foreach($hotels as $hotel)
                                <option value="{{ $hotel->id }}">{{ $hotel->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Floor Number <span class="text-danger">*</span></label>
                            <input type="number" name="floor_number" id="edit_floor_number" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Floor Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="edit_name" class="form-control" required>
                        </div>
                    </div>

                    @if(count($buildings) > 0)
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Building (Optional)</label>
                        <select name="building_id" id="edit_building_id" class="form-select">
                            <option value="">-- Select Building --</option>
                            @foreach($buildings as $building)
                                <option value="{{ $building->id }}">{{ $building->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Description</label>
                        <textarea name="description" id="edit_description" class="form-control" rows="2"></textarea>
                    </div>

                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="status" id="edit_status" value="1">
                        <label class="form-check-label fw-semibold" for="edit_status">Active Status</label>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i> Update Floor</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const editBtns = document.querySelectorAll('.edit-floor-btn');
    editBtns.forEach(btn => {
        btn.addEventListener('click', function () {
            const id = this.dataset.id;
            const name = this.dataset.name;
            const floorNumber = this.dataset.floor_number;
            const hotelId = this.dataset.hotel_id;
            const buildingId = this.dataset.building_id;
            const description = this.dataset.description;
            const status = this.dataset.status;

            const form = document.getElementById('editFloorForm');
            form.action = `/admin/floors/${id}`;

            document.getElementById('edit_name').value = name;
            document.getElementById('edit_floor_number').value = floorNumber;
            document.getElementById('edit_hotel_id').value = hotelId;
            if (document.getElementById('edit_building_id')) {
                document.getElementById('edit_building_id').value = buildingId || '';
            }
            document.getElementById('edit_description').value = description || '';
            document.getElementById('edit_status').checked = (status == 1);
        });
    });
});
</script>
@endpush
@endsection

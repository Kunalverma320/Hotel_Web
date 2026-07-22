@extends('admin.layouts.app')
@section('title', 'Room #' . $room->number)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0">Room #{{ $room->number }}</h4>
        <small class="text-muted">{{ $room->roomType->name ?? 'N/A' }} | {{ $room->hotel->name ?? 'N/A' }}</small>
    </div>
    <div>
        <a href="{{ route('admin.rooms.edit', $room->id) }}" class="btn btn-primary btn-sm"><i class="bi bi-pencil me-1"></i> Edit</a>
        <a href="{{ route('admin.rooms.index') }}" class="btn btn-outline-secondary btn-sm ms-1"><i class="bi bi-arrow-left me-1"></i> Back</a>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header"><h6 class="mb-0 fw-semibold">Room Information</h6></div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="text-muted small">Room Number</label>
                        <div class="fw-semibold">{{ $room->number }}</div>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small">Room Type</label>
                        <div class="fw-semibold">{{ $room->roomType->name ?? 'N/A' }}</div>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small">Building</label>
                        <div class="fw-semibold">{{ $room->building->name ?? '-' }}</div>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small">Floor</label>
                        <div class="fw-semibold">{{ $room->floor->number ?? '-' }}</div>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small">Condition</label>
                        <div class="fw-semibold">{{ $room->condition ?? 'N/A' }}</div>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small">Active</label>
                        <div><span class="badge bg-{{ $room->is_active ? 'success' : 'secondary' }}">{{ $room->is_active ? 'Yes' : 'No' }}</span></div>
                    </div>
                </div>
                @if($room->notes)
                    <div class="mt-3">
                        <label class="text-muted small">Notes</label>
                        <div>{{ $room->notes }}</div>
                    </div>
                @endif
            </div>
        </div>

        @if($room->maintenanceRequests->count())
            <div class="card mb-4">
                <div class="card-header"><h6 class="mb-0 fw-semibold">Maintenance History</h6></div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <thead><tr><th>Date</th><th>Type</th><th>Status</th><th>Description</th></tr></thead>
                            <tbody>
                                @foreach($room->maintenanceRequests as $req)
                                    <tr>
                                        <td>{{ $req->created_at->format('M d, Y') }}</td>
                                        <td>{{ ucfirst($req->type ?? 'N/A') }}</td>
                                        <td><span class="badge bg-{{ $req->status == 'completed' ? 'success' : ($req->status == 'in_progress' ? 'warning' : 'secondary') }}">{{ ucfirst(str_replace('_',' ',$req->status)) }}</span></td>
                                        <td>{{ Str::limit($req->description, 60) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif

        @if($room->housekeepingAssignments->count())
            <div class="card mb-4">
                <div class="card-header"><h6 class="mb-0 fw-semibold">Housekeeping History</h6></div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <thead><tr><th>Date</th><th>Assigned To</th><th>Status</th></tr></thead>
                            <tbody>
                                @foreach($room->housekeepingAssignments as $ha)
                                    <tr>
                                        <td>{{ $ha->created_at->format('M d, Y') }}</td>
                                        <td>{{ $ha->assignedTo->name ?? 'N/A' }}</td>
                                        <td><span class="badge bg-{{ $ha->status == 'completed' ? 'success' : 'warning' }}">{{ ucfirst($ha->status) }}</span></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-header"><h6 class="mb-0 fw-semibold">Status</h6></div>
            <div class="card-body text-center">
                <span class="badge bg-{{ $room->status == 'available' ? 'success' : ($room->status == 'occupied' ? 'danger' : ($room->status == 'maintenance' ? 'warning' : ($room->status == 'dirty' ? 'secondary' : ($room->status == 'clean' ? 'info' : 'dark')))) }} fs-6 px-3 py-2">
                    {{ ucfirst(str_replace('_', ' ', $room->status)) }}
                </span>
                <div class="mt-3 d-grid gap-2">
                    @foreach(['available','dirty','clean','inspected','maintenance','out_of_order'] as $status)
                        @if($room->status !== $status)
                            <form method="POST" action="{{ route('admin.rooms.update-status', [$room->id, $status]) }}">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn btn-sm btn-outline-secondary w-100">Mark as {{ ucfirst(str_replace('_',' ',$status)) }}</button>
                            </form>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header"><h6 class="mb-0 fw-semibold">Quick Actions</h6></div>
            <div class="card-body d-grid gap-2">
                <a href="{{ route('admin.rooms.edit', $room->id) }}" class="btn btn-outline-primary"><i class="bi bi-pencil me-1"></i> Edit Room</a>
                <a href="{{ route('admin.rooms.availability') }}?hotel_id={{ $room->hotel_id }}" class="btn btn-outline-info"><i class="bi bi-calendar3 me-1"></i> View Availability</a>
                <button class="btn btn-outline-danger" onclick="confirmDelete('{{ route('admin.rooms.destroy', $room->id) }}', 'this room')"><i class="bi bi-trash me-1"></i> Delete Room</button>
            </div>
        </div>

        @if($room->images->count())
            <div class="card">
                <div class="card-header"><h6 class="mb-0 fw-semibold">Images</h6></div>
                <div class="card-body">
                    <div class="row g-2">
                        @foreach($room->images as $img)
                            <div class="col-6">
                                <img src="{{ asset('storage/' . $img->image_path) }}" alt="Room Image" class="img-fluid rounded">
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<div class="modal fade" id="confirmDeleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center py-4">
                <div class="delete-icon mb-3"><i class="bi bi-trash" style="font-size:3rem;color:#ef4444;"></i></div>
                <h5 class="fw-bold">Are you sure?</h5>
                <p class="text-muted" id="deleteModalText">This action cannot be undone.</p>
            </div>
            <div class="modal-footer justify-content-center border-0 pt-0">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST">@csrf @method('DELETE')<button type="submit" class="btn btn-danger">Delete</button></form>
            </div>
        </div>
    </div>
</div>
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

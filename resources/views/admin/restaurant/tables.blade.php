@extends('admin.layouts.app')

@section('title', 'Restaurant Tables')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-grid-3x3-gap"></i> Restaurant Tables</h4>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tableModal">
        <i class="bi bi-plus-lg"></i> Add Table
    </button>
</div>

<div class="row g-3">
    @forelse($tables as $table)
        <div class="col-md-3 col-sm-6">
            <div class="card h-100 {{ $table->status === 'occupied' ? 'border-danger' : ($table->status === 'reserved' ? 'border-warning' : 'border-success') }}">
                <div class="card-body text-center">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="badge {{ $table->status === 'occupied' ? 'bg-danger' : ($table->status === 'reserved' ? 'bg-warning text-dark' : 'bg-success') }}">
                            {{ ucfirst($table->status ?? 'available') }}
                        </span>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-light" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#editTableModal{{ $table->id }}"><i class="bi bi-pencil"></i> Edit</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('admin.restaurant.table-destroy', $table->id) }}" method="POST" onsubmit="return confirm('Delete this table?')">
                                        @csrf @method('DELETE')
                                        <button class="dropdown-item text-danger"><i class="bi bi-trash"></i> Delete</button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="my-3">
                        <i class="bi bi-grid-3x3-gap" style="font-size:48px;color:#6c757d;"></i>
                    </div>
                    <h5 class="mb-1">Table {{ $table->table_number }}</h5>
                    <p class="text-muted mb-0"><i class="bi bi-people"></i> {{ $table->capacity }} Seats</p>
                    @if($table->location)
                        <small class="text-muted">{{ $table->location }}</small>
                    @endif
                </div>
            </div>
        </div>

        <div class="modal fade" id="editTableModal{{ $table->id }}" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('admin.restaurant.table-update', $table->id) }}" method="POST">
                        @csrf @method('PUT')
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Table {{ $table->table_number }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Table Number</label>
                                <input type="text" name="table_number" class="form-control" value="{{ $table->table_number }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Capacity</label>
                                <input type="number" name="capacity" class="form-control" value="{{ $table->capacity }}" min="1" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Location</label>
                                <input type="text" name="location" class="form-control" value="{{ $table->location }}">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center py-5 text-muted">
                    <i class="bi bi-grid-3x3-gap" style="font-size:48px;"></i>
                    <p class="mt-3">No tables configured. Click "Add Table" to create one.</p>
                </div>
            </div>
        </div>
    @endforelse
</div>

<div class="modal fade" id="tableModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.restaurant.table-store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add New Table</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Table Number</label>
                        <input type="text" name="table_number" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Capacity</label>
                        <input type="number" name="capacity" class="form-control" min="1" value="4" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Location</label>
                        <select name="location" class="form-select">
                            <option value="indoor">Indoor</option>
                            <option value="outdoor">Outdoor</option>
                            <option value="private">Private Room</option>
                            <option value="patio">Patio</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Table</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

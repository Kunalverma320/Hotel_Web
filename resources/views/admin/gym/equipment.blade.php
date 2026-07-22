@extends('admin.layouts.app')

@section('title', 'Gym Equipment')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-bicycle"></i> Gym Equipment</h4>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#equipmentModal">
        <i class="bi bi-plus-lg"></i> Add Equipment
    </button>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <h6>Total Equipment</h6>
                <h2 class="mb-0">{{ $equipment->total() }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h6>Available</h6>
                <h2 class="mb-0">{{ $equipment->where('status', 'available')->count() }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-dark">
            <div class="card-body">
                <h6>Under Maintenance</h6>
                <h2 class="mb-0">{{ $equipment->where('status', 'maintenance')->count() }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-secondary text-white">
            <div class="card-body">
                <h6>Retired</h6>
                <h2 class="mb-0">{{ $equipment->where('status', 'retired')->count() }}</h2>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Quantity</th>
                        <th>Status</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($equipment as $item)
                        <tr>
                            <td><strong>{{ $item->name }}</strong></td>
                            <td><span class="badge bg-light text-dark">{{ ucfirst($item->category) }}</span></td>
                            <td>{{ $item->quantity }}</td>
                            <td>
                                @switch($item->status)
                                    @case('available')
                                        <span class="badge bg-success">Available</span>
                                        @break
                                    @case('maintenance')
                                        <span class="badge bg-warning text-dark">Maintenance</span>
                                        @break
                                    @case('retired')
                                        <span class="badge bg-secondary">Retired</span>
                                        @break
                                    @default
                                        <span class="badge bg-secondary">{{ ucfirst($item->status ?? 'available') }}</span>
                                @endswitch
                            </td>
                            <td><small class="text-muted">{{ Str::limit($item->description ?? '-', 50) }}</small></td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editEquipment{{ $item->id }}"><i class="bi bi-pencil"></i></button>
                                    <form action="{{ route('admin.gym.equipment-destroy', $item->id) }}" method="POST" onsubmit="return confirm('Delete?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-outline-danger"><i class="bi bi-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                        <div class="modal fade" id="editEquipment{{ $item->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('admin.gym.equipment-update', $item->id) }}" method="POST">
                                        @csrf @method('PUT')
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Equipment</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label">Name</label>
                                                <input type="text" name="name" class="form-control" value="{{ $item->name }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Category</label>
                                                <select name="category" class="form-select" required>
                                                    <option value="cardio" {{ $item->category == 'cardio' ? 'selected' : '' }}>Cardio</option>
                                                    <option value="strength" {{ $item->category == 'strength' ? 'selected' : '' }}>Strength</option>
                                                    <option value="flexibility" {{ $item->category == 'flexibility' ? 'selected' : '' }}>Flexibility</option>
                                                    <option value="free_weights" {{ $item->category == 'free_weights' ? 'selected' : '' }}>Free Weights</option>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Quantity</label>
                                                <input type="number" name="quantity" class="form-control" value="{{ $item->quantity }}" min="1" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Status</label>
                                                <select name="status" class="form-select">
                                                    <option value="available" {{ $item->status == 'available' ? 'selected' : '' }}>Available</option>
                                                    <option value="maintenance" {{ $item->status == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                                                    <option value="retired" {{ $item->status == 'retired' ? 'selected' : '' }}>Retired</option>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Description</label>
                                                <textarea name="description" class="form-control">{{ $item->description }}</textarea>
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
                        <tr><td colspan="6" class="text-center py-4 text-muted">No equipment found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer">
        {{ $equipment->links() }}
    </div>
</div>

<div class="modal fade" id="equipmentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.gym.equipment-store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add Equipment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Category</label>
                        <select name="category" class="form-select" required>
                            <option value="cardio">Cardio</option>
                            <option value="strength">Strength</option>
                            <option value="flexibility">Flexibility</option>
                            <option value="free_weights">Free Weights</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Quantity</label>
                        <input type="number" name="quantity" class="form-control" min="1" value="1" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Equipment</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

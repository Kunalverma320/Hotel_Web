@extends('admin.layouts.app')

@section('title', 'Spa Packages')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-flower1"></i> Spa Packages</h4>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#packageModal">
        <i class="bi bi-plus-lg"></i> Add Package
    </button>
</div>

<div class="row g-4">
    @forelse($packages as $package)
        <div class="col-md-4">
            <div class="card h-100 {{ $package->is_active ? 'border-success' : 'border-secondary' }}">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <span class="badge {{ $package->is_active ? 'bg-success' : 'bg-secondary' }} mb-2">
                                {{ $package->is_active ? 'Active' : 'Inactive' }}
                            </span>
                            <h5 class="card-title">{{ $package->name }}</h5>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-light" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#editPackage{{ $package->id }}"><i class="bi bi-pencil"></i> Edit</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('admin.spa.package-destroy', $package->id) }}" method="POST" onsubmit="return confirm('Delete?')">
                                        @csrf @method('DELETE')
                                        <button class="dropdown-item text-danger"><i class="bi bi-trash"></i> Delete</button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <p class="text-muted">{{ $package->description ?? 'No description' }}</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="text-primary mb-0">${{ number_format($package->price, 2) }}</h4>
                        <span class="badge bg-light text-dark"><i class="bi bi-clock"></i> {{ $package->duration_minutes }} min</span>
                    </div>
                    @if($package->services->count())
                        <hr>
                        <div>
                            @foreach($package->services as $service)
                                <span class="badge bg-light text-dark me-1 mb-1">{{ $service->name ?? $service }}</span>
                            @endforeach
                        </div>
                    @endif
                </div>
                <div class="card-footer">
                    <a href="{{ route('admin.spa.appointment-create') }}?package={{ $package->id }}" class="btn btn-outline-primary btn-sm w-100">
                        <i class="bi bi-calendar-plus"></i> Book Appointment
                    </a>
                </div>
            </div>
        </div>

        <div class="modal fade" id="editPackage{{ $package->id }}" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('admin.spa.package-update', $package->id) }}" method="POST">
                        @csrf @method('PUT')
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Package</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Name</label>
                                <input type="text" name="name" class="form-control" value="{{ $package->name }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea name="description" class="form-control">{{ $package->description }}</textarea>
                            </div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Price</label>
                                    <input type="number" name="price" class="form-control" value="{{ $package->price }}" step="0.01" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Duration (min)</label>
                                    <input type="number" name="duration_minutes" class="form-control" value="{{ $package->duration_minutes }}" required>
                                </div>
                            </div>
                            <div class="form-check form-switch mt-3">
                                <input class="form-check-input" type="checkbox" name="is_active" value="1" {{ $package->is_active ? 'checked' : '' }}>
                                <label class="form-check-label">Active</label>
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
                    <i class="bi bi-flower1" style="font-size:48px;"></i>
                    <p class="mt-3">No spa packages yet. Create your first package.</p>
                </div>
            </div>
        </div>
    @endforelse
</div>

<div class="modal fade" id="packageModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.spa.package-store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add Spa Package</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control"></textarea>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Price</label>
                            <input type="number" name="price" class="form-control" step="0.01" min="0" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Duration (min)</label>
                            <input type="number" name="duration_minutes" class="form-control" min="1" required>
                        </div>
                    </div>
                    <div class="form-check form-switch mt-3">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1" checked>
                        <label class="form-check-label">Active</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Package</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

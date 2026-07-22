@extends('admin.layouts.app')

@section('title', isset($department) ? 'Edit Department' : 'Add Department')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="fas fa-building me-2"></i>{{ isset($department) ? 'Edit Department' : 'Add Department' }}</h4>
    <a href="{{ route('admin.departments.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i>Back</a>
</div>

<div class="card" style="max-width:600px;">
    <div class="card-body">
        <form action="{{ isset($department) ? route('admin.departments.update', $department->id) : route('admin.departments.store') }}" method="POST">
            @csrf
            @if(isset($department)) @method('PUT') @endif

            <div class="mb-3">
                <label class="form-label">Name <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $department->name ?? '') }}" required>
                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="3">{{ old('description', $department->description ?? '') }}</textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="active" {{ old('status', $department->status ?? 'active') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('status', $department->status ?? '') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('admin.departments.index') }}" class="btn btn-outline-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>{{ isset($department) ? 'Update' : 'Save' }}</button>
            </div>
        </form>
    </div>
</div>
@endsection

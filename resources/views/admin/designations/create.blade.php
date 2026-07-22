@extends('admin.layouts.app')

@section('title', isset($designation) ? 'Edit Designation' : 'Add Designation')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="fas fa-id-badge me-2"></i>{{ isset($designation) ? 'Edit Designation' : 'Add Designation' }}</h4>
    <a href="{{ route('admin.designations.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i>Back</a>
</div>

<div class="card" style="max-width:600px;">
    <div class="card-body">
        <form action="{{ isset($designation) ? route('admin.designations.update', $designation->id) : route('admin.designations.store') }}" method="POST">
            @csrf
            @if(isset($designation)) @method('PUT') @endif

            <div class="mb-3">
                <label class="form-label">Name <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $designation->name ?? '') }}" required>
                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="3">{{ old('description', $designation->description ?? '') }}</textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="active" {{ old('status', $designation->status ?? 'active') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('status', $designation->status ?? '') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('admin.designations.index') }}" class="btn btn-outline-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>{{ isset($designation) ? 'Update' : 'Save' }}</button>
            </div>
        </form>
    </div>
</div>
@endsection

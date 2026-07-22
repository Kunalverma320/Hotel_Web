@extends('admin.layouts.app')

@section('title', isset($shift) ? 'Edit Shift' : 'Add Shift')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="fas fa-business-time me-2"></i>{{ isset($shift) ? 'Edit Shift' : 'Add Shift' }}</h4>
    <a href="{{ route('admin.shifts.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i>Back</a>
</div>

<div class="card" style="max-width:600px;">
    <div class="card-body">
        <form action="{{ isset($shift) ? route('admin.shifts.update', $shift->id) : route('admin.shifts.store') }}" method="POST">
            @csrf
            @if(isset($shift)) @method('PUT') @endif

            <div class="mb-3">
                <label class="form-label">Shift Name <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $shift->name ?? '') }}" placeholder="e.g., Morning Shift" required>
                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label">Start Time <span class="text-danger">*</span></label>
                    <input type="time" name="start_time" class="form-control @error('start_time') is-invalid @enderror" value="{{ old('start_time', isset($shift) && $shift->start_time ? $shift->start_time->format('H:i') : '') }}" required>
                    @error('start_time') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">End Time <span class="text-danger">*</span></label>
                    <input type="time" name="end_time" class="form-control @error('end_time') is-invalid @enderror" value="{{ old('end_time', isset($shift) && $shift->end_time ? $shift->end_time->format('H:i') : '') }}" required>
                    @error('end_time') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="2">{{ old('description', $shift->description ?? '') }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="active" {{ old('status', $shift->status ?? 'active') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('status', $shift->status ?? '') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('admin.shifts.index') }}" class="btn btn-outline-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>{{ isset($shift) ? 'Update' : 'Save' }}</button>
            </div>
        </form>
    </div>
</div>
@endsection

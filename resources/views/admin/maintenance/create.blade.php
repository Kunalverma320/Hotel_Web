@extends('admin.layouts.app')

@section('title', isset($request) ? 'Edit Maintenance Request' : 'New Maintenance Request')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">
        <i class="bi bi-tools"></i>
        {{ isset($request) ? 'Edit Maintenance Request #' . $request->id : 'New Maintenance Request' }}
    </h4>
    <a href="{{ route('admin.maintenance.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Back
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ isset($request) ? route('admin.maintenance.update', $request->id) : route('admin.maintenance.store') }}" method="POST">
            @csrf
            @if(isset($request))
                @method('PUT')
            @endif

            <div class="row g-3">
                <div class="col-md-8">
                    <label class="form-label">Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-control" value="{{ old('title', $request->title ?? '') }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Priority <span class="text-danger">*</span></label>
                    <select name="priority" class="form-select" required>
                        <option value="low" {{ old('priority', $request->priority ?? '') == 'low' ? 'selected' : '' }}>Low</option>
                        <option value="medium" {{ old('priority', $request->priority ?? '') == 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="high" {{ old('priority', $request->priority ?? '') == 'high' ? 'selected' : '' }}>High</option>
                        <option value="critical" {{ old('priority', $request->priority ?? '') == 'critical' ? 'selected' : '' }}>Critical</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Category <span class="text-danger">*</span></label>
                    <select name="category" class="form-select" required>
                        <option value="">-- Select --</option>
                        <option value="plumbing" {{ old('category', $request->category ?? '') == 'plumbing' ? 'selected' : '' }}>Plumbing</option>
                        <option value="electrical" {{ old('category', $request->category ?? '') == 'electrical' ? 'selected' : '' }}>Electrical</option>
                        <option value="hvac" {{ old('category', $request->category ?? '') == 'hvac' ? 'selected' : '' }}>HVAC</option>
                        <option value="furniture" {{ old('category', $request->category ?? '') == 'furniture' ? 'selected' : '' }}>Furniture</option>
                        <option value="appliances" {{ old('category', $request->category ?? '') == 'appliances' ? 'selected' : '' }}>Appliances</option>
                        <option value="general" {{ old('category', $request->category ?? '') == 'general' ? 'selected' : '' }}>General</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Location <span class="text-danger">*</span></label>
                    <input type="text" name="location" class="form-control" value="{{ old('location', $request->location ?? '') }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Room Number</label>
                    <input type="text" name="room_number" class="form-control" value="{{ old('room_number', $request->room_number ?? '') }}">
                </div>
                <div class="col-12">
                    <label class="form-label">Description <span class="text-danger">*</span></label>
                    <textarea name="description" class="form-control" rows="4" required>{{ old('description', $request->description ?? '') }}</textarea>
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg"></i> {{ isset($request) ? 'Update Request' : 'Create Request' }}
                </button>
                <a href="{{ route('admin.maintenance.index') }}" class="btn btn-outline-secondary ms-2">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection

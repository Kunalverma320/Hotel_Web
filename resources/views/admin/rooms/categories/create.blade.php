@extends('admin.layouts.app')
@section('title', isset($roomCategory) ? 'Edit Category' : 'Add Category')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0">{{ isset($roomCategory) ? 'Edit' : 'Add' }} Room Category</h4>
    </div>
    <a href="{{ route('admin.room-categories.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i> Back</a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ isset($roomCategory) ? route('admin.room-categories.update', $roomCategory->id) : route('admin.room-categories.store') }}">
            @csrf
            @if(isset($roomCategory)) @method('PUT') @endif
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Hotel <span class="text-danger">*</span></label>
                    <select name="hotel_id" class="form-select @error('hotel_id') is-invalid @enderror" required>
                        <option value="">Select Hotel</option>
                        @foreach($hotels as $hotel)
                            <option value="{{ $hotel->id }}" {{ old('hotel_id', $roomCategory->hotel_id ?? '') == $hotel->id ? 'selected' : '' }}>{{ $hotel->name }}</option>
                        @endforeach
                    </select>
                    @error('hotel_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $roomCategory->name ?? '') }}" required>
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Sort Order</label>
                    <input type="number" name="sort_order" class="form-control @error('sort_order') is-invalid @enderror" value="{{ old('sort_order', $roomCategory->sort_order ?? 0) }}">
                    @error('sort_order') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-12">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="3">{{ old('description', $roomCategory->description ?? '') }}</textarea>
                    @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Active</label>
                    <div class="form-check form-switch mt-2">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" class="form-check-input" {{ old('is_active', $roomCategory->is_active ?? 1) ? 'checked' : '' }}>
                    </div>
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i> {{ isset($roomCategory) ? 'Update' : 'Create' }} Category</button>
                <a href="{{ route('admin.room-categories.index') }}" class="btn btn-outline-secondary ms-2">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection

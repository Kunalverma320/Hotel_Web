@extends('admin.layouts.app')

@section('title', isset($testimonial) ? 'Edit Testimonial' : 'Create Testimonial')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">{{ isset($testimonial) ? 'Edit Testimonial' : 'Create Testimonial' }}</h4>
    <a href="{{ route('admin.cms.testimonials') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i> Back</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form method="POST" action="{{ isset($testimonial) ? route('admin.cms.testimonial-update', $testimonial->id) : route('admin.cms.testimonial-store') }}">
            @csrf
            @if(isset($testimonial)) @method('PUT') @endif

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Customer Name <span class="text-danger">*</span></label>
                    <input type="text" name="customer_name" class="form-control @error('customer_name') is-invalid @enderror" value="{{ old('customer_name', $testimonial->customer_name ?? '') }}" required>
                    @error('customer_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="customer_email" class="form-control @error('customer_email') is-invalid @enderror" value="{{ old('customer_email', $testimonial->customer_email ?? '') }}">
                    @error('customer_email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Rating</label>
                    <select name="rating" class="form-select">
                        <option value="">No Rating</option>
                        @for($i = 5; $i >= 1; $i--)
                            <option value="{{ $i }}" {{ (old('rating', $testimonial->rating ?? '') == $i) ? 'selected' : '' }}>{{ $i }} Star{{ $i > 1 ? 's' : '' }}</option>
                        @endfor
                    </select>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Content <span class="text-danger">*</span></label>
                <textarea name="content" class="form-control @error('content') is-invalid @enderror" rows="5" required>{{ old('content', $testimonial->content ?? '') }}</textarea>
                @error('content') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Status <span class="text-danger">*</span></label>
                <select name="status" class="form-select w-auto" required>
                    <option value="active" {{ ($testimonial->status ?? 'active') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ ($testimonial->status ?? '') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle me-1"></i> {{ isset($testimonial) ? 'Update' : 'Create' }} Testimonial</button>
        </form>
    </div>
</div>
@endsection

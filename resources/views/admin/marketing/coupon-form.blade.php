@extends('admin.layouts.app')

@section('title', isset($coupon) ? 'Edit Coupon' : 'Create Coupon')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">{{ isset($coupon) ? 'Edit Coupon' : 'Create Coupon' }}</h4>
    <a href="{{ route('admin.marketing.coupons') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i> Back</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form method="POST" action="{{ isset($coupon) ? route('admin.marketing.coupon-update', $coupon->id) : route('admin.marketing.coupon-store') }}">
            @csrf
            @if(isset($coupon)) @method('PUT') @endif

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Code <span class="text-danger">*</span></label>
                    <input type="text" name="code" class="form-control text-uppercase @error('code') is-invalid @enderror" value="{{ old('code', $coupon->code ?? '') }}" required>
                    @error('code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Type <span class="text-danger">*</span></label>
                    <select name="type" class="form-select" required>
                        <option value="percentage" {{ ($coupon->type ?? '') === 'percentage' ? 'selected' : '' }}>Percentage</option>
                        <option value="fixed" {{ ($coupon->type ?? '') === 'fixed' ? 'selected' : '' }}>Fixed Amount</option>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Value <span class="text-danger">*</span></label>
                    <input type="number" name="value" class="form-control @error('value') is-invalid @enderror" value="{{ old('value', $coupon->value ?? '') }}" step="0.01" min="0" required>
                    @error('value') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Minimum Order Amount</label>
                    <input type="number" name="min_order" class="form-control" value="{{ old('min_order', $coupon->min_order ?? '') }}" step="0.01" min="0">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Max Uses</label>
                    <input type="number" name="max_uses" class="form-control" value="{{ old('max_uses', $coupon->max_uses ?? '') }}" min="1">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-select" required>
                        <option value="active" {{ ($coupon->status ?? 'active') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ ($coupon->status ?? '') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Start Date <span class="text-danger">*</span></label>
                    <input type="date" name="starts_at" class="form-control" value="{{ old('starts_at', isset($coupon) ? \Carbon\Carbon::parse($coupon->starts_at)->format('Y-m-d') : '') }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">End Date <span class="text-danger">*</span></label>
                    <input type="date" name="ends_at" class="form-control" value="{{ old('ends_at', isset($coupon) ? \Carbon\Carbon::parse($coupon->ends_at)->format('Y-m-d') : '') }}" required>
                </div>
            </div>

            <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle me-1"></i> {{ isset($coupon) ? 'Update' : 'Create' }} Coupon</button>
        </form>
    </div>
</div>
@endsection

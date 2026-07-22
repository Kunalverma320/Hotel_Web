@extends('admin.layouts.app')

@section('title', isset($program) ? 'Edit Loyalty Program' : 'Create Loyalty Program')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">{{ isset($program) ? 'Edit Loyalty Program' : 'Create Loyalty Program' }}</h4>
    <a href="{{ route('admin.marketing.loyalty') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i> Back</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.marketing.loyalty-store') }}">
            @csrf

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Program Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $program->name ?? '') }}" required>
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Points per Dollar <span class="text-danger">*</span></label>
                    <input type="number" name="points_per_dollar" class="form-control" value="{{ old('points_per_dollar', $program->points_per_dollar ?? 1) }}" step="0.01" min="0" required>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-select" required>
                        <option value="active" {{ ($program->status ?? 'active') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ ($program->status ?? '') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Redeem Rate ($) <span class="text-danger">*</span></label>
                    <input type="number" name="redeem_rate" class="form-control" value="{{ old('redeem_rate', $program->redeem_rate ?? 0.01) }}" step="0.01" min="0" required>
                    <small class="text-muted">Value of each point when redeemed (e.g., 0.01 = 1 cent per point).</small>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Minimum Points to Redeem <span class="text-danger">*</span></label>
                    <input type="number" name="min_points_redeem" class="form-control" value="{{ old('min_points_redeem', $program->min_points_redeem ?? 100) }}" min="1" required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="4">{{ old('description', $program->description ?? '') }}</textarea>
            </div>

            <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle me-1"></i> {{ isset($program) ? 'Update' : 'Create' }} Program</button>
        </form>
    </div>
</div>
@endsection

@extends('admin.layouts.app')

@section('title', isset($notification) ? 'Edit Push Notification' : 'Create Push Notification')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">{{ isset($notification) ? 'Edit Push Notification' : 'Create Push Notification' }}</h4>
    <a href="{{ route('admin.marketing.push-notifications') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i> Back</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form method="POST" action="{{ isset($notification) ? route('admin.marketing.push-store') : route('admin.marketing.push-store') }}">
            @csrf

            <div class="row">
                <div class="col-md-8 mb-3">
                    <label class="form-label">Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $notification->title ?? '') }}" required>
                    @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-select" required>
                        <option value="draft" {{ ($notification->status ?? 'draft') === 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="published" {{ ($notification->status ?? '') === 'published' ? 'selected' : '' }}>Published</option>
                    </select>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Body <span class="text-danger">*</span></label>
                <textarea name="body" class="form-control @error('body') is-invalid @enderror" rows="4" required maxlength="500">{{ old('body', $notification->body ?? '') }}</textarea>
                @error('body') <div class="invalid-feedback">{{ $message }}</div> @enderror
                <small class="text-muted">Max 500 characters.</small>
            </div>

            <div class="row">
                <div class="col-md-8 mb-3">
                    <label class="form-label">URL</label>
                    <input type="url" name="url" class="form-control" value="{{ old('url', $notification->url ?? '') }}" placeholder="https://example.com/page">
                    <small class="text-muted">Where the user is taken when they click the notification.</small>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Target Audience <span class="text-danger">*</span></label>
                    <input type="text" name="target_audience" class="form-control" value="{{ old('target_audience', $notification->target_audience ?? 'All Users') }}" required placeholder="All Users">
                </div>
            </div>

            <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle me-1"></i> {{ isset($notification) ? 'Update' : 'Create' }} Notification</button>
        </form>
    </div>
</div>
@endsection

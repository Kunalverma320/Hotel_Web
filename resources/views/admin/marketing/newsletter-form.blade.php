@extends('admin.layouts.app')

@section('title', isset($newsletter) ? 'Edit Newsletter' : 'Create Newsletter')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">{{ isset($newsletter) ? 'Edit Newsletter' : 'Create Newsletter' }}</h4>
    <a href="{{ route('admin.marketing.newsletters') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i> Back</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form method="POST" action="{{ isset($newsletter) ? route('admin.marketing.newsletter-store') : route('admin.marketing.newsletter-store') }}">
            @csrf

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $newsletter->title ?? '') }}" required>
                    @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Email Subject <span class="text-danger">*</span></label>
                    <input type="text" name="subject" class="form-control @error('subject') is-invalid @enderror" value="{{ old('subject', $newsletter->subject ?? '') }}" required>
                    @error('subject') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Status <span class="text-danger">*</span></label>
                <select name="status" class="form-select w-auto" required>
                    <option value="draft" {{ ($newsletter->status ?? 'draft') === 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="published" {{ ($newsletter->status ?? '') === 'published' ? 'selected' : '' }}>Published</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Content <span class="text-danger">*</span></label>
                <textarea name="content" class="form-control @error('content') is-invalid @enderror" rows="15" required>{{ old('content', $newsletter->content ?? '') }}</textarea>
                @error('content') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle me-1"></i> {{ isset($newsletter) ? 'Update' : 'Create' }} Newsletter</button>
        </form>
    </div>
</div>
@endsection

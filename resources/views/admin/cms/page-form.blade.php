@extends('admin.layouts.app')

@section('title', isset($page) ? 'Edit Page' : 'Create Page')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">{{ isset($page) ? 'Edit Page' : 'Create Page' }}</h4>
    <a href="{{ route('admin.cms.pages') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i> Back</a>
</div>

<form method="POST" action="{{ isset($page) ? route('admin.cms.page-update', $page->id) : route('admin.cms.page-store') }}" id="pageForm">
    @csrf
    @if(isset($page)) @method('PUT') @endif

    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $page->title ?? '') }}" required id="pageTitle">
                        @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Slug <span class="text-danger">*</span></label>
                        <input type="text" name="slug" class="form-control @error('slug') is-invalid @enderror" value="{{ old('slug', $page->slug ?? '') }}" required id="pageSlug">
                        @error('slug') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Content <span class="text-danger">*</span></label>
                        <textarea name="content" class="form-control @error('content') is-invalid @enderror" rows="20" required>{{ old('content', $page->content ?? '') }}</textarea>
                        @error('content') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header"><h6 class="mb-0">Publishing</h6></div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-select" required>
                            <option value="draft" {{ ($page->status ?? 'draft') === 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="published" {{ ($page->status ?? '') === 'published' ? 'selected' : '' }}>Published</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100"><i class="bi bi-check-circle me-1"></i> {{ isset($page) ? 'Update' : 'Create' }} Page</button>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header"><h6 class="mb-0">SEO / Meta</h6></div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Meta Title</label>
                        <input type="text" name="meta_title" class="form-control" value="{{ old('meta_title', $page->meta_title ?? '') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Meta Description</label>
                        <textarea name="meta_description" class="form-control" rows="3">{{ old('meta_description', $page->meta_description ?? '') }}</textarea>
                    </div>
                    <div class="mb-0">
                        <label class="form-label">Meta Keywords</label>
                        <input type="text" name="meta_keywords" class="form-control" value="{{ old('meta_keywords', $page->meta_keywords ?? '') }}" placeholder="keyword1, keyword2">
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

@push('scripts')
<script>
document.getElementById('pageTitle').addEventListener('input', function() {
    if (!document.getElementById('pageSlug').value || '{{ isset($page) ? "1" : "0" }}' === '0') {
        document.getElementById('pageSlug').value = this.value.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '');
    }
});
</script>
@endpush
@endsection

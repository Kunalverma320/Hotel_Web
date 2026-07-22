@extends('admin.layouts.app')

@section('title', isset($blog) ? 'Edit Blog Post' : 'Create Blog Post')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">{{ isset($blog) ? 'Edit Blog Post' : 'Create Blog Post' }}</h4>
    <a href="{{ route('admin.cms.blogs') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i> Back</a>
</div>

<form method="POST" action="{{ isset($blog) ? route('admin.cms.blog-update', $blog->id) : route('admin.cms.blog-store') }}" enctype="multipart/form-data">
    @csrf
    @if(isset($blog)) @method('PUT') @endif

    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $blog->title ?? '') }}" required id="blogTitle">
                        @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Slug <span class="text-danger">*</span></label>
                        <input type="text" name="slug" class="form-control @error('slug') is-invalid @enderror" value="{{ old('slug', $blog->slug ?? '') }}" required id="blogSlug">
                        @error('slug') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Excerpt</label>
                        <textarea name="excerpt" class="form-control" rows="3">{{ old('excerpt', $blog->excerpt ?? '') }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Content <span class="text-danger">*</span></label>
                        <textarea name="content" class="form-control @error('content') is-invalid @enderror" rows="20" required>{{ old('content', $blog->content ?? '') }}</textarea>
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
                            <option value="draft" {{ ($blog->status ?? 'draft') === 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="published" {{ ($blog->status ?? '') === 'published' ? 'selected' : '' }}>Published</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100"><i class="bi bi-check-circle me-1"></i> {{ isset($blog) ? 'Update' : 'Create' }} Post</button>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header"><h6 class="mb-0">Featured Image</h6></div>
                <div class="card-body">
                    @if(isset($blog) && $blog->featured_image)
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $blog->featured_image) }}" class="img-fluid rounded" style="max-height: 150px;">
                        </div>
                    @endif
                    <input type="file" name="featured_image" class="form-control" accept="image/*">
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header"><h6 class="mb-0">Categories & Tags</h6></div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Category</label>
                        <input type="text" name="category" class="form-control" value="{{ old('category', $blog->category ?? '') }}" placeholder="e.g. News, Tips">
                    </div>
                    <div class="mb-0">
                        <label class="form-label">Tags</label>
                        <input type="text" name="tags" class="form-control" value="{{ old('tags', $blog->tags ?? '') }}" placeholder="tag1, tag2, tag3">
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header"><h6 class="mb-0">SEO / Meta</h6></div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Meta Title</label>
                        <input type="text" name="meta_title" class="form-control" value="{{ old('meta_title', $blog->meta_title ?? '') }}">
                    </div>
                    <div class="mb-0">
                        <label class="form-label">Meta Description</label>
                        <textarea name="meta_description" class="form-control" rows="3">{{ old('meta_description', $blog->meta_description ?? '') }}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

@push('scripts')
<script>
document.getElementById('blogTitle').addEventListener('input', function() {
    if (!document.getElementById('blogSlug').value || '{{ isset($blog) ? "1" : "0" }}' === '0') {
        document.getElementById('blogSlug').value = this.value.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '');
    }
});
</script>
@endpush
@endsection

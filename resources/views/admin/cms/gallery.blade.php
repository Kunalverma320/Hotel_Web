@extends('admin.layouts.app')

@section('title', 'Gallery')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Gallery</h4>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadModal">
        <i class="bi bi-cloud-upload me-1"></i> Upload Images
    </button>
</div>

<div class="row g-3" id="galleryGrid" style="columns: 4; column-gap: 1rem;">
    @forelse($items as $item)
        <div class="gallery-item mb-3 break-inside-avoid" data-id="{{ $item->id }}">
            <div class="card border-0 shadow-sm overflow-hidden">
                <a href="{{ asset('storage/' . $item->image) }}" data-lightbox="gallery" data-caption="{{ $item->caption ?? '' }}">
                    <img src="{{ asset('storage/' . $item->image) }}" class="w-100" alt="{{ $item->caption ?? '' }}" style="object-fit:cover; min-height: 150px; max-height: 300px;">
                </a>
                <div class="card-body p-2">
                    <div class="d-flex align-items-center justify-content-between">
                        <small class="text-muted text-truncate me-2">{{ $item->caption ?? 'Image' }}</small>
                        <div class="d-flex gap-1">
                            <button class="btn btn-sm btn-outline-secondary p-0" style="width:24px;height:24px;" title="Drag to reorder"><i class="bi bi-grip-vertical"></i></button>
                            <form method="POST" action="{{ route('admin.cms.gallery-delete', $item->id) }}" class="d-inline" onsubmit="return confirm('Delete this image?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger p-0" style="width:24px;height:24px;"><i class="bi bi-trash"></i></button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="text-center py-5">
                <i class="bi bi-images text-muted" style="font-size: 4rem;"></i>
                <p class="mt-3 text-muted">No gallery images found. Upload some images to get started.</p>
            </div>
        </div>
    @endforelse
</div>

<div class="mt-4">{{ $items->links() }}</div>

<div class="modal fade" id="uploadModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.cms.gallery-upload') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Upload Images</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Select Images (max 20)</label>
                        <input type="file" name="images[]" class="form-control" multiple accept="image/*" required id="galleryFiles">
                        <div class="form-text">Supported formats: JPG, PNG, GIF, WebP. Max 5MB each.</div>
                    </div>
                    <div id="imagePreview" class="row g-2"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-upload me-1"></i> Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css">
<style>
.break-inside-avoid { break-inside: avoid; }
.gallery-item { transition: transform 0.15s; }
.gallery-item:hover { transform: translateY(-2px); }
</style>
@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>
<script>
document.getElementById('galleryFiles').addEventListener('change', function(e) {
    var preview = document.getElementById('imagePreview');
    preview.innerHTML = '';
    Array.from(e.target.files).forEach(function(file) {
        var reader = new FileReader();
        reader.onload = function(ev) {
            var col = document.createElement('div');
            col.className = 'col-3';
            col.innerHTML = '<img src="' + ev.target.result + '" class="img-fluid rounded" style="height:80px;width:100%;object-fit:cover;">';
            preview.appendChild(col);
        };
        reader.readAsDataURL(file);
    });
});
lightbox.option({ 'resizeDuration': 200, 'wrapAround': true, 'maxWidth': 1200, 'maxHeight': 900 });
</script>
@endpush
@endsection

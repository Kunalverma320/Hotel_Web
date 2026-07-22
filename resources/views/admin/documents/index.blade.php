@extends('admin.layouts.app')

@section('title', 'Document Management')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Document Management</h4>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadModal">
        <i class="bi bi-cloud-upload me-1"></i> Upload File
    </button>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.documents.index') }}" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label">Search</label>
                <input type="text" name="search" class="form-control" placeholder="Search files..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">Type</label>
                <select name="type" class="form-select">
                    <option value="">All Types</option>
                    @foreach($types as $type)
                        <option value="{{ $type }}" {{ request('type') === $type ? 'selected' : '' }}>{{ ucfirst($type) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-outline-primary w-100"><i class="bi bi-search me-1"></i> Filter</button>
            </div>
            <div class="col-md-2">
                <a href="{{ route('admin.documents.index') }}" class="btn btn-outline-secondary w-100"><i class="bi bi-x-circle me-1"></i> Clear</a>
            </div>
            <div class="col-md-2 text-end">
                <div class="btn-group w-100" role="group">
                    <button type="button" class="btn btn-outline-secondary active" id="gridViewBtn"><i class="bi bi-grid"></i></button>
                    <button type="button" class="btn btn-outline-secondary" id="listViewBtn"><i class="bi bi-list"></i></button>
                </div>
            </div>
        </form>
    </div>
</div>

<div id="gridView" class="row g-3">
    @forelse($documents as $doc)
        <div class="col-xl-2 col-lg-3 col-md-4 col-6">
            <div class="card h-100 border-0 shadow-sm document-card">
                <div class="card-body text-center p-3">
                    <div class="document-icon mb-2">
                        @if(in_array($doc->type, ['image']))
                            <i class="bi bi-file-earmark-image text-success" style="font-size: 2.5rem;"></i>
                        @elseif($doc->type === 'document')
                            <i class="bi bi-file-earmark-text text-primary" style="font-size: 2.5rem;"></i>
                        @elseif($doc->type === 'video')
                            <i class="bi bi-file-earmark-play text-danger" style="font-size: 2.5rem;"></i>
                        @elseif($doc->type === 'audio')
                            <i class="bi bi-file-earmark-music text-warning" style="font-size: 2.5rem;"></i>
                        @elseif($doc->type === 'archive')
                            <i class="bi bi-file-earmark-zip text-secondary" style="font-size: 2.5rem;"></i>
                        @else
                            <i class="bi bi-file-earmark text-muted" style="font-size: 2.5rem;"></i>
                        @endif
                    </div>
                    <h6 class="card-title text-truncate mb-1" title="{{ $doc->name }}">{{ $doc->name }}</h6>
                    <small class="text-muted">{{ $doc->type }} &middot; {{ $doc->size ? number_format($doc->size / 1024, 1) . ' KB' : 'N/A' }}</small>
                    <br><small class="text-muted">{{ $doc->created_at->format('M d, Y') }}</small>
                </div>
                <div class="card-footer bg-transparent border-0 pb-3">
                    <div class="btn-group w-100" role="group">
                        @if(in_array($doc->type, ['image']))
                            <a href="{{ route('admin.documents.preview', $doc->id) }}" class="btn btn-sm btn-outline-info" title="Preview" target="_blank"><i class="bi bi-eye"></i></a>
                        @endif
                        <a href="{{ route('admin.documents.download', $doc->id) }}" class="btn btn-sm btn-outline-primary" title="Download"><i class="bi bi-download"></i></a>
                        <form method="POST" action="{{ route('admin.documents.destroy', $doc->id) }}" class="d-inline" onsubmit="return confirm('Delete this file?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete"><i class="bi bi-trash"></i></button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="text-center py-5">
                <i class="bi bi-folder2-open text-muted" style="font-size: 4rem;"></i>
                <p class="mt-3 text-muted">No documents found.</p>
            </div>
        </div>
    @endforelse
</div>

<div id="listView" class="d-none">
    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Size</th>
                        <th>Uploaded</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($documents as $doc)
                        <tr>
                            <td>
                                <i class="bi bi-file-earmark me-2 text-primary"></i>
                                {{ $doc->name }}
                            </td>
                            <td><span class="badge bg-light text-dark">{{ ucfirst($doc->type) }}</span></td>
                            <td>{{ $doc->size ? number_format($doc->size / 1024, 1) . ' KB' : 'N/A' }}</td>
                            <td>{{ $doc->created_at->format('M d, Y H:i') }}</td>
                            <td class="text-end">
                                @if(in_array($doc->type, ['image']))
                                    <a href="{{ route('admin.documents.preview', $doc->id) }}" class="btn btn-sm btn-outline-info" target="_blank"><i class="bi bi-eye"></i></a>
                                @endif
                                <a href="{{ route('admin.documents.download', $doc->id) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-download"></i></a>
                                <form method="POST" action="{{ route('admin.documents.destroy', $doc->id) }}" class="d-inline" onsubmit="return confirm('Delete this file?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center py-4 text-muted">No documents found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-4">{{ $documents->withQueryString()->links() }}</div>

<div class="modal fade" id="uploadModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.documents.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Upload Document</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">File</label>
                        <input type="file" name="file" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Type (auto-detected if empty)</label>
                        <select name="type" class="form-select">
                            <option value="">Auto-detect</option>
                            @foreach($types as $type)
                                <option value="{{ $type }}">{{ ucfirst($type) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('gridViewBtn').addEventListener('click', function() {
    document.getElementById('gridView').classList.remove('d-none');
    document.getElementById('listView').classList.add('d-none');
    this.classList.add('active');
    document.getElementById('listViewBtn').classList.remove('active');
});
document.getElementById('listViewBtn').addEventListener('click', function() {
    document.getElementById('listView').classList.remove('d-none');
    document.getElementById('gridView').classList.add('d-none');
    this.classList.add('active');
    document.getElementById('gridViewBtn').classList.remove('active');
});
</script>
@endpush

<style>
.document-card { transition: transform 0.15s; cursor: default; }
.document-card:hover { transform: translateY(-2px); box-shadow: 0 4px 15px rgba(0,0,0,0.1) !important; }
</style>
@endsection

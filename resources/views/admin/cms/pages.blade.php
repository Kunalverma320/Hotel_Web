@extends('admin.layouts.app')

@section('title', 'CMS Pages')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">CMS Pages</h4>
    <a href="{{ route('admin.cms.page-create') }}" class="btn btn-primary"><i class="bi bi-plus-circle me-1"></i> New Page</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Title</th>
                    <th>Slug</th>
                    <th>Status</th>
                    <th>Updated</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pages as $page)
                    <tr>
                        <td><strong>{{ $page->title }}</strong></td>
                        <td><code>/{{ $page->slug }}</code></td>
                        <td>
                            @if($page->status === 'published')
                                <span class="badge bg-success">Published</span>
                            @else
                                <span class="badge bg-secondary">Draft</span>
                            @endif
                        </td>
                        <td>{{ $page->updated_at->format('M d, Y') }}</td>
                        <td class="text-end">
                            <a href="{{ route('admin.cms.page-preview', $page->slug) }}" class="btn btn-sm btn-outline-info" target="_blank"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('admin.cms.page-edit', $page->id) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                            <form method="POST" action="{{ route('admin.cms.page-destroy', $page->id) }}" class="d-inline" onsubmit="return confirm('Delete this page?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center py-4 text-muted">No pages found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4">{{ $pages->links() }}</div>
@endsection

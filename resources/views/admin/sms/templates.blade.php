@extends('admin.layouts.app')

@section('title', 'SMS Templates')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">SMS Templates</h4>
    <a href="{{ route('admin.sms.template-create') }}" class="btn btn-primary"><i class="bi bi-plus-circle me-1"></i> New Template</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Name</th>
                    <th>Slug</th>
                    <th>Body</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($templates as $template)
                    <tr>
                        <td><strong>{{ $template->name }}</strong></td>
                        <td><code>{{ $template->slug }}</code></td>
                        <td>
                            <span class="text-truncate d-inline-block" style="max-width: 400px;">{{ $template->body }}</span>
                        </td>
                        <td class="text-end">
                            <a href="{{ route('admin.sms.template-edit', $template->id) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                            <form method="POST" action="{{ route('admin.sms.template-destroy', $template->id) }}" class="d-inline" onsubmit="return confirm('Delete this template?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center py-4 text-muted">No SMS templates found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4">{{ $templates->links() }}</div>
@endsection

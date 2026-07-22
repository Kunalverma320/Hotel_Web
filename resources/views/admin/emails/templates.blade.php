@extends('admin.layouts.app')

@section('title', 'Email Templates')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Email Templates</h4>
    <a href="{{ route('admin.emails.template-create') }}" class="btn btn-primary"><i class="bi bi-plus-circle me-1"></i> New Template</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Name</th>
                    <th>Slug</th>
                    <th>Subject</th>
                    <th>Variables</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($templates as $template)
                    <tr>
                        <td><strong>{{ $template->name }}</strong></td>
                        <td><code>{{ $template->slug }}</code></td>
                        <td>{{ $template->subject }}</td>
                        <td>
                            @if(preg_match_all('/\{(\w+)\}/', $template->body, $matches))
                                @foreach($matches[1] as $var)
                                    <span class="badge bg-info-subtle text-info me-1">{{ '{{' . $var . '}}' }}</span>
                                @endforeach
                            @else
                                <span class="text-muted">None</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <a href="{{ route('admin.emails.template-edit', $template->id) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                            <form method="POST" action="{{ route('admin.emails.template-destroy', $template->id) }}" class="d-inline" onsubmit="return confirm('Delete this template?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center py-4 text-muted">No email templates found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4">{{ $templates->links() }}</div>
@endsection

@extends('admin.layouts.app')

@section('title', 'Newsletters')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Newsletters</h4>
    <a href="{{ route('admin.marketing.newsletter-create') }}" class="btn btn-primary"><i class="bi bi-plus-circle me-1"></i> New Newsletter</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Title</th>
                    <th>Subject</th>
                    <th>Status</th>
                    <th>Sent At</th>
                    <th>Created</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($newsletters as $newsletter)
                    <tr>
                        <td><strong>{{ $newsletter->title }}</strong></td>
                        <td>{{ $newsletter->subject }}</td>
                        <td>
                            @if($newsletter->status === 'sent')
                                <span class="badge bg-success">Sent</span>
                            @elseif($newsletter->status === 'published')
                                <span class="badge bg-primary">Published</span>
                            @else
                                <span class="badge bg-secondary">Draft</span>
                            @endif
                        </td>
                        <td>{{ $newsletter->sent_at ? $newsletter->sent_at->format('M d, Y H:i') : '-' }}</td>
                        <td>{{ $newsletter->created_at->format('M d, Y') }}</td>
                        <td class="text-end">
                            @if($newsletter->status !== 'sent')
                                <form method="POST" action="{{ route('admin.marketing.newsletter-send', $newsletter->id) }}" class="d-inline" onsubmit="return confirm('Send this newsletter now?')">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success"><i class="bi bi-send me-1"></i> Send</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center py-4 text-muted">No newsletters found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4">{{ $newsletters->links() }}</div>
@endsection

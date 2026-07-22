@extends('admin.layouts.app')

@section('title', 'Push Notifications')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Push Notifications</h4>
    <a href="{{ route('admin.marketing.push-create') }}" class="btn btn-primary"><i class="bi bi-plus-circle me-1"></i> New Notification</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Title</th>
                    <th>Body</th>
                    <th>Target</th>
                    <th>Status</th>
                    <th>Sent At</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($notifications as $notification)
                    <tr>
                        <td><strong>{{ $notification->title }}</strong></td>
                        <td><span class="text-truncate d-inline-block" style="max-width: 300px;">{{ $notification->body }}</span></td>
                        <td><span class="badge bg-light text-dark">{{ $notification->target_audience }}</span></td>
                        <td>
                            @if($notification->status === 'sent')
                                <span class="badge bg-success">Sent</span>
                            @elseif($notification->status === 'published')
                                <span class="badge bg-primary">Published</span>
                            @else
                                <span class="badge bg-secondary">Draft</span>
                            @endif
                        </td>
                        <td>{{ $notification->sent_at ? $notification->sent_at->format('M d, Y H:i') : '-' }}</td>
                        <td class="text-end">
                            @if($notification->status !== 'sent')
                                <form method="POST" action="{{ route('admin.marketing.push-send', $notification->id) }}" class="d-inline" onsubmit="return confirm('Send this notification now?')">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success"><i class="bi bi-bell me-1"></i> Send</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center py-4 text-muted">No push notifications found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4">{{ $notifications->links() }}</div>
@endsection

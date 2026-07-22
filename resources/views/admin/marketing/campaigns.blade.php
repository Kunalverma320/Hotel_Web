@extends('admin.layouts.app')

@section('title', 'Campaigns')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Marketing Campaigns</h4>
    <a href="{{ route('admin.marketing.campaign-create') }}" class="btn btn-primary"><i class="bi bi-plus-circle me-1"></i> New Campaign</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Target</th>
                    <th>Status</th>
                    <th>Scheduled</th>
                    <th>Sent</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($campaigns as $campaign)
                    <tr>
                        <td><strong>{{ $campaign->name }}</strong></td>
                        <td>
                            @if($campaign->type === 'email')
                                <span class="badge bg-primary"><i class="bi bi-envelope me-1"></i>Email</span>
                            @elseif($campaign->type === 'sms')
                                <span class="badge bg-info"><i class="bi bi-chat-dots me-1"></i>SMS</span>
                            @else
                                <span class="badge bg-success"><i class="bi bi-whatsapp me-1"></i>WhatsApp</span>
                            @endif
                        </td>
                        <td>{{ $campaign->target_audience }}</td>
                        <td>
                            @if($campaign->status === 'sent')
                                <span class="badge bg-success">Sent</span>
                            @elseif($campaign->status === 'scheduled')
                                <span class="badge bg-warning text-dark">Scheduled</span>
                            @else
                                <span class="badge bg-secondary">Draft</span>
                            @endif
                        </td>
                        <td>{{ $campaign->scheduled_at ? $campaign->scheduled_at->format('M d, Y H:i') : '-' }}</td>
                        <td>{{ $campaign->sent_at ? $campaign->sent_at->format('M d, Y H:i') : '-' }}</td>
                        <td class="text-end">
                            @if($campaign->status !== 'sent')
                                <form method="POST" action="{{ route('admin.marketing.campaign-send', $campaign->id) }}" class="d-inline" onsubmit="return confirm('Send this campaign now?')">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success"><i class="bi bi-send me-1"></i> Send</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center py-4 text-muted">No campaigns found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4">{{ $campaigns->links() }}</div>
@endsection

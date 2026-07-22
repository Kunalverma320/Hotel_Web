@extends('admin.layouts.app')

@section('title', 'Email Logs')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Email Logs</h4>
    <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#testEmailModal">
        <i class="bi bi-send me-1"></i> Send Test Email
    </button>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>To</th>
                    <th>Subject</th>
                    <th>Status</th>
                    <th>Sent At</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                    <tr>
                        <td>{{ $log->to }}</td>
                        <td>{{ $log->subject }}</td>
                        <td>
                            @if($log->status === 'sent')
                                <span class="badge bg-success">Sent</span>
                            @elseif($log->status === 'failed')
                                <span class="badge bg-danger">Failed</span>
                            @else
                                <span class="badge bg-warning text-dark">Pending</span>
                            @endif
                        </td>
                        <td>{{ $log->sent_at ? $log->sent_at->format('M d, Y H:i:s') : '-' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center py-4 text-muted">No email logs found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4">{{ $logs->links() }}</div>

<div class="modal fade" id="testEmailModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.emails.send-test') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Send Test Email</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="email" class="form-control" required placeholder="test@example.com">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Send Test</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@extends('admin.layouts.app')

@section('title', 'WhatsApp Logs')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">WhatsApp Logs</h4>
    <button class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#sendWhatsappModal">
        <i class="bi bi-whatsapp me-1"></i> Send Message
    </button>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>To</th>
                    <th>Message</th>
                    <th>Status</th>
                    <th>Error</th>
                    <th>Sent At</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                    <tr>
                        <td>{{ $log->to }}</td>
                        <td><span class="text-truncate d-inline-block" style="max-width: 300px;">{{ $log->message }}</span></td>
                        <td>
                            @if($log->status === 'sent')
                                <span class="badge bg-success">Sent</span>
                            @elseif($log->status === 'failed')
                                <span class="badge bg-danger">Failed</span>
                            @else
                                <span class="badge bg-warning text-dark">Pending</span>
                            @endif
                        </td>
                        <td class="text-danger small">{{ $log->error ?? '-' }}</td>
                        <td>{{ $log->sent_at ? $log->sent_at->format('M d, Y H:i:s') : '-' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center py-4 text-muted">No WhatsApp logs found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4">{{ $logs->links() }}</div>

<div class="modal fade" id="sendWhatsappModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.whatsapp.send-message') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-whatsapp text-success me-2"></i>Send WhatsApp Message</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Phone Number</label>
                        <input type="text" name="phone" class="form-control" required placeholder="+1234567890">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Message</label>
                        <textarea name="message" class="form-control" rows="5" required placeholder="Type your message..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success"><i class="bi bi-send me-1"></i> Send</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

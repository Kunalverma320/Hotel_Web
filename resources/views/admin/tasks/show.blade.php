@extends('admin.layouts.app')

@section('title', $task->title)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="fas fa-tasks me-2"></i>{{ $task->title }}</h4>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.tasks.edit', $task->id) }}" class="btn btn-primary"><i class="fas fa-edit me-1"></i>Edit</a>
        <a href="{{ route('admin.tasks.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i>Back</a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header"><h5 class="mb-0">Task Details</h5></div>
            <div class="card-body">
                <p>{{ $task->description ?? 'No description provided.' }}</p>

                <div class="row g-3 mt-2">
                    <div class="col-md-3">
                        <small class="text-muted d-block">Priority</small>
                        @if($task->priority == 'urgent')
                            <span class="badge bg-danger">Urgent</span>
                        @elseif($task->priority == 'high')
                            <span class="badge bg-warning text-dark">High</span>
                        @elseif($task->priority == 'medium')
                            <span class="badge bg-info">Medium</span>
                        @else
                            <span class="badge bg-secondary">Low</span>
                        @endif
                    </div>
                    <div class="col-md-3">
                        <small class="text-muted d-block">Status</small>
                        @if($task->status == 'todo') <span class="badge bg-secondary">To Do</span>
                        @elseif($task->status == 'in_progress') <span class="badge bg-primary">In Progress</span>
                        @elseif($task->status == 'done') <span class="badge bg-success">Done</span>
                        @else <span class="badge bg-dark">Cancelled</span>
                        @endif
                    </div>
                    <div class="col-md-3">
                        <small class="text-muted d-block">Due Date</small>
                        <span>{{ $task->due_date ? \Carbon\Carbon::parse($task->due_date)->format('d M Y') : 'Not set' }}</span>
                    </div>
                    <div class="col-md-3">
                        <small class="text-muted d-block">Created By</small>
                        <span>{{ $task->creator ? $task->creator->name : '-' }}</span>
                    </div>
                </div>

                <hr>
                <div class="row">
                    <div class="col-md-6">
                        <small class="text-muted d-block">Assigned To</small>
                        <span>{{ $task->assignee ? $task->assignee->first_name . ' ' . $task->assignee->last_name : 'Unassigned' }}</span>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block">Created At</small>
                        <span>{{ $task->created_at->format('d M Y, h:i A') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header"><h5 class="mb-0">Update Status</h5></div>
            <div class="card-body">
                <div class="btn-group">
                    <button class="btn btn-secondary {{ $task->status == 'todo' ? 'active' : '' }}" onclick="updateTaskStatus('{{ $task->id }}', 'todo')">To Do</button>
                    <button class="btn btn-primary {{ $task->status == 'in_progress' ? 'active' : '' }}" onclick="updateTaskStatus('{{ $task->id }}', 'in_progress')">In Progress</button>
                    <button class="btn btn-success {{ $task->status == 'done' ? 'active' : '' }}" onclick="updateTaskStatus('{{ $task->id }}', 'done')">Done</button>
                    <button class="btn btn-dark {{ $task->status == 'cancelled' ? 'active' : '' }}" onclick="updateTaskStatus('{{ $task->id }}', 'cancelled')">Cancelled</button>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header"><h5 class="mb-0">Comments ({{ $task->comments->count() }})</h5></div>
            <div class="card-body" style="max-height:400px;overflow-y:auto;">
                @forelse($task->comments->reverse() as $comment)
                <div class="mb-3 pb-3 border-bottom">
                    <div class="d-flex justify-content-between">
                        <strong>{{ $comment->user->name ?? 'User' }}</strong>
                        <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                    </div>
                    <p class="mb-0 mt-1">{{ $comment->comment }}</p>
                </div>
                @empty
                <p class="text-muted text-center">No comments yet.</p>
                @endforelse
            </div>
            <div class="card-footer">
                <form action="{{ route('admin.tasks.comment', $task->id) }}" method="POST">
                    @csrf
                    <div class="mb-2">
                        <textarea name="comment" class="form-control" rows="2" placeholder="Write a comment..." required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm w-100"><i class="fas fa-paper-plane me-1"></i>Add Comment</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function updateTaskStatus(taskId, status) {
    fetch(`/admin/tasks/${taskId}/status/${status}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        }
    }).then(r => r.json()).then(data => {
        if (data.success) location.reload();
    });
}
</script>
@endpush

@extends('admin.layouts.app')

@section('title', 'Task Board')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="fas fa-columns me-2"></i>Task Board</h4>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.tasks.index') }}" class="btn btn-outline-primary"><i class="fas fa-list me-1"></i>List View</a>
        <a href="{{ route('admin.tasks.create') }}" class="btn btn-primary"><i class="fas fa-plus me-1"></i>Add Task</a>
    </div>
</div>

<div class="row g-3 kanban-board">
    {{-- To Do Column --}}
    <div class="col-lg-3 col-md-6">
        <div class="card bg-light h-100">
            <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
                <h6 class="mb-0"><i class="fas fa-circle me-1" style="font-size:0.6rem;"></i>To Do</h6>
                <span class="badge bg-white text-secondary">{{ $todo->count() }}</span>
            </div>
            <div class="card-body p-2 kanban-column" data-status="todo" style="min-height:400px;max-height:70vh;overflow-y:auto;">
                @forelse($todo as $task)
                <div class="card mb-2 task-card shadow-sm" draggable="true" data-task-id="{{ $task->id }}" data-status="todo">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <a href="{{ route('admin.tasks.show', $task->id) }}" class="text-decoration-none fw-bold text-dark">{{ Str::limit($task->title, 40) }}</a>
                            @if($task->priority == 'urgent')
                                <span class="badge bg-danger">!</span>
                            @endif
                        </div>
                        @if($task->description)
                            <p class="text-muted small mb-2">{{ Str::limit($task->description, 80) }}</p>
                        @endif
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                @if($task->assignee)
                                    <i class="fas fa-user me-1"></i>{{ $task->assignee->first_name }}
                                @endif
                            </small>
                            @if($task->due_date)
                                <small class="{{ \Carbon\Carbon::parse($task->due_date)->isPast() ? 'text-danger' : 'text-muted' }}">
                                    <i class="fas fa-calendar me-1"></i>{{ \Carbon\Carbon::parse($task->due_date)->format('d M') }}
                                </small>
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center text-muted py-4">No tasks</div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- In Progress Column --}}
    <div class="col-lg-3 col-md-6">
        <div class="card bg-light h-100">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h6 class="mb-0"><i class="fas fa-spinner me-1"></i>In Progress</h6>
                <span class="badge bg-white text-primary">{{ $inProgress->count() }}</span>
            </div>
            <div class="card-body p-2 kanban-column" data-status="in_progress" style="min-height:400px;max-height:70vh;overflow-y:auto;">
                @forelse($inProgress as $task)
                <div class="card mb-2 task-card shadow-sm border-primary" draggable="true" data-task-id="{{ $task->id }}" data-status="in_progress">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <a href="{{ route('admin.tasks.show', $task->id) }}" class="text-decoration-none fw-bold text-dark">{{ Str::limit($task->title, 40) }}</a>
                            @if($task->priority == 'urgent')
                                <span class="badge bg-danger">!</span>
                            @endif
                        </div>
                        @if($task->description)
                            <p class="text-muted small mb-2">{{ Str::limit($task->description, 80) }}</p>
                        @endif
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                @if($task->assignee)
                                    <i class="fas fa-user me-1"></i>{{ $task->assignee->first_name }}
                                @endif
                            </small>
                            @if($task->due_date)
                                <small class="{{ \Carbon\Carbon::parse($task->due_date)->isPast() ? 'text-danger' : 'text-muted' }}">
                                    <i class="fas fa-calendar me-1"></i>{{ \Carbon\Carbon::parse($task->due_date)->format('d M') }}
                                </small>
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center text-muted py-4">No tasks</div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Done Column --}}
    <div class="col-lg-3 col-md-6">
        <div class="card bg-light h-100">
            <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                <h6 class="mb-0"><i class="fas fa-check-circle me-1"></i>Done</h6>
                <span class="badge bg-white text-success">{{ $done->count() }}</span>
            </div>
            <div class="card-body p-2 kanban-column" data-status="done" style="min-height:400px;max-height:70vh;overflow-y:auto;">
                @forelse($done as $task)
                <div class="card mb-2 task-card shadow-sm border-success" draggable="true" data-task-id="{{ $task->id }}" data-status="done">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <a href="{{ route('admin.tasks.show', $task->id) }}" class="text-decoration-none fw-bold text-dark text-decoration-line-through">{{ Str::limit($task->title, 40) }}</a>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                @if($task->assignee)
                                    <i class="fas fa-user me-1"></i>{{ $task->assignee->first_name }}
                                @endif
                            </small>
                            <small class="text-success"><i class="fas fa-check"></i></small>
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center text-muted py-4">No tasks</div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Cancelled Column --}}
    <div class="col-lg-3 col-md-6">
        <div class="card bg-light h-100">
            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                <h6 class="mb-0"><i class="fas fa-ban me-1"></i>Cancelled</h6>
                <span class="badge bg-white text-dark">{{ $cancelled->count() }}</span>
            </div>
            <div class="card-body p-2 kanban-column" data-status="cancelled" style="min-height:400px;max-height:70vh;overflow-y:auto;">
                @forelse($cancelled as $task)
                <div class="card mb-2 task-card shadow-sm border-dark" draggable="true" data-task-id="{{ $task->id }}" data-status="cancelled">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <a href="{{ route('admin.tasks.show', $task->id) }}" class="text-decoration-none fw-bold text-muted text-decoration-line-through">{{ Str::limit($task->title, 40) }}</a>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                @if($task->assignee)
                                    <i class="fas fa-user me-1"></i>{{ $task->assignee->first_name }}
                                @endif
                            </small>
                            <small class="text-danger"><i class="fas fa-ban"></i></small>
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center text-muted py-4">No tasks</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.kanban-board .card-body.kanban-column { background: #f8f9fa; border-radius: 0 0 0.375rem 0.375rem; }
.task-card { cursor: grab; transition: transform 0.2s, box-shadow 0.2s; }
.task-card:active { cursor: grabbing; }
.task-card.dragging { opacity: 0.5; transform: rotate(3deg); }
.kanban-column.drag-over { background: #e3f2fd !important; border: 2px dashed #0d6efd; border-radius: 0.375rem; }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const cards = document.querySelectorAll('.task-card');
    const columns = document.querySelectorAll('.kanban-column');
    let draggedCard = null;

    cards.forEach(card => {
        card.addEventListener('dragstart', function(e) {
            draggedCard = this;
            this.classList.add('dragging');
            e.dataTransfer.effectAllowed = 'move';
            e.dataTransfer.setData('text/plain', this.dataset.taskId);
        });

        card.addEventListener('dragend', function() {
            this.classList.remove('dragging');
            columns.forEach(col => col.classList.remove('drag-over'));
            draggedCard = null;
        });
    });

    columns.forEach(column => {
        column.addEventListener('dragover', function(e) {
            e.preventDefault();
            e.dataTransfer.dropEffect = 'move';
            this.classList.add('drag-over');
        });

        column.addEventListener('dragleave', function() {
            this.classList.remove('drag-over');
        });

        column.addEventListener('drop', function(e) {
            e.preventDefault();
            this.classList.remove('drag-over');

            if (draggedCard) {
                const taskId = draggedCard.dataset.taskId;
                const newStatus = this.dataset.status;

                fetch(`/admin/tasks/${taskId}/status/${newStatus}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        this.appendChild(draggedCard);
                        draggedCard.dataset.status = newStatus;

                        document.querySelectorAll('.badge').forEach(badge => {
                            const cardCount = badge.closest('.card')?.querySelector('.kanban-column');
                            if (cardCount) {
                                const count = cardCount.querySelectorAll('.task-card').length;
                                const countBadge = badge.closest('.card-header')?.querySelector('.badge');
                                if (countBadge) countBadge.textContent = count;
                            }
                        });

                        const toast = document.createElement('div');
                        toast.className = 'position-fixed bottom-0 end-0 p-3';
                        toast.style.zIndex = '9999';
                        toast.innerHTML = `<div class="toast show align-items-center text-bg-success border-0"><div class="d-flex"><div class="toast-body">Task moved successfully.</div></div></div>`;
                        document.body.appendChild(toast);
                        setTimeout(() => toast.remove(), 2000);
                    }
                });
            }
        });
    });
});
</script>
@endpush

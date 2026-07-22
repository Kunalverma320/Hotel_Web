@extends('admin.layouts.app')

@section('title', 'Tasks')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="fas fa-tasks me-2"></i>Task Management</h4>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.tasks.board') }}" class="btn btn-outline-primary"><i class="fas fa-columns me-1"></i>Kanban Board</a>
        <a href="{{ route('admin.tasks.create') }}" class="btn btn-primary"><i class="fas fa-plus me-1"></i>Add Task</a>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.tasks.index') }}" class="row g-3 align-items-end">
            <div class="col-md-2">
                <input type="text" name="search" class="form-control" placeholder="Search tasks..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="todo" {{ request('status') == 'todo' ? 'selected' : '' }}>To Do</option>
                    <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="done" {{ request('status') == 'done' ? 'selected' : '' }}>Done</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="priority" class="form-select">
                    <option value="">All Priority</option>
                    <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low</option>
                    <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                    <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High</option>
                    <option value="urgent" {{ request('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="assigned_to" class="form-select">
                    <option value="">All Assignees</option>
                    @foreach($employees as $emp)
                        <option value="{{ $emp->id }}" {{ request('assigned_to') == $emp->id ? 'selected' : '' }}>{{ $emp->first_name }} {{ $emp->last_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-outline-primary w-100"><i class="fas fa-filter me-1"></i>Filter</button>
            </div>
            <div class="col-md-2">
                <a href="{{ route('admin.tasks.index') }}" class="btn btn-outline-secondary w-100">Clear</a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Title</th>
                    <th>Assigned To</th>
                    <th>Priority</th>
                    <th>Due Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tasks as $task)
                <tr>
                    <td>
                        <a href="{{ route('admin.tasks.show', $task->id) }}" class="text-decoration-none fw-semibold">{{ $task->title }}</a>
                    </td>
                    <td>{{ $task->assignee ? $task->assignee->first_name . ' ' . $task->assignee->last_name : '-' }}</td>
                    <td>
                        @if($task->priority == 'urgent')
                            <span class="badge bg-danger">Urgent</span>
                        @elseif($task->priority == 'high')
                            <span class="badge bg-warning text-dark">High</span>
                        @elseif($task->priority == 'medium')
                            <span class="badge bg-info">Medium</span>
                        @else
                            <span class="badge bg-secondary">Low</span>
                        @endif
                    </td>
                    <td>{{ $task->due_date ? \Carbon\Carbon::parse($task->due_date)->format('d M Y') : '-' }}</td>
                    <td>
                        @if($task->status == 'todo') <span class="badge bg-secondary">To Do</span>
                        @elseif($task->status == 'in_progress') <span class="badge bg-primary">In Progress</span>
                        @elseif($task->status == 'done') <span class="badge bg-success">Done</span>
                        @else <span class="badge bg-dark">Cancelled</span>
                        @endif
                    </td>
                    <td>
                        <div class="btn-group btn-group-sm">
                            <a href="{{ route('admin.tasks.show', $task->id) }}" class="btn btn-outline-info"><i class="fas fa-eye"></i></a>
                            <a href="{{ route('admin.tasks.edit', $task->id) }}" class="btn btn-outline-primary"><i class="fas fa-edit"></i></a>
                            <form action="{{ route('admin.tasks.destroy', $task->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this task?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center py-4 text-muted">No tasks found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer">{{ $tasks->withQueryString()->links() }}</div>
</div>
@endsection

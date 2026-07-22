@extends('admin.layouts.app')

@section('title', 'Housekeeping Management')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-eraser"></i> Housekeeping Management</h4>
    <div>
        <a href="{{ route('admin.housekeeping.schedule') }}" class="btn btn-outline-primary">
            <i class="bi bi-calendar3"></i> Schedule
        </a>
        <a href="{{ route('admin.housekeeping.reports') }}" class="btn btn-outline-secondary">
            <i class="bi bi-graph-up"></i> Reports
        </a>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.housekeeping.index') }}" class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="assigned" {{ request('status') == 'assigned' ? 'selected' : '' }}>Assigned</option>
                    <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Date</label>
                <input type="date" name="date" class="form-control" value="{{ request('date') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">Assigned To</label>
                <select name="assigned_to" class="form-select">
                    <option value="">All Staff</option>
                    @foreach($staff as $member)
                        <option value="{{ $member->id }}" {{ request('assigned_to') == $member->id ? 'selected' : '' }}>
                            {{ $member->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <button type="submit" class="btn btn-primary me-2"><i class="bi bi-search"></i> Filter</button>
                <a href="{{ route('admin.housekeeping.index') }}" class="btn btn-outline-secondary">Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Room</th>
                        <th>Type</th>
                        <th>Scheduled Date</th>
                        <th>Assigned To</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tasks as $task)
                        <tr>
                            <td>
                                <strong>{{ $task->room->room_number ?? 'N/A' }}</strong>
                                <br><small class="text-muted">Floor {{ $task->room->floor ?? '-' }}</small>
                            </td>
                            <td>{{ ucfirst($task->type ?? 'general') }}</td>
                            <td>
                                {{ $task->scheduled_date ? \Carbon\Carbon::parse($task->scheduled_date)->format('M d, Y') : '-' }}
                                <br><small class="text-muted">{{ $task->scheduled_time ?? '' }}</small>
                            </td>
                            <td>
                                @if($task->assignee)
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width:32px;height:32px;font-size:12px;">
                                            {{ substr($task->assignee->name, 0, 2) }}
                                        </div>
                                        <span class="ms-2">{{ $task->assignee->name }}</span>
                                    </div>
                                @else
                                    <span class="text-muted">Unassigned</span>
                                @endif
                            </td>
                            <td>
                                @switch($task->status)
                                    @case('pending')
                                        <span class="badge bg-warning text-dark">Pending</span>
                                        @break
                                    @case('assigned')
                                        <span class="badge bg-info">Assigned</span>
                                        @break
                                    @case('in_progress')
                                        <span class="badge bg-primary">In Progress</span>
                                        @break
                                    @case('completed')
                                        <span class="badge bg-success">Completed</span>
                                        @break
                                    @default
                                        <span class="badge bg-secondary">{{ ucfirst($task->status) }}</span>
                                @endswitch
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    @if(!$task->assignee)
                                        <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#assignModal{{ $task->id }}">
                                            <i class="bi bi-person-plus"></i>
                                        </button>
                                    @endif
                                    @if($task->status !== 'completed')
                                        <a href="{{ route('admin.housekeeping.update-status', [$task->id, 'in_progress']) }}" class="btn btn-outline-warning">
                                            <i class="bi bi-play"></i>
                                        </a>
                                        <a href="{{ route('admin.housekeeping.update-status', [$task->id, 'completed']) }}" class="btn btn-outline-success">
                                            <i class="bi bi-check-lg"></i>
                                        </a>
                                    @endif
                                </div>

                                @if(!$task->assignee)
                                    <div class="modal fade" id="assignModal{{ $task->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form action="{{ route('admin.housekeeping.assign') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="task_id" value="{{ $task->id }}">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Assign Housekeeper</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label class="form-label">Select Staff</label>
                                                            <select name="assigned_to" class="form-select" required>
                                                                <option value="">-- Select --</option>
                                                                @foreach($staff as $member)
                                                                    <option value="{{ $member->id }}">{{ $member->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-primary">Assign</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">No housekeeping tasks found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer">
        {{ $tasks->links() }}
    </div>
</div>
@endsection

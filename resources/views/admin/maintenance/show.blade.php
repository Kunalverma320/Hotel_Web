@extends('admin.layouts.app')

@section('title', 'Maintenance Request #' . $request->id)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-tools"></i> Request #{{ $request->id }}</h4>
    <div>
        <a href="{{ route('admin.maintenance.edit', $request->id) }}" class="btn btn-outline-primary">
            <i class="bi bi-pencil"></i> Edit
        </a>
        <a href="{{ route('admin.maintenance.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Request Details</h6>
                @switch($request->status)
                    @case('pending')
                        <span class="badge bg-warning text-dark fs-6">Pending</span>
                        @break
                    @case('assigned')
                        <span class="badge bg-info fs-6">Assigned</span>
                        @break
                    @case('in_progress')
                        <span class="badge bg-primary fs-6">In Progress</span>
                        @break
                    @case('completed')
                        <span class="badge bg-success fs-6">Completed</span>
                        @break
                @endswitch
            </div>
            <div class="card-body">
                <h5>{{ $request->title }}</h5>
                <p class="text-muted">{{ $request->description }}</p>
                <hr>
                <div class="row">
                    <div class="col-md-6">
                        <small class="text-muted">Category</small><br>
                        <span class="badge bg-light text-dark">{{ ucfirst($request->category) }}</span>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted">Priority</small><br>
                        @switch($request->priority)
                            @case('critical')
                                <span class="badge bg-danger"><i class="bi bi-exclamation-triangle"></i> Critical</span>
                                @break
                            @case('high')
                                <span class="badge bg-warning text-dark">High</span>
                                @break
                            @case('medium')
                                <span class="badge bg-info">Medium</span>
                                @break
                            @case('low')
                                <span class="badge bg-secondary">Low</span>
                                @break
                        @endswitch
                    </div>
                    <div class="col-md-6 mt-3">
                        <small class="text-muted">Location</small><br>
                        <strong>{{ $request->location }}</strong>
                    </div>
                    <div class="col-md-6 mt-3">
                        <small class="text-muted">Room Number</small><br>
                        <strong>{{ $request->room_number ?? 'N/A' }}</strong>
                    </div>
                    <div class="col-md-6 mt-3">
                        <small class="text-muted">Created</small><br>
                        {{ $request->created_at->format('M d, Y h:i A') }}
                    </div>
                    <div class="col-md-6 mt-3">
                        <small class="text-muted">Last Updated</small><br>
                        {{ $request->updated_at->format('M d, Y h:i A') }}
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Timeline</h6>
            </div>
            <div class="card-body">
                @forelse($request->timeline ?? [] as $entry)
                    <div class="d-flex mb-3">
                        <div class="me-3">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width:36px;height:36px;">
                                <i class="bi bi-circle-fill" style="font-size:8px;"></i>
                            </div>
                        </div>
                        <div>
                            <strong>{{ $entry->action ?? 'Status Updated' }}</strong>
                            <small class="text-muted d-block">{{ $entry->created_at ?? '' }}</small>
                            <p class="mb-0">{{ $entry->notes ?? '' }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-muted text-center">No timeline entries yet.</p>
                @endforelse
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0"><i class="bi bi-person-plus"></i> Assign Technician</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.maintenance.assign', $request->id) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <select name="assigned_to" class="form-select" required>
                            <option value="">-- Select Technician --</option>
                            @foreach($technicians as $tech)
                                <option value="{{ $tech->id }}" {{ $request->assigned_to == $tech->id ? 'selected' : '' }}>
                                    {{ $tech->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-check-lg"></i> Assign
                    </button>
                </form>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0"><i class="bi bi-arrow-repeat"></i> Update Status</h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    @if($request->status !== 'in_progress')
                        <a href="{{ route('admin.maintenance.update-status', [$request->id, 'in_progress']) }}" class="btn btn-warning">
                            <i class="bi bi-play"></i> Mark In Progress
                        </a>
                    @endif
                    @if($request->status !== 'completed')
                        <a href="{{ route('admin.maintenance.update-status', [$request->id, 'completed']) }}" class="btn btn-success">
                            <i class="bi bi-check-lg"></i> Mark Completed
                        </a>
                    @endif
                    @if($request->status !== 'pending')
                        <a href="{{ route('admin.maintenance.update-status', [$request->id, 'pending']) }}" class="btn btn-outline-warning">
                            <i class="bi bi-arrow-counterclockwise"></i> Reopen
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Assigned Technician</h6>
            </div>
            <div class="card-body text-center">
                @if($request->assignee)
                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2" style="width:60px;height:60px;font-size:20px;">
                        {{ substr($request->assignee->name, 0, 2) }}
                    </div>
                    <strong>{{ $request->assignee->name }}</strong>
                @else
                    <p class="text-muted">No technician assigned</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

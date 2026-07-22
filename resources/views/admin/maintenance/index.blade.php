@extends('admin.layouts.app')

@section('title', 'Maintenance Requests')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-tools"></i> Maintenance Requests</h4>
    <a href="{{ route('admin.maintenance.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> New Request
    </a>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.maintenance.index') }}" class="row g-3">
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
                <label class="form-label">Priority</label>
                <select name="priority" class="form-select">
                    <option value="">All Priorities</option>
                    <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low</option>
                    <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                    <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High</option>
                    <option value="critical" {{ request('priority') == 'critical' ? 'selected' : '' }}>Critical</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Category</label>
                <select name="category" class="form-select">
                    <option value="">All Categories</option>
                    <option value="plumbing" {{ request('category') == 'plumbing' ? 'selected' : '' }}>Plumbing</option>
                    <option value="electrical" {{ request('category') == 'electrical' ? 'selected' : '' }}>Electrical</option>
                    <option value="hvac" {{ request('category') == 'hvac' ? 'selected' : '' }}>HVAC</option>
                    <option value="furniture" {{ request('category') == 'furniture' ? 'selected' : '' }}>Furniture</option>
                    <option value="appliances" {{ request('category') == 'appliances' ? 'selected' : '' }}>Appliances</option>
                    <option value="general" {{ request('category') == 'general' ? 'selected' : '' }}>General</option>
                </select>
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <button type="submit" class="btn btn-primary me-2"><i class="bi bi-search"></i> Filter</button>
                <a href="{{ route('admin.maintenance.index') }}" class="btn btn-outline-secondary">Reset</a>
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
                        <th>#</th>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Location</th>
                        <th>Priority</th>
                        <th>Status</th>
                        <th>Assigned To</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($requests as $req)
                        <tr>
                            <td>{{ $req->id }}</td>
                            <td><strong>{{ $req->title }}</strong></td>
                            <td><span class="badge bg-light text-dark">{{ ucfirst($req->category) }}</span></td>
                            <td>{{ $req->location }}</td>
                            <td>
                                @switch($req->priority)
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
                            </td>
                            <td>
                                @switch($req->status)
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
                                        <span class="badge bg-secondary">{{ ucfirst($req->status) }}</span>
                                @endswitch
                            </td>
                            <td>{{ $req->assignee->name ?? 'Unassigned' }}</td>
                            <td>{{ $req->created_at->format('M d, Y') }}</td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.maintenance.show', $req->id) }}" class="btn btn-outline-primary" title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.maintenance.edit', $req->id) }}" class="btn btn-outline-secondary" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-4 text-muted">No maintenance requests found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer">
        {{ $requests->links() }}
    </div>
</div>
@endsection

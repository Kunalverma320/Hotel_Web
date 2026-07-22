@extends('admin.layouts.app')

@section('title', 'Leaves')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="fas fa-calendar-alt me-2"></i>Leave Management</h4>
    <a href="{{ route('admin.leaves.create') }}" class="btn btn-primary"><i class="fas fa-plus me-1"></i>Apply Leave</a>
</div>

<ul class="nav nav-tabs mb-3">
    <li class="nav-item">
        <a class="nav-link {{ !request('status') ? 'active' : '' }}" href="{{ route('admin.leaves.index') }}">All</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request('status') == 'pending' ? 'active' : '' }}" href="{{ route('admin.leaves.index', ['status' => 'pending']) }}">Pending</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request('status') == 'approved' ? 'active' : '' }}" href="{{ route('admin.leaves.index', ['status' => 'approved']) }}">Approved</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request('status') == 'rejected' ? 'active' : '' }}" href="{{ route('admin.leaves.index', ['status' => 'rejected']) }}">Rejected</a>
    </li>
</ul>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Employee</th>
                    <th>Type</th>
                    <th>From</th>
                    <th>To</th>
                    <th>Days</th>
                    <th>Reason</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($leaves as $leave)
                <tr>
                    <td><strong>{{ $leave->employee->first_name }} {{ $leave->employee->last_name }}</strong></td>
                    <td><span class="badge bg-info">{{ ucfirst($leave->leave_type) }}</span></td>
                    <td>{{ $leave->start_date->format('d M Y') }}</td>
                    <td>{{ $leave->end_date->format('d M Y') }}</td>
                    <td><strong>{{ $leave->days }}</strong></td>
                    <td>{{ Str::limit($leave->reason, 40) }}</td>
                    <td>
                        @if($leave->status == 'pending')
                            <span class="badge bg-warning text-dark">Pending</span>
                        @elseif($leave->status == 'approved')
                            <span class="badge bg-success">Approved</span>
                        @else
                            <span class="badge bg-danger">Rejected</span>
                        @endif
                    </td>
                    <td>
                        @if($leave->status == 'pending')
                        <div class="btn-group btn-group-sm">
                            <form action="{{ route('admin.leaves.approve', $leave->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-outline-success btn-sm" title="Approve"><i class="fas fa-check"></i></button>
                            </form>
                            <form action="{{ route('admin.leaves.reject', $leave->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger btn-sm" title="Reject"><i class="fas fa-times"></i></button>
                            </form>
                        </div>
                        @else
                        <span class="text-muted">-</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center py-4 text-muted">No leave records found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer">{{ $leaves->withQueryString()->links() }}</div>
</div>
@endsection

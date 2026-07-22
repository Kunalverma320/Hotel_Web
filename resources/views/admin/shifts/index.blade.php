@extends('admin.layouts.app')

@section('title', 'Shifts')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="fas fa-business-time me-2"></i>Shift Management</h4>
    <a href="{{ route('admin.shifts.create') }}" class="btn btn-primary"><i class="fas fa-plus me-1"></i>Add Shift</a>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header"><h5 class="mb-0">All Shifts</h5></div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Name</th>
                            <th>Start Time</th>
                            <th>End Time</th>
                            <th>Employees</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($shifts as $shift)
                        <tr>
                            <td><strong>{{ $shift->name }}</strong></td>
                            <td><span class="badge bg-success">{{ $shift->start_time->format('h:i A') }}</span></td>
                            <td><span class="badge bg-danger">{{ $shift->end_time->format('h:i A') }}</span></td>
                            <td><span class="badge bg-info">{{ $shift->employees_count }}</span></td>
                            <td>
                                @if($shift->status == 'active') <span class="badge bg-success">Active</span>
                                @else <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.shifts.edit', $shift->id) }}" class="btn btn-outline-primary"><i class="fas fa-edit"></i></a>
                                    <form action="{{ route('admin.shifts.destroy', $shift->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this shift?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger"><i class="fas fa-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="text-center py-4 text-muted">No shifts found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer">{{ $shifts->links() }}</div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header"><h5 class="mb-0">Assign Shift to Employee</h5></div>
            <div class="card-body">
                <form action="{{ route('admin.shifts.assign') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Employee <span class="text-danger">*</span></label>
                        <select name="employee_id" class="form-select" required>
                            <option value="">Select Employee</option>
                            @foreach($employees as $emp)
                                <option value="{{ $emp->id }}">{{ $emp->first_name }} {{ $emp->last_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Shift <span class="text-danger">*</span></label>
                        <select name="shift_id" class="form-select" required>
                            <option value="">Select Shift</option>
                            @foreach($shifts as $shift)
                                <option value="{{ $shift->id }}">{{ $shift->name }} ({{ $shift->start_time->format('h:i A') }} - {{ $shift->end_time->format('h:i A') }})</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100"><i class="fas fa-link me-1"></i>Assign Shift</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

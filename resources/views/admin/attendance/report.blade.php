@extends('admin.layouts.app')

@section('title', 'Attendance Report')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Attendance Report</h4>
    <a href="{{ route('admin.attendance.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i>Back</a>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.attendance.report') }}" class="row g-3 align-items-end">
            <div class="col-md-2">
                <label class="form-label">Start Date</label>
                <input type="date" name="start_date" class="form-control" value="{{ $startDate }}">
            </div>
            <div class="col-md-2">
                <label class="form-label">End Date</label>
                <input type="date" name="end_date" class="form-control" value="{{ $endDate }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">Employee</label>
                <select name="employee_id" class="form-select">
                    <option value="">All Employees</option>
                    @foreach($employees as $emp)
                        <option value="{{ $emp->id }}" {{ request('employee_id') == $emp->id ? 'selected' : '' }}>{{ $emp->first_name }} {{ $emp->last_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100"><i class="fas fa-filter me-1"></i>Generate</button>
            </div>
        </form>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-2">
        <div class="card text-center">
            <div class="card-body">
                <h5 class="text-success">{{ $summary->get('present', 0) }}</h5>
                <small class="text-muted">Present</small>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card text-center">
            <div class="card-body">
                <h5 class="text-danger">{{ $summary->get('absent', 0) }}</h5>
                <small class="text-muted">Absent</small>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card text-center">
            <div class="card-body">
                <h5 class="text-warning">{{ $summary->get('late', 0) }}</h5>
                <small class="text-muted">Late</small>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card text-center">
            <div class="card-body">
                <h5 class="text-info">{{ $summary->get('half_day', 0) }}</h5>
                <small class="text-muted">Half Day</small>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card text-center">
            <div class="card-body">
                <h5 class="text-secondary">{{ $summary->get('leave', 0) }}</h5>
                <small class="text-muted">On Leave</small>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card text-center">
            <div class="card-body">
                <h5>{{ $attendances->total() }}</h5>
                <small class="text-muted">Total Records</small>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Employee</th>
                    <th>Date</th>
                    <th>Clock In</th>
                    <th>Clock Out</th>
                    <th>Hours</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($attendances as $att)
                <tr>
                    <td>{{ $att->employee->first_name }} {{ $att->employee->last_name }}</td>
                    <td>{{ $att->date->format('d M Y') }}</td>
                    <td>{{ $att->clock_in ? $att->clock_in->format('h:i A') : '-' }}</td>
                    <td>{{ $att->clock_out ? $att->clock_out->format('h:i A') : '-' }}</td>
                    <td>{{ $att->hours_worked ? number_format($att->hours_worked, 2) . 'h' : '-' }}</td>
                    <td>
                        @if($att->status == 'present') <span class="badge bg-success">Present</span>
                        @elseif($att->status == 'absent') <span class="badge bg-danger">Absent</span>
                        @elseif($att->status == 'late') <span class="badge bg-warning text-dark">Late</span>
                        @else <span class="badge bg-secondary">{{ ucfirst(str_replace('_',' ',$att->status)) }}</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center py-4 text-muted">No records found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer">{{ $attendances->withQueryString()->links() }}</div>
</div>
@endsection

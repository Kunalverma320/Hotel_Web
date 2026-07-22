@extends('admin.layouts.app')

@section('title', 'Employee Attendance Report')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Attendance Report - {{ $employee->first_name }} {{ $employee->last_name }}</h4>
    <a href="{{ route('admin.attendance.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i>Back</a>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h4 class="text-success">{{ $summary->get('present', 0) }}</h4>
                <small class="text-muted">Present</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h4 class="text-danger">{{ $summary->get('absent', 0) }}</h4>
                <small class="text-muted">Absent</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h4 class="text-warning">{{ $summary->get('late', 0) }}</h4>
                <small class="text-muted">Late</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h4 class="text-info">{{ $summary->get('half_day', 0) }}</h4>
                <small class="text-muted">Half Day</small>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
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
                    <td>{{ $att->date->format('d M Y') }}</td>
                    <td>{{ $att->clock_in ? $att->clock_in->format('h:i A') : '-' }}</td>
                    <td>{{ $att->clock_out ? $att->clock_out->format('h:i A') : '-' }}</td>
                    <td>{{ $att->hours_worked ? number_format($att->hours_worked, 2) . 'h' : '-' }}</td>
                    <td>
                        @if($att->status == 'present') <span class="badge bg-success">Present</span>
                        @elseif($att->status == 'absent') <span class="badge bg-danger">Absent</span>
                        @elseif($att->status == 'late') <span class="badge bg-warning text-dark">Late</span>
                        @else <span class="badge bg-secondary">{{ ucfirst($att->status) }}</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center py-4 text-muted">No attendance records for this month.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

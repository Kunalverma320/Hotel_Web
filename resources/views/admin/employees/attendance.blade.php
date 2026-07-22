@extends('admin.layouts.app')

@section('title', 'Employee Attendance History')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="fas fa-clock me-2"></i>Attendance - {{ $employee->first_name }} {{ $employee->last_name }}</h4>
    <a href="{{ route('admin.employees.show', $employee->id) }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i>Back</a>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Date</th>
                    <th>Clock In</th>
                    <th>Clock Out</th>
                    <th>Hours Worked</th>
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
                <tr><td colspan="5" class="text-center py-4 text-muted">No attendance records found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer">{{ $attendances->links() }}</div>
</div>
@endsection

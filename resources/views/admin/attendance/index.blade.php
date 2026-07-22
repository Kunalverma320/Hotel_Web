@extends('admin.layouts.app')

@section('title', 'Attendance')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="fas fa-clock me-2"></i>Attendance</h4>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.attendance.report') }}" class="btn btn-outline-primary"><i class="fas fa-chart-bar me-1"></i>Report</a>
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#clockInModal"><i class="fas fa-sign-in-alt me-1"></i>Clock In</button>
        <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#clockOutModal"><i class="fas fa-sign-out-alt me-1"></i>Clock Out</button>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.attendance.index') }}" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label">Date</label>
                <input type="date" name="date" class="form-control" value="{{ request('date', $today) }}">
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
                <button type="submit" class="btn btn-outline-primary w-100"><i class="fas fa-filter me-1"></i>Filter</button>
            </div>
            <div class="col-md-2">
                <a href="{{ route('admin.attendance.index') }}" class="btn btn-outline-secondary w-100">Clear</a>
            </div>
        </form>
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
                    <th>Hours Worked</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($attendances as $att)
                <tr>
                    <td>
                        <strong>{{ $att->employee->first_name }} {{ $att->employee->last_name }}</strong>
                        <br><small class="text-muted">{{ $att->employee->employee_code }}</small>
                    </td>
                    <td>{{ $att->date->format('d M Y') }}</td>
                    <td>
                        @if($att->clock_in)
                            <span class="text-success fw-bold">{{ $att->clock_in->format('h:i A') }}</span>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>
                        @if($att->clock_out)
                            <span class="text-danger fw-bold">{{ $att->clock_out->format('h:i A') }}</span>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>
                        @if($att->hours_worked)
                            <span class="badge bg-info">{{ number_format($att->hours_worked, 2) }}h</span>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>
                        @if($att->status == 'present') <span class="badge bg-success">Present</span>
                        @elseif($att->status == 'absent') <span class="badge bg-danger">Absent</span>
                        @elseif($att->status == 'late') <span class="badge bg-warning text-dark">Late</span>
                        @elseif($att->status == 'half_day') <span class="badge bg-info">Half Day</span>
                        @elseif($att->status == 'leave') <span class="badge bg-secondary">Leave</span>
                        @else <span class="badge bg-secondary">{{ ucfirst($att->status) }}</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center py-4 text-muted">No attendance records found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer">{{ $attendances->withQueryString()->links() }}</div>
</div>

{{-- Clock In Modal --}}
<div class="modal fade" id="clockInModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title"><i class="fas fa-sign-in-alt me-2"></i>Clock In</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.attendance.store') }}" method="POST">
                @csrf
                <input type="hidden" name="type" value="clock_in">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Select Employee</label>
                        <select name="employee_id" class="form-select" required>
                            <option value="">-- Select Employee --</option>
                            @foreach($employees as $emp)
                                <option value="{{ $emp->id }}">{{ $emp->first_name }} {{ $emp->last_name }} ({{ $emp->employee_code }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="text-center py-3">
                        <i class="fas fa-clock text-success" style="font-size:3rem;"></i>
                        <p class="text-muted mt-2">Current time: <span id="clockInTime"></span></p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success"><i class="fas fa-sign-in-alt me-1"></i>Clock In</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Clock Out Modal --}}
<div class="modal fade" id="clockOutModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title"><i class="fas fa-sign-out-alt me-2"></i>Clock Out</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.attendance.store') }}" method="POST">
                @csrf
                <input type="hidden" name="type" value="clock_out">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Select Employee</label>
                        <select name="employee_id" class="form-select" required>
                            <option value="">-- Select Employee --</option>
                            @foreach($employees as $emp)
                                <option value="{{ $emp->id }}">{{ $emp->first_name }} {{ $emp->last_name }} ({{ $emp->employee_code }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="text-center py-3">
                        <i class="fas fa-clock text-warning" style="font-size:3rem;"></i>
                        <p class="text-muted mt-2">Current time: <span id="clockOutTime"></span></p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning"><i class="fas fa-sign-out-alt me-1"></i>Clock Out</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function updateClocks() {
    const now = new Date().toLocaleTimeString();
    const el1 = document.getElementById('clockInTime');
    const el2 = document.getElementById('clockOutTime');
    if (el1) el1.textContent = now;
    if (el2) el2.textContent = now;
}
updateClocks();
setInterval(updateClocks, 1000);
</script>
@endpush

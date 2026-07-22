@extends('admin.layouts.app')

@section('title', 'Employee Profile')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="fas fa-user me-2"></i>{{ $employee->first_name }} {{ $employee->last_name }}</h4>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.employees.edit', $employee->id) }}" class="btn btn-primary"><i class="fas fa-edit me-1"></i>Edit</a>
        <a href="{{ route('admin.employees.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i>Back</a>
    </div>
</div>

<div class="row">
    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-body text-center">
                @if($employee->photo)
                    <img src="{{ asset('storage/' . $employee->photo) }}" class="rounded-circle mb-3" width="120" height="120" style="object-fit:cover;">
                @else
                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width:120px;height:120px;font-size:2.5rem;">
                        {{ strtoupper(substr($employee->first_name, 0, 1)) }}
                    </div>
                @endif
                <h5>{{ $employee->first_name }} {{ $employee->last_name }}</h5>
                <p class="text-muted">{{ $employee->employee_code }}</p>
                @if($employee->status == 'active')
                    <span class="badge bg-success fs-6">Active</span>
                @elseif($employee->status == 'inactive')
                    <span class="badge bg-warning text-dark fs-6">Inactive</span>
                @else
                    <span class="badge bg-danger fs-6">Terminated</span>
                @endif
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header"><h6 class="mb-0">Personal Details</h6></div>
            <div class="card-body">
                <table class="table table-borderless table-sm mb-0">
                    <tr><td class="text-muted" style="width:40%">Email</td><td>{{ $employee->email }}</td></tr>
                    <tr><td class="text-muted">Phone</td><td>{{ $employee->phone }}</td></tr>
                    <tr><td class="text-muted">Gender</td><td>{{ ucfirst($employee->gender) }}</td></tr>
                    <tr><td class="text-muted">DOB</td><td>{{ $employee->date_of_birth ? $employee->date_of_birth->format('d M Y') : '-' }}</td></tr>
                    <tr><td class="text-muted">Address</td><td>{{ $employee->address ?? '-' }}</td></tr>
                </table>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header"><h6 class="mb-0">Bank Details</h6></div>
            <div class="card-body">
                <table class="table table-borderless table-sm mb-0">
                    <tr><td class="text-muted" style="width:40%">Bank</td><td>{{ $employee->bank_name ?? '-' }}</td></tr>
                    <tr><td class="text-muted">Account</td><td>{{ $employee->bank_account_number ?? '-' }}</td></tr>
                    <tr><td class="text-muted">IFSC</td><td>{{ $employee->bank_ifsc ?? '-' }}</td></tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <ul class="nav nav-tabs mb-3" id="profileTabs">
            <li class="nav-item">
                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#overview">Overview</button>
            </li>
            <li class="nav-item">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#attendance">Attendance</button>
            </li>
            <li class="nav-item">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#leaves">Leaves</button>
            </li>
            <li class="nav-item">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#payroll">Payroll</button>
            </li>
            <li class="nav-item">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#documents">Documents</button>
            </li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane fade show active" id="overview">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h6 class="text-muted">Department</h6>
                                <h5>{{ $employee->department->name ?? '-' }}</h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h6 class="text-muted">Designation</h6>
                                <h5>{{ $employee->designation->name ?? '-' }}</h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h6 class="text-muted">Employment Type</h6>
                                <h5>{{ ucfirst($employee->employment_type) }}</h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h6 class="text-muted">Salary</h6>
                                <h5>${{ number_format($employee->salary, 2) }}</h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h6 class="text-muted">Date of Joining</h6>
                                <h5>{{ $employee->date_of_joining ? $employee->date_of_joining->format('d M Y') : '-' }}</h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h6 class="text-muted">Employee Code</h6>
                                <h5>{{ $employee->employee_code }}</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="attendance">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr><th>Date</th><th>Clock In</th><th>Clock Out</th><th>Hours</th><th>Status</th></tr>
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
                            <tr><td colspan="5" class="text-center text-muted">No attendance records.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="tab-pane fade" id="leaves">
                <p class="text-muted">Leave records will appear here.</p>
            </div>

            <div class="tab-pane fade" id="payroll">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr><th>Period</th><th>Basic</th><th>Deductions</th><th>Bonuses</th><th>Net</th><th>Status</th></tr>
                        </thead>
                        <tbody>
                            @forelse($payrolls as $pay)
                            <tr>
                                <td>{{ \Carbon\Carbon::create($pay->year, $pay->month)->format('M Y') }}</td>
                                <td>${{ number_format($pay->basic_salary, 2) }}</td>
                                <td>${{ number_format($pay->deductions, 2) }}</td>
                                <td>${{ number_format($pay->bonuses, 2) }}</td>
                                <td><strong>${{ number_format($pay->net_salary, 2) }}</strong></td>
                                <td>
                                    @if($pay->status == 'paid') <span class="badge bg-success">Paid</span>
                                    @else <span class="badge bg-warning text-dark">Pending</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="6" class="text-center text-muted">No payroll records.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="tab-pane fade" id="documents">
                <p class="text-muted">Employee documents will appear here.</p>
            </div>
        </div>
    </div>
</div>
@endsection

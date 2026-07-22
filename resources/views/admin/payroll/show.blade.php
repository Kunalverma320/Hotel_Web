@extends('admin.layouts.app')

@section('title', 'Payroll Details')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="fas fa-receipt me-2"></i>Payroll Details</h4>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.payroll.payslip', $payroll->id) }}" class="btn btn-outline-secondary" target="_blank"><i class="fas fa-print me-1"></i>Print Payslip</a>
        @if($payroll->status == 'pending')
        <form action="{{ route('admin.payroll.mark-paid', $payroll->id) }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-success"><i class="fas fa-check-circle me-1"></i>Mark as Paid</button>
        </form>
        @endif
        <a href="{{ route('admin.payroll.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i>Back</a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header"><h5 class="mb-0">Employee Information</h5></div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless table-sm mb-0">
                            <tr><td class="text-muted" style="width:140px;">Name:</td><td><strong>{{ $payroll->employee->first_name }} {{ $payroll->employee->last_name }}</strong></td></tr>
                            <tr><td class="text-muted">Code:</td><td>{{ $payroll->employee->employee_code }}</td></tr>
                            <tr><td class="text-muted">Department:</td><td>{{ $payroll->employee->department->name ?? '-' }}</td></tr>
                            <tr><td class="text-muted">Designation:</td><td>{{ $payroll->employee->designation->name ?? '-' }}</td></tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless table-sm mb-0">
                            <tr><td class="text-muted" style="width:140px;">Period:</td><td>{{ \Carbon\Carbon::create($payroll->year, $payroll->month)->format('F Y') }}</td></tr>
                            <tr><td class="text-muted">Status:</td><td><span class="badge {{ $payroll->status == 'paid' ? 'bg-success' : 'bg-warning text-dark' }}">{{ ucfirst($payroll->status) }}</span></td></tr>
                            <tr><td class="text-muted">Paid On:</td><td>{{ $payroll->paid_at ? $payroll->paid_at->format('d M Y, h:i A') : 'Not yet paid' }}</td></tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header"><h5 class="mb-0">Salary Breakdown</h5></div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-success"><i class="fas fa-arrow-up me-1"></i>Earnings</h6>
                        <table class="table table-bordered table-sm">
                            <tr><td>Basic Salary</td><td class="text-end">${{ number_format($payroll->basic_salary, 2) }}</td></tr>
                            <tr><td>Bonuses</td><td class="text-end text-success">${{ number_format($payroll->bonuses, 2) }}</td></tr>
                            <tr class="table-light"><td><strong>Total Earnings</strong></td><td class="text-end"><strong>${{ number_format($payroll->basic_salary + $payroll->bonuses, 2) }}</strong></td></tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-danger"><i class="fas fa-arrow-down me-1"></i>Deductions</h6>
                        <table class="table table-bordered table-sm">
                            <tr><td>Total Deductions</td><td class="text-end">${{ number_format($payroll->deductions, 2) }}</td></tr>
                            <tr class="table-light"><td><strong>Total Deductions</strong></td><td class="text-end"><strong>${{ number_format($payroll->deductions, 2) }}</strong></td></tr>
                        </table>
                    </div>
                </div>
                <hr>
                <div class="text-center">
                    <h4>Net Salary: <span class="text-primary">${{ number_format($payroll->net_salary, 2) }}</span></h4>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header"><h5 class="mb-0">Quick Actions</h5></div>
            <div class="card-body d-grid gap-2">
                <a href="{{ route('admin.payroll.payslip', $payroll->id) }}" class="btn btn-outline-primary" target="_blank"><i class="fas fa-file-invoice me-1"></i>View Payslip</a>
                <a href="{{ route('admin.employees.show', $payroll->employee_id) }}" class="btn btn-outline-info"><i class="fas fa-user me-1"></i>View Employee</a>
                @if($payroll->status == 'pending')
                <form action="{{ route('admin.payroll.mark-paid', $payroll->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-success w-100"><i class="fas fa-check-circle me-1"></i>Mark as Paid</button>
                </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

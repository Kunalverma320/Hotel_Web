<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payslip - {{ $payroll->employee->first_name }} {{ $payroll->employee->last_name }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @media print { .no-print { display: none !important; } body { font-size: 12px; } }
        .payslip-header { border-bottom: 3px solid #0d6efd; }
        .payslip-footer { border-top: 2px solid #dee2e6; }
    </style>
</head>
<body>
    <div class="container py-4">
        <div class="no-print mb-3 text-end">
            <button onclick="window.print()" class="btn btn-primary"><i class="fas fa-print me-1"></i>Print Payslip</button>
            <button onclick="window.close()" class="btn btn-secondary">Close</button>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="row payslip-header pb-3 mb-4">
                    <div class="col-6">
                        <h3 class="text-primary mb-0">{{ config('app.name', 'Hotel Management') }}</h3>
                        <small class="text-muted">Payroll Department</small>
                    </div>
                    <div class="col-6 text-end">
                        <h5 class="mb-0">PAYSLIP</h5>
                        <small class="text-muted">{{ \Carbon\Carbon::create($payroll->year, $payroll->month)->format('F Y') }}</small>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-6">
                        <table class="table table-borderless table-sm">
                            <tr><td class="text-muted" style="width:120px;">Employee Name:</td><td><strong>{{ $payroll->employee->first_name }} {{ $payroll->employee->last_name }}</strong></td></tr>
                            <tr><td class="text-muted">Employee Code:</td><td>{{ $payroll->employee->employee_code }}</td></tr>
                            <tr><td class="text-muted">Department:</td><td>{{ $payroll->employee->department->name ?? '-' }}</td></tr>
                            <tr><td class="text-muted">Designation:</td><td>{{ $payroll->employee->designation->name ?? '-' }}</td></tr>
                        </table>
                    </div>
                    <div class="col-6">
                        <table class="table table-borderless table-sm">
                            <tr><td class="text-muted" style="width:120px;">Pay Period:</td><td>{{ \Carbon\Carbon::create($payroll->year, $payroll->month)->format('F Y') }}</td></tr>
                            <tr><td class="text-muted">Status:</td><td><span class="badge {{ $payroll->status == 'paid' ? 'bg-success' : 'bg-warning' }}">{{ ucfirst($payroll->status) }}</span></td></tr>
                            <tr><td class="text-muted">Paid On:</td><td>{{ $payroll->paid_at ? $payroll->paid_at->format('d M Y') : '-' }}</td></tr>
                        </table>
                    </div>
                </div>

                <div class="row">
                    <div class="col-6">
                        <h6 class="text-primary mb-2">Earnings</h6>
                        <table class="table table-bordered table-sm">
                            <tbody>
                                <tr><td>Basic Salary</td><td class="text-end">${{ number_format($payroll->basic_salary, 2) }}</td></tr>
                                <tr><td>Bonuses</td><td class="text-end text-success">${{ number_format($payroll->bonuses, 2) }}</td></tr>
                                <tr class="table-light"><td><strong>Total Earnings</strong></td><td class="text-end"><strong>${{ number_format($payroll->basic_salary + $payroll->bonuses, 2) }}</strong></td></tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-6">
                        <h6 class="text-danger mb-2">Deductions</h6>
                        <table class="table table-bordered table-sm">
                            <tbody>
                                <tr><td>Total Deductions</td><td class="text-end">${{ number_format($payroll->deductions, 2) }}</td></tr>
                                <tr class="table-light"><td><strong>Total Deductions</strong></td><td class="text-end"><strong>${{ number_format($payroll->deductions, 2) }}</strong></td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="payslip-footer pt-3 mt-3">
                    <div class="row">
                        <div class="col-6">
                            <h5>Net Salary: <span class="text-primary">${{ number_format($payroll->net_salary, 2) }}</span></h5>
                        </div>
                        <div class="col-6 text-end">
                            <small class="text-muted">This is a computer-generated payslip and does not require a signature.</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

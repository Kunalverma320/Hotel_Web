@extends('admin.layouts.app')

@section('title', 'Employee Payroll History')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="fas fa-money-check-alt me-2"></i>Payroll - {{ $employee->first_name }} {{ $employee->last_name }}</h4>
    <a href="{{ route('admin.employees.show', $employee->id) }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i>Back</a>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Period</th>
                    <th>Basic Salary</th>
                    <th>Deductions</th>
                    <th>Bonuses</th>
                    <th>Net Salary</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payrolls as $pay)
                <tr>
                    <td>{{ \Carbon\Carbon::create($pay->year, $pay->month)->format('M Y') }}</td>
                    <td>${{ number_format($pay->basic_salary, 2) }}</td>
                    <td class="text-danger">${{ number_format($pay->deductions, 2) }}</td>
                    <td class="text-success">${{ number_format($pay->bonuses, 2) }}</td>
                    <td><strong>${{ number_format($pay->net_salary, 2) }}</strong></td>
                    <td>
                        @if($pay->status == 'paid') <span class="badge bg-success">Paid</span>
                        @else <span class="badge bg-warning text-dark">Pending</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.payroll.payslip', $pay->id) }}" class="btn btn-sm btn-outline-info" target="_blank"><i class="fas fa-print"></i></a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center py-4 text-muted">No payroll records found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer">{{ $payrolls->links() }}</div>
</div>
@endsection

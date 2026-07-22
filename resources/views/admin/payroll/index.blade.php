@extends('admin.layouts.app')

@section('title', 'Payroll')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="fas fa-money-check-alt me-2"></i>Payroll Management</h4>
    <a href="{{ route('admin.payroll.generate') }}" class="btn btn-primary"><i class="fas fa-cog me-1"></i>Generate Payroll</a>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.payroll.index') }}" class="row g-3 align-items-end">
            <div class="col-md-2">
                <label class="form-label">Month</label>
                <select name="month" class="form-select">
                    @for($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" {{ request('month', $currentMonth) == $m ? 'selected' : '' }}>{{ \Carbon\Carbon::create()->month($m)->format('F') }}</option>
                    @endfor
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Year</label>
                <select name="year" class="form-select">
                    @for($y = date('Y'); $y >= date('Y') - 5; $y--)
                        <option value="{{ $y }}" {{ request('year', $currentYear) == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">All</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-outline-primary w-100"><i class="fas fa-filter me-1"></i>Filter</button>
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
                    <td><strong>{{ $pay->employee->first_name }} {{ $pay->employee->last_name }}</strong></td>
                    <td>{{ \Carbon\Carbon::create($pay->year, $pay->month)->format('M Y') }}</td>
                    <td>${{ number_format($pay->basic_salary, 2) }}</td>
                    <td class="text-danger">${{ number_format($pay->deductions, 2) }}</td>
                    <td class="text-success">${{ number_format($pay->bonuses, 2) }}</td>
                    <td><strong>${{ number_format($pay->net_salary, 2) }}</strong></td>
                    <td>
                        @if($pay->status == 'paid')
                            <span class="badge bg-success">Paid</span>
                        @else
                            <span class="badge bg-warning text-dark">Pending</span>
                        @endif
                    </td>
                    <td>
                        <div class="btn-group btn-group-sm">
                            <a href="{{ route('admin.payroll.show', $pay->id) }}" class="btn btn-outline-info" title="View"><i class="fas fa-eye"></i></a>
                            <a href="{{ route('admin.payroll.payslip', $pay->id) }}" class="btn btn-outline-secondary" title="Payslip" target="_blank"><i class="fas fa-print"></i></a>
                            @if($pay->status == 'pending')
                            <form action="{{ route('admin.payroll.mark-paid', $pay->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-outline-success btn-sm" title="Mark Paid"><i class="fas fa-check-circle"></i></button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center py-4 text-muted">No payroll records found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer">{{ $payrolls->withQueryString()->links() }}</div>
</div>
@endsection

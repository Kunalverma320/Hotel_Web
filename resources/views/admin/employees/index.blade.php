@extends('admin.layouts.app')

@section('title', 'Employees')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="fas fa-users me-2"></i>Employees</h4>
    <a href="{{ route('admin.employees.create') }}" class="btn btn-primary"><i class="fas fa-plus me-1"></i>Add Employee</a>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.employees.index') }}" class="row g-3">
            <div class="col-md-2">
                <input type="text" name="search" class="form-control" placeholder="Search..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <select name="department_id" class="form-select">
                    <option value="">All Departments</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="designation_id" class="form-select">
                    <option value="">All Designations</option>
                    @foreach($designations as $des)
                        <option value="{{ $des->id }}" {{ request('designation_id') == $des->id ? 'selected' : '' }}>{{ $des->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    <option value="terminated" {{ request('status') == 'terminated' ? 'selected' : '' }}>Terminated</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-outline-primary w-100"><i class="fas fa-filter me-1"></i>Filter</button>
            </div>
            <div class="col-md-2">
                <a href="{{ route('admin.employees.index') }}" class="btn btn-outline-secondary w-100"><i class="fas fa-times me-1"></i>Clear</a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Photo</th>
                    <th>Name</th>
                    <th>Code</th>
                    <th>Department</th>
                    <th>Designation</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($employees as $employee)
                <tr>
                    <td>
                        @if($employee->photo)
                            <img src="{{ asset('storage/' . $employee->photo) }}" class="rounded-circle" width="40" height="40" style="object-fit:cover;">
                        @else
                            <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center" style="width:40px;height:40px;">
                                {{ strtoupper(substr($employee->first_name, 0, 1)) }}
                            </div>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.employees.show', $employee->id) }}" class="text-decoration-none fw-semibold">
                            {{ $employee->first_name }} {{ $employee->last_name }}
                        </a>
                        <br><small class="text-muted">{{ $employee->email }}</small>
                    </td>
                    <td><span class="badge bg-light text-dark">{{ $employee->employee_code }}</span></td>
                    <td>{{ $employee->department->name ?? '-' }}</td>
                    <td>{{ $employee->designation->name ?? '-' }}</td>
                    <td>
                        @if($employee->status == 'active')
                            <span class="badge bg-success">Active</span>
                        @elseif($employee->status == 'inactive')
                            <span class="badge bg-warning text-dark">Inactive</span>
                        @else
                            <span class="badge bg-danger">Terminated</span>
                        @endif
                    </td>
                    <td>
                        <div class="btn-group btn-group-sm">
                            <a href="{{ route('admin.employees.show', $employee->id) }}" class="btn btn-outline-info" title="View"><i class="fas fa-eye"></i></a>
                            <a href="{{ route('admin.employees.edit', $employee->id) }}" class="btn btn-outline-primary" title="Edit"><i class="fas fa-edit"></i></a>
                            <form action="{{ route('admin.employees.destroy', $employee->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger" title="Delete"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-4 text-muted">No employees found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer">
        {{ $employees->withQueryString()->links() }}
    </div>
</div>
@endsection

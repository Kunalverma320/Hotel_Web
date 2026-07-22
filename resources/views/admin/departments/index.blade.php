@extends('admin.layouts.app')

@section('title', 'Departments')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="fas fa-building me-2"></i>Departments</h4>
    <a href="{{ route('admin.departments.create') }}" class="btn btn-primary"><i class="fas fa-plus me-1"></i>Add Department</a>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Employees</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($departments as $dept)
                <tr>
                    <td>{{ $dept->id }}</td>
                    <td><strong>{{ $dept->name }}</strong></td>
                    <td>{{ Str::limit($dept->description, 60) ?? '-' }}</td>
                    <td><span class="badge bg-info">{{ $dept->employees_count }}</span></td>
                    <td>
                        @if($dept->status == 'active')
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-secondary">Inactive</span>
                        @endif
                    </td>
                    <td>
                        <div class="btn-group btn-group-sm">
                            <a href="{{ route('admin.departments.edit', $dept->id) }}" class="btn btn-outline-primary"><i class="fas fa-edit"></i></a>
                            <form action="{{ route('admin.departments.destroy', $dept->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this department?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center py-4 text-muted">No departments found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer">{{ $departments->links() }}</div>
</div>
@endsection

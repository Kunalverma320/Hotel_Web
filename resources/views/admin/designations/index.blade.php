@extends('admin.layouts.app')

@section('title', 'Designations')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="fas fa-id-badge me-2"></i>Designations</h4>
    <a href="{{ route('admin.designations.create') }}" class="btn btn-primary"><i class="fas fa-plus me-1"></i>Add Designation</a>
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
                @forelse($designations as $des)
                <tr>
                    <td>{{ $des->id }}</td>
                    <td><strong>{{ $des->name }}</strong></td>
                    <td>{{ Str::limit($des->description, 60) ?? '-' }}</td>
                    <td><span class="badge bg-info">{{ $des->employees_count }}</span></td>
                    <td>
                        @if($des->status == 'active')
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-secondary">Inactive</span>
                        @endif
                    </td>
                    <td>
                        <div class="btn-group btn-group-sm">
                            <a href="{{ route('admin.designations.edit', $des->id) }}" class="btn btn-outline-primary"><i class="fas fa-edit"></i></a>
                            <form action="{{ route('admin.designations.destroy', $des->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this designation?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center py-4 text-muted">No designations found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer">{{ $designations->links() }}</div>
</div>
@endsection

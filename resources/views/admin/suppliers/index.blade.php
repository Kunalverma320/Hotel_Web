@extends('admin.layouts.app')

@section('title', 'Suppliers')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Suppliers</h4>
    <a href="{{ route('admin.suppliers.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i> Add Supplier
    </a>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.suppliers.index') }}" class="row g-3">
            <div class="col-md-6">
                <input type="text" name="search" class="form-control" placeholder="Search name, contact, email..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-outline-primary w-100">
                    <i class="fas fa-search me-1"></i> Search
                </button>
            </div>
            <div class="col-md-2">
                <a href="{{ route('admin.suppliers.index') }}" class="btn btn-outline-secondary w-100">Clear</a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body table-responsive">
        <table class="table table-hover table-striped align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Name</th>
                    <th>Contact Person</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>City</th>
                    <th>Status</th>
                    <th width="140">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($suppliers as $supplier)
                    <tr>
                        <td>
                            <a href="{{ route('admin.suppliers.show', $supplier->id) }}" class="fw-bold">
                                {{ $supplier->name }}
                            </a>
                        </td>
                        <td>{{ $supplier->contact_person ?? '-' }}</td>
                        <td>{{ $supplier->email ?? '-' }}</td>
                        <td>{{ $supplier->phone ?? '-' }}</td>
                        <td>{{ $supplier->city ?? '-' }}</td>
                        <td>
                            <span class="badge bg-{{ $supplier->status === 'active' ? 'success' : 'secondary' }}">
                                {{ ucfirst($supplier->status) }}
                            </span>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.suppliers.show', $supplier->id) }}" class="btn btn-outline-info" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.suppliers.edit', $supplier->id) }}" class="btn btn-outline-primary" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.suppliers.destroy', $supplier->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this supplier?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">No suppliers found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        {{ $suppliers->withQueryString()->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection

@extends('admin.layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Companies</h4>
    <a href="{{ route('admin.companies.create') }}" class="btn btn-primary">
        <i class="ri-add-line me-1"></i> Add Company
    </a>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Company List</h5>
        <form action="{{ route('admin.companies.index') }}" method="GET" class="d-flex gap-2">
            <input type="text" name="search" class="form-control form-control-sm" placeholder="Search companies..." value="{{ request('search') }}">
            <select name="status" class="form-select form-select-sm" style="width: 150px;">
                <option value="">All Status</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
            <button type="submit" class="btn btn-sm btn-outline-primary"><i class="ri-search-line"></i></button>
            <a href="{{ route('admin.companies.index') }}" class="btn btn-sm btn-outline-secondary"><i class="ri-refresh-line"></i></a>
        </form>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover table-striped align-middle">
                <thead class="table-dark">
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th>Logo</th>
                        <th>Name</th>
                        <th>GST No.</th>
                        <th>PAN No.</th>
                        <th>City</th>
                        <th>Phone</th>
                        <th>Status</th>
                        <th style="width: 150px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($companies as $company)
                    <tr>
                        <td>{{ $companies->firstItem() + $loop->index }}</td>
                        <td>
                            @if($company->logo)
                                <img src="{{ asset('storage/' . $company->logo) }}" alt="{{ $company->name }}" class="rounded" style="width: 40px; height: 40px; object-fit: cover;">
                            @else
                                <div class="bg-secondary bg-opacity-25 rounded d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                    <i class="ri-building-line text-muted"></i>
                                </div>
                            @endif
                        </td>
                        <td>
                            <strong>{{ $company->name }}</strong>
                            <br><small class="text-muted">{{ $company->email ?? '-' }}</small>
                        </td>
                        <td>{{ $company->gst_number ?? '-' }}</td>
                        <td>{{ $company->pan_number ?? '-' }}</td>
                        <td>{{ $company->city ?? '-' }}</td>
                        <td>{{ $company->phone ?? '-' }}</td>
                        <td>
                            @if($company->status == 'active')
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-danger">Inactive</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.companies.show', $company->id) }}" class="btn btn-outline-info" title="View"><i class="ri-eye-line"></i></a>
                                <a href="{{ route('admin.companies.edit', $company->id) }}" class="btn btn-outline-warning" title="Edit"><i class="ri-edit-line"></i></a>
                                <form action="{{ route('admin.companies.destroy', $company->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this company?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger" title="Delete"><i class="ri-delete-bin-line"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-4">
                            <i class="ri-building-line fs-1 text-muted d-block mb-2"></i>
                            No companies found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-end">
            {{ $companies->withQueryString()->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection

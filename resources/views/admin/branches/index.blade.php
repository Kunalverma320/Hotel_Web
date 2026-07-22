@extends('admin.layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Branches</h4>
    <a href="{{ route('admin.branches.create') }}" class="btn btn-primary">
        <i class="ri-add-line me-1"></i> Add Branch
    </a>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Branch List</h5>
        <form action="{{ route('admin.branches.index') }}" method="GET" class="d-flex gap-2">
            <input type="text" name="search" class="form-control form-control-sm" placeholder="Search branches..." value="{{ request('search') }}">
            <select name="company_id" class="form-select form-select-sm" style="width: 180px;">
                <option value="">All Companies</option>
                @foreach(\App\Models\Company::orderBy('name')->get() as $company)
                    <option value="{{ $company->id }}" {{ request('company_id') == $company->id ? 'selected' : '' }}>{{ $company->name }}</option>
                @endforeach
            </select>
            <select name="status" class="form-select form-select-sm" style="width: 130px;">
                <option value="">All Status</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
            <button type="submit" class="btn btn-sm btn-outline-primary"><i class="ri-search-line"></i></button>
            <a href="{{ route('admin.branches.index') }}" class="btn btn-sm btn-outline-secondary"><i class="ri-refresh-line"></i></a>
        </form>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover table-striped align-middle">
                <thead class="table-dark">
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th>Name</th>
                        <th>Code</th>
                        <th>Company</th>
                        <th>City</th>
                        <th>Phone</th>
                        <th>Manager</th>
                        <th>Status</th>
                        <th style="width: 150px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($branches as $branch)
                    <tr>
                        <td>{{ $branches->firstItem() + $loop->index }}</td>
                        <td><strong>{{ $branch->name }}</strong></td>
                        <td><code>{{ $branch->code }}</code></td>
                        <td>{{ $branch->company->name ?? '-' }}</td>
                        <td>{{ $branch->city ?? '-' }}</td>
                        <td>{{ $branch->phone ?? '-' }}</td>
                        <td>{{ $branch->manager->name ?? '-' }}</td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-{{ $branch->status == 'active' ? 'success' : 'danger' }} dropdown-toggle" data-bs-toggle="dropdown">
                                    {{ ucfirst($branch->status) }}
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="{{ route('admin.branches.update-status', [$branch->id, 'active']) }}" onclick="event.preventDefault(); document.getElementById('status-form-{{ $branch->id }}-active').submit();">Active</a></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.branches.update-status', [$branch->id, 'inactive']) }}" onclick="event.preventDefault(); document.getElementById('status-form-{{ $branch->id }}-inactive').submit();">Inactive</a></li>
                                </ul>
                                <form id="status-form-{{ $branch->id }}-active" action="{{ route('admin.branches.update-status', [$branch->id, 'active']) }}" method="POST" class="d-none">@csrf @method('PATCH')</form>
                                <form id="status-form-{{ $branch->id }}-inactive" action="{{ route('admin.branches.update-status', [$branch->id, 'inactive']) }}" method="POST" class="d-none">@csrf @method('PATCH')</form>
                            </div>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.branches.show', $branch->id) }}" class="btn btn-outline-info" title="View"><i class="ri-eye-line"></i></a>
                                <a href="{{ route('admin.branches.edit', $branch->id) }}" class="btn btn-outline-warning" title="Edit"><i class="ri-edit-line"></i></a>
                                <form action="{{ route('admin.branches.destroy', $branch->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this branch?')">
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
                            <i class="ri-building-2-line fs-1 text-muted d-block mb-2"></i>
                            No branches found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-end">
            {{ $branches->withQueryString()->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection

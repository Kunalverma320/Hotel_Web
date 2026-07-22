@extends('admin.layouts.app')
@section('title', 'Audit Log')

@section('content')
<div class="mb-4">
    <h1 class="h3 mb-0"><i class="bi bi-journal-text"></i> Audit Log</h1>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.audit.index') }}">
            <div class="row g-3 align-items-end">
                <div class="col-md-2">
                    <label class="form-label">User</label>
                    <select name="user_id" class="form-select">
                        <option value="">All Users</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Module</label>
                    <select name="module" class="form-select">
                        <option value="">All Modules</option>
                        <option value="bookings">Bookings</option>
                        <option value="guests">Guests</option>
                        <option value="rooms">Rooms</option>
                        <option value="payments">Payments</option>
                        <option value="settings">Settings</option>
                        <option value="users">Users</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Action</label>
                    <select name="action" class="form-select">
                        <option value="">All Actions</option>
                        <option value="create">Create</option>
                        <option value="update">Update</option>
                        <option value="delete">Delete</option>
                        <option value="login">Login</option>
                        <option value="logout">Logout</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">From</label>
                    <input type="date" name="start_date" class="form-control" value="{{ $startDate ?? '' }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">To</label>
                    <input type="date" name="end_date" class="form-control" value="{{ $endDate ?? '' }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100"><i class="bi bi-search"></i> Filter</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover" id="auditTable">
                <thead>
                    <tr>
                        <th style="width:30px"></th>
                        <th>Date/Time</th>
                        <th>User</th>
                        <th>Module</th>
                        <th>Action</th>
                        <th>Model</th>
                        <th>IP Address</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="7" class="text-center text-muted">No audit log records found.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.querySelectorAll('#auditTable tbody tr[data-id]').forEach(row => {
    row.querySelector('.toggle-detail')?.addEventListener('click', function() {
        const detailRow = document.getElementById('detail-' + this.dataset.id);
        if (detailRow) detailRow.classList.toggle('d-none');
    });
});
</script>
@endpush
@endsection

@extends('admin.layouts.app')
@section('title', 'Login History')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0"><i class="bi bi-clock-history"></i> Login History</h1>
    <a href="{{ route('admin.security.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Back</a>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.security.login-history') }}">
            <div class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label">User</label>
                    <select name="user_id" class="form-select">
                        <option value="">All Users</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">All</option>
                        <option value="success">Success</option>
                        <option value="failed">Failed</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">From</label>
                    <input type="date" name="start_date" class="form-control">
                </div>
                <div class="col-md-2">
                    <label class="form-label">To</label>
                    <input type="date" name="end_date" class="form-control">
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i> Filter</button>
                    <a href="{{ route('admin.security.login-history') }}" class="btn btn-outline-secondary">Reset</a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr><th>Date/Time</th><th>User</th><th>IP Address</th><th>Browser/Device</th><th>Status</th><th>Details</th></tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="6" class="text-center text-muted">No login history records found.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

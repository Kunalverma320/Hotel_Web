@extends('admin.layouts.app')
@section('title', 'Security Dashboard')

@section('content')
<div class="mb-4">
    <h1 class="h3 mb-0"><i class="bi bi-shield-lock"></i> Security Dashboard</h1>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center">
                <div class="mb-2"><i class="bi bi-shield-check text-success fs-1"></i></div>
                <h6 class="text-muted">Two-Factor Auth</h6>
                <span class="badge bg-success">Enabled</span>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center">
                <div class="mb-2"><i class="bi bi-laptop text-primary fs-1"></i></div>
                <h6 class="text-muted">Active Sessions</h6>
                <h3>--</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center">
                <div class="mb-2"><i class="bi bi-person-check text-info fs-1"></i></div>
                <h6 class="text-muted">Recent Logins (7d)</h6>
                <h3>--</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center">
                <div class="mb-2"><i class="bi bi-shield-exclamation text-warning fs-1"></i></div>
                <h6 class="text-muted">Failed Attempts (7d)</h6>
                <h3>--</h3>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between">
                <h5 class="mb-0">Quick Actions</h5>
            </div>
            <div class="card-body">
                <a href="{{ route('admin.security.two-factor') }}" class="btn btn-outline-primary mb-2"><i class="bi bi-shield-lock"></i> 2FA Settings</a>
                <a href="{{ route('admin.security.sessions') }}" class="btn btn-outline-info mb-2"><i class="bi bi-laptop"></i> Manage Sessions</a>
                <a href="{{ route('admin.security.login-history') }}" class="btn btn-outline-secondary mb-2"><i class="bi bi-clock-history"></i> Login History</a>
                <a href="{{ route('admin.security.password-policy') }}" class="btn btn-outline-warning mb-2"><i class="bi bi-key"></i> Password Policy</a>
                <a href="{{ route('admin.security.ip-restriction') }}" class="btn btn-outline-danger mb-2"><i class="bi bi-ip"></i> IP Restriction</a>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white"><h5 class="mb-0">Recent Security Events</h5></div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    <div class="list-group-item">
                        <small class="text-muted">--</small>
                        <p class="mb-0">No recent security events.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('admin.layouts.app')
@section('title', 'IP Restriction')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0"><i class="bi bi-ip"></i> IP Restriction</h1>
    <a href="{{ route('admin.security.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Back</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.security.ip-restriction.update') }}">
            @csrf
            @method('PUT')
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <label class="form-label">Mode</label>
                    <select name="mode" class="form-select" required>
                        <option value="whitelist">Whitelist (Allow only listed IPs)</option>
                        <option value="blacklist">Blacklist (Block listed IPs)</option>
                    </select>
                    <small class="text-muted">Whitelist: only listed IPs can access. Blacklist: listed IPs are blocked.</small>
                </div>
                <div class="col-md-4">
                    <div class="form-check form-switch mt-4">
                        <input type="checkbox" name="enabled" class="form-check-input" value="1" id="ipEnabled" {{ old('enabled', 1) ? 'checked' : '' }}>
                        <label class="form-check-label" for="ipEnabled">Enable IP Restriction</label>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">IP Addresses (one per line)</label>
                <textarea name="ips" class="form-control font-monospace" rows="10" placeholder="192.168.1.1&#10;10.0.0.0/24&#10;203.0.113.50">192.168.1.1
10.0.0.0/24</textarea>
                <small class="text-muted">Enter one IP address or CIDR range per line.</small>
            </div>

            <div class="mt-3">
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg"></i> Save IP Restriction</button>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm mt-4">
    <div class="card-header bg-white"><h6 class="mb-0">Your Current IP</h6></div>
    <div class="card-body">
        <span class="font-monospace fs-5">{{ request()->ip() }}</span>
        <button class="btn btn-outline-secondary btn-sm ms-2" onclick="navigator.clipboard.writeText('{{ request()->ip() }}')"><i class="bi bi-clipboard"></i></button>
    </div>
</div>
@endsection

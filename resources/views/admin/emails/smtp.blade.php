@extends('admin.layouts.app')

@section('title', 'SMTP Settings')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">SMTP Settings</h4>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.emails.smtp-update') }}">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-8 mb-3">
                    <label class="form-label">SMTP Host <span class="text-danger">*</span></label>
                    <input type="text" name="host" class="form-control @error('host') is-invalid @enderror" value="{{ old('host', $settings->host ?? '') }}" placeholder="smtp.gmail.com" required>
                    @error('host') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Port <span class="text-danger">*</span></label>
                    <input type="number" name="port" class="form-control @error('port') is-invalid @enderror" value="{{ old('port', $settings->port ?? 587) }}" required>
                    @error('port') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-control @error('username') is-invalid @enderror" value="{{ old('username', $settings->username ?? '') }}">
                    @error('username') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" value="{{ old('password', $settings->password ?? '') }}">
                    @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Encryption</label>
                    <select name="encryption" class="form-select @error('encryption') is-invalid @enderror">
                        <option value="tls" {{ ($settings->encryption ?? '') === 'tls' ? 'selected' : '' }}>TLS</option>
                        <option value="ssl" {{ ($settings->encryption ?? '') === 'ssl' ? 'selected' : '' }}>SSL</option>
                        <option value="none" {{ ($settings->encryption ?? '') === 'none' ? 'selected' : '' }}>None</option>
                    </select>
                    @error('encryption') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">From Address <span class="text-danger">*</span></label>
                    <input type="email" name="from_address" class="form-control @error('from_address') is-invalid @enderror" value="{{ old('from_address', $settings->from_address ?? '') }}" required>
                    @error('from_address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">From Name</label>
                    <input type="text" name="from_name" class="form-control @error('from_name') is-invalid @enderror" value="{{ old('from_name', $settings->from_name ?? 'Hotel Management') }}">
                    @error('from_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle me-1"></i> Save Settings</button>
        </form>
    </div>
</div>
@endsection

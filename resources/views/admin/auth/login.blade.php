@extends('admin.layouts.auth')

@section('title', 'Login')

@section('content')
<div id="loginSection">
    <h5 class="fw-bold mb-1">Welcome back</h5>
    <p class="text-muted mb-4" style="font-size:0.875rem;">Sign in to your account</p>

    <form method="POST" action="{{ route('admin.login.post') }}" id="loginForm">
        @csrf
        <div class="mb-3">
            <label for="email" class="form-label">Email Address</label>
            <input type="email" class="form-control @error('email') is-invalid @enderror"
                   id="email" name="email" value="{{ old('email') }}"
                   placeholder="you@example.com" required autofocus>
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <div class="input-group">
                <input type="password" class="form-control @error('password') is-invalid @enderror"
                       id="password" name="password" placeholder="Enter your password" required>
                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword()">
                    <i class="bi bi-eye" id="toggleIcon"></i>
                </button>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="form-check">
                <input type="checkbox" class="form-check-input" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                <label class="form-check-label" for="remember" style="font-size:0.875rem;">Remember me</label>
            </div>
            <a href="{{ route('password.request') }}" class="text-decoration-none" style="font-size:0.875rem;color:var(--auth-primary);">
                Forgot password?
            </a>
        </div>

        <button type="submit" class="btn btn-auth w-100 mb-3" id="loginBtn">
            <span id="loginText">Sign In</span>
            <span id="loginSpinner" class="d-none">
                <span class="spinner-border spinner-border-sm me-1" role="status"></span>Signing in...
            </span>
        </button>
    </form>

    <div id="twoFactorSection" style="display:none;">
        <hr>
        <h6 class="fw-bold mb-2"><i class="bi bi-shield-lock me-1"></i> Two-Factor Authentication</h6>
        <p class="text-muted mb-3" style="font-size:0.8125rem;">Enter the 6-digit code from your authenticator app.</p>

        <form method="POST" action="{{ route('admin.login.2fa.verify') }}" id="twoFactorForm">
            @csrf
            <input type="hidden" name="login_id" id="loginId" value="">
            <div class="mb-3">
                <label for="otp_code" class="form-label">Verification Code</label>
                <input type="text" class="form-control text-center fw-bold @error('otp_code') is-invalid @enderror"
                       id="otp_code" name="otp_code" maxlength="6" pattern="[0-9]{6}"
                       placeholder="000000" autocomplete="one-time-code" inputmode="numeric">
                @error('otp_code')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <button type="submit" class="btn btn-auth w-100">
                <i class="bi bi-check-circle me-1"></i> Verify Code
            </button>
        </form>

        <div class="text-center mt-3">
            <a href="#" onclick="useBackupCode(); return false;" style="font-size:0.8125rem;color:var(--auth-primary);text-decoration:none;">
                Use a backup code instead
            </a>
        </div>

        <div id="backupCodeSection" style="display:none;" class="mt-3">
            <form method="POST" action="{{ route('admin.login.2fa.backup') }}">
                @csrf
                <input type="hidden" name="login_id" value="">
                <div class="mb-3">
                    <label class="form-label" style="font-size:0.8125rem;">Backup Code</label>
                    <input type="text" class="form-control text-center" name="backup_code"
                           placeholder="XXXX-XXXX" maxlength="9" autocomplete="off">
                </div>
                <button type="submit" class="btn btn-auth w-100">Verify Backup Code</button>
            </form>
        </div>
    </div>

    <div class="text-center mt-3">
        <span class="text-muted" style="font-size:0.8125rem;">
            <i class="bi bi-google me-1"></i>
            Protected by Google Authenticator
        </span>
    </div>
</div>

@push('scripts')
<script>
    function togglePassword() {
        const input = document.getElementById('password');
        const icon = document.getElementById('toggleIcon');
        if (input.type === 'password') {
            input.type = 'text';
            icon.className = 'bi bi-eye-slash';
        } else {
            input.type = 'password';
            icon.className = 'bi bi-eye';
        }
    }

    function useBackupCode() {
        document.getElementById('backupCodeSection').style.display = 'block';
    }

    document.getElementById('loginForm').addEventListener('submit', function(e) {
        document.getElementById('loginText').classList.add('d-none');
        document.getElementById('loginSpinner').classList.remove('d-none');
        document.getElementById('loginBtn').disabled = true;
    });

    @if(session('show_2fa'))
        document.getElementById('loginSection').querySelector('form:not(#twoFactorForm)').style.display = 'none';
        document.getElementById('twoFactorSection').style.display = 'block';
        document.getElementById('loginId').value = '{{ session("login_id") }}';
    @endif
</script>
@endpush
@endsection

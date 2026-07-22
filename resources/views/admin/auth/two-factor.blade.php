@extends('admin.layouts.auth')

@section('title', 'Two-Factor Verification')

@section('content')
<h5 class="fw-bold mb-1"><i class="bi bi-shield-lock me-1"></i> Two-Factor Authentication</h5>
<p class="text-muted mb-4" style="font-size:0.875rem;">Enter the 6-digit code from your authenticator app to continue.</p>

<form method="POST" action="{{ route('login.2fa.verify') }}">
    @csrf
    <div class="mb-3">
        <label for="otp_code" class="form-label">Verification Code</label>
        <input type="text" class="form-control text-center fw-bold @error('otp_code') is-invalid @enderror"
               id="otp_code" name="otp_code" maxlength="6" pattern="[0-9]{6}"
               placeholder="000000" autocomplete="one-time-code" inputmode="numeric"
               style="font-size:1.5rem; letter-spacing:0.5rem;" required autofocus>
        @error('otp_code')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <button type="submit" class="btn btn-auth w-100 mb-3">
        <i class="bi bi-check-circle me-1"></i> Verify &amp; Sign In
    </button>
</form>

<hr>

<h6 class="text-center text-muted mb-3" style="font-size:0.8125rem;">Or use a backup code</h6>

<form method="POST" action="{{ route('login.2fa.backup') }}">
    @csrf
    <div class="mb-3">
        <input type="text" class="form-control text-center @error('backup_code') is-invalid @enderror"
               name="backup_code" placeholder="XXXX-XXXX" maxlength="9" autocomplete="off">
        @error('backup_code')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <button type="submit" class="btn btn-outline-secondary w-100">
        <i class="bi bi-key me-1"></i> Use Backup Code
    </button>
</form>

<div class="auth-links mt-3">
    <a href="{{ route('login') }}"><i class="bi bi-arrow-left me-1"></i> Back to Login</a>
</div>

@push('scripts')
<script>
    const otpInput = document.getElementById('otp_code');
    otpInput.addEventListener('input', function(e) {
        this.value = this.value.replace(/[^0-9]/g, '');
        if (this.value.length === 6) {
            this.form.submit();
        }
    });
</script>
@endpush
@endsection

@extends('admin.layouts.auth')

@section('title', 'Forgot Password')

@section('content')
<h5 class="fw-bold mb-1">Reset Password</h5>
<p class="text-muted mb-4" style="font-size:0.875rem;">Enter your email and we'll send you a reset link.</p>

<form method="POST" action="{{ route('password.email') }}">
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

    <button type="submit" class="btn btn-auth w-100">
        <i class="bi bi-envelope-arrow-up me-1"></i> Send Reset Link
    </button>
</form>

<div class="auth-links mt-3">
    <a href="{{ route('login') }}"><i class="bi bi-arrow-left me-1"></i> Back to Login</a>
</div>
@endsection

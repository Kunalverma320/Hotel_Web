@extends('admin.layouts.auth')

@section('title', 'Reset Password')

@section('content')
<h5 class="fw-bold mb-1">Create New Password</h5>
<p class="text-muted mb-4" style="font-size:0.875rem;">Enter your new password below.</p>

<form method="POST" action="{{ route('password.update') }}">
    @csrf
    <input type="hidden" name="token" value="{{ $token }}">
    <input type="hidden" name="email" value="{{ request()->query('email', old('email')) }}">

    <div class="mb-3">
        <label for="email" class="form-label">Email Address</label>
        <input type="email" class="form-control @error('email') is-invalid @enderror"
               id="email" name="email" value="{{ request()->query('email', old('email')) }}"
               placeholder="you@example.com" required autofocus>
        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="password" class="form-label">New Password</label>
        <input type="password" class="form-control @error('password') is-invalid @enderror"
               id="password" name="password" placeholder="Enter new password" required>
        @error('password')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="password_confirmation" class="form-label">Confirm Password</label>
        <input type="password" class="form-control"
               id="password_confirmation" name="password_confirmation"
               placeholder="Confirm new password" required>
    </div>

    <button type="submit" class="btn btn-auth w-100">
        <i class="bi bi-check-circle me-1"></i> Reset Password
    </button>
</form>

<div class="auth-links mt-3">
    <a href="{{ route('login') }}"><i class="bi bi-arrow-left me-1"></i> Back to Login</a>
</div>
@endsection

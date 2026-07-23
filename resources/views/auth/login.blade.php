@extends('layouts.guest')

@section('title', 'Sign In | MakeMyTrip Hotels')

@section('content')
<div class="container py-5 d-flex justify-content-center align-items-center" style="min-height: calc(100vh - 120px);">
    <div class="card shadow-lg border-0 p-4 p-md-5" style="max-width: 460px; width: 100%; border-radius: 24px; background: var(--card-bg);">
        <div class="text-center mb-4">
            <h3 class="fw-bold text-gradient mb-2" style="background: linear-gradient(90deg, #60b4ff 0%, #008cff 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; font-family: 'Outfit', sans-serif;">Customer Login</h3>
            <p class="text-muted small">Sign in to book hotels, manage reservations, and get access to special member rates.</p>
        </div>

        <form method="POST" action="{{ route('login.post') }}">
            @csrf
            
            <div class="mb-3 text-start">
                <label for="email" class="form-label fw-bold small text-uppercase text-muted">Email Address</label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                       id="email" name="email" value="{{ old('email') }}" 
                       placeholder="e.g. user@hotelms.com" required autocomplete="email" autofocus
                       style="border-radius: 12px; padding: 0.8rem 1rem; border: 1px solid var(--border); background: rgba(255,255,255,0.02); color: var(--text);">
                @error('email')
                    <div class="invalid-feedback mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4 text-start">
                <label for="password" class="form-label fw-bold small text-uppercase text-muted">Password</label>
                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                       id="password" name="password" 
                       placeholder="••••••••" required autocomplete="current-password"
                       style="border-radius: 12px; padding: 0.8rem 1rem; border: 1px solid var(--border); background: rgba(255,255,255,0.02); color: var(--text);">
                @error('password')
                    <div class="invalid-feedback mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="form-check text-start">
                    <input type="checkbox" class="form-check-input" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                    <label class="form-check-label text-muted small" for="remember">Remember me</label>
                </div>
                <a href="{{ route('password.request') }}" class="text-decoration-none small text-primary fw-semibold">Forgot Password?</a>
            </div>

            <button type="submit" class="btn btn-primary w-100 py-3 fw-bold" style="border-radius: 12px; background: linear-gradient(90deg, #60b4ff 0%, #008cff 100%); border: none; box-shadow: 0 4px 12px rgba(0, 140, 255, 0.25);">
                <i class="bi bi-box-arrow-in-right me-2"></i>Sign In
            </button>
        </form>

        <div class="mt-4 pt-3 border-top border-secondary border-opacity-25 text-center">
            <span class="text-muted small">Not a guest? <a href="{{ route('admin.login') }}" class="text-primary fw-bold text-decoration-none">Staff Login Here</a></span>
        </div>
    </div>
</div>
@endsection

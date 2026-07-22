@extends('admin.layouts.app')
@section('title', 'Password Policy')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0"><i class="bi bi-key"></i> Password Policy</h1>
    <a href="{{ route('admin.security.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Back</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.security.password-policy.update') }}">
            @csrf
            @method('PUT')
            <h5 class="mb-3">Password Requirements</h5>
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Minimum Length</label>
                    <input type="number" name="min_length" class="form-control" value="{{ old('min_length', 8) }}" min="6" max="128" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Password Expiry (days, 0 = never)</label>
                    <input type="number" name="password_expiry_days" class="form-control" value="{{ old('password_expiry_days', 90) }}" min="0" max="365" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Check Password History</label>
                    <select name="history_check" class="form-select">
                        <option value="0">No</option>
                        <option value="1" {{ old('history_check') ? 'selected' : '' }}>Yes</option>
                    </select>
                </div>
            </div>

            <h5 class="mt-4 mb-3">Complexity Requirements</h5>
            <div class="row g-3">
                <div class="col-md-3">
                    <div class="form-check form-switch">
                        <input type="checkbox" name="require_uppercase" class="form-check-input" value="1" id="upper" {{ old('require_uppercase', 1) ? 'checked' : '' }}>
                        <label class="form-check-label" for="upper">Require Uppercase (A-Z)</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-check form-switch">
                        <input type="checkbox" name="require_lowercase" class="form-check-input" value="1" id="lower" {{ old('require_lowercase', 1) ? 'checked' : '' }}>
                        <label class="form-check-label" for="lower">Require Lowercase (a-z)</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-check form-switch">
                        <input type="checkbox" name="require_numbers" class="form-check-input" value="1" id="numbers" {{ old('require_numbers', 1) ? 'checked' : '' }}>
                        <label class="form-check-label" for="numbers">Require Numbers (0-9)</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-check form-switch">
                        <input type="checkbox" name="require_special" class="form-check-input" value="1" id="special" {{ old('require_special', 1) ? 'checked' : '' }}>
                        <label class="form-check-label" for="special">Require Special (!@#$%)</label>
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg"></i> Save Policy</button>
            </div>
        </form>
    </div>
</div>
@endsection

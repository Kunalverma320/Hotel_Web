@extends('admin.layouts.app')

@section('title', 'WhatsApp Settings')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">WhatsApp API Settings</h4>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.whatsapp.update-settings') }}">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">API URL</label>
                <input type="url" name="api_url" class="form-control @error('api_url') is-invalid @enderror" value="{{ old('api_url', $settings->api_url ?? '') }}" placeholder="https://graph.facebook.com/v17.0">
                @error('api_url') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">API Token</label>
                <input type="password" name="api_token" class="form-control @error('api_token') is-invalid @enderror" value="{{ old('api_token', $settings->api_token ?? '') }}">
                @error('api_token') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Phone Number ID</label>
                    <input type="text" name="phone_number_id" class="form-control @error('phone_number_id') is-invalid @enderror" value="{{ old('phone_number_id', $settings->phone_number_id ?? '') }}">
                    @error('phone_number_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Business Account ID</label>
                    <input type="text" name="business_account_id" class="form-control @error('business_account_id') is-invalid @enderror" value="{{ old('business_account_id', $settings->business_account_id ?? '') }}">
                    @error('business_account_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Verify Token</label>
                <input type="text" name="verify_token" class="form-control @error('verify_token') is-invalid @enderror" value="{{ old('verify_token', $settings->verify_token ?? '') }}">
                @error('verify_token') <div class="invalid-feedback">{{ $message }}</div> @enderror
                <small class="text-muted">Used for webhook verification.</small>
            </div>

            <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle me-1"></i> Save Settings</button>
        </form>
    </div>
</div>
@endsection

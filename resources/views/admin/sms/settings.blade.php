@extends('admin.layouts.app')

@section('title', 'SMS Settings')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">SMS Provider Settings</h4>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.sms.update-settings') }}">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Provider <span class="text-danger">*</span></label>
                    <select name="provider" class="form-select @error('provider') is-invalid @enderror" required>
                        <option value="twilio" {{ ($settings->provider ?? '') === 'twilio' ? 'selected' : '' }}>Twilio</option>
                        <option value="nexmo" {{ ($settings->provider ?? '') === 'nexmo' ? 'selected' : '' }}>Nexmo (Vonage)</option>
                        <option value="custom" {{ ($settings->provider ?? '') === 'custom' ? 'selected' : '' }}>Custom API</option>
                    </select>
                    @error('provider') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">From Number</label>
                    <input type="text" name="from_number" class="form-control @error('from_number') is-invalid @enderror" value="{{ old('from_number', $settings->from_number ?? '') }}" placeholder="+1234567890">
                    @error('from_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">API Key</label>
                    <input type="text" name="api_key" class="form-control @error('api_key') is-invalid @enderror" value="{{ old('api_key', $settings->api_key ?? '') }}">
                    @error('api_key') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">API Secret</label>
                    <input type="password" name="api_secret" class="form-control @error('api_secret') is-invalid @enderror" value="{{ old('api_secret', $settings->api_secret ?? '') }}">
                    @error('api_secret') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Custom API URL</label>
                <input type="url" name="api_url" class="form-control @error('api_url') is-invalid @enderror" value="{{ old('api_url', $settings->api_url ?? '') }}" placeholder="https://api.example.com/sms">
                @error('api_url') <div class="invalid-feedback">{{ $message }}</div> @enderror
                <small class="text-muted">Required only for Custom API provider.</small>
            </div>

            <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle me-1"></i> Save Settings</button>
        </form>
    </div>
</div>
@endsection

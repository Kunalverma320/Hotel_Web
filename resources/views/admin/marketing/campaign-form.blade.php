@extends('admin.layouts.app')

@section('title', isset($campaign) ? 'Edit Campaign' : 'Create Campaign')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">{{ isset($campaign) ? 'Edit Campaign' : 'Create Campaign' }}</h4>
    <a href="{{ route('admin.marketing.campaigns') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i> Back</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form method="POST" action="{{ isset($campaign) ? route('admin.marketing.campaign-store') : route('admin.marketing.campaign-store') }}">
            @csrf

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Campaign Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $campaign->name ?? '') }}" required>
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Type <span class="text-danger">*</span></label>
                    <select name="type" class="form-select" required>
                        <option value="email" {{ ($campaign->type ?? '') === 'email' ? 'selected' : '' }}>Email</option>
                        <option value="sms" {{ ($campaign->type ?? '') === 'sms' ? 'selected' : '' }}>SMS</option>
                        <option value="whatsapp" {{ ($campaign->type ?? '') === 'whatsapp' ? 'selected' : '' }}>WhatsApp</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Target Audience <span class="text-danger">*</span></label>
                    <input type="text" name="target_audience" class="form-control" value="{{ old('target_audience', $campaign->target_audience ?? 'All Guests') }}" required placeholder="All Guests">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Subject</label>
                <input type="text" name="subject" class="form-control" value="{{ old('subject', $campaign->subject ?? '') }}" placeholder="Optional - only for email campaigns">
            </div>

            <div class="mb-3">
                <label class="form-label">Message <span class="text-danger">*</span></label>
                <textarea name="message" class="form-control @error('message') is-invalid @enderror" rows="8" required>{{ old('message', $campaign->message ?? '') }}</textarea>
                @error('message') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Scheduled Date/Time</label>
                <input type="datetime-local" name="scheduled_at" class="form-control" value="{{ old('scheduled_at', isset($campaign) && $campaign->scheduled_at ? $campaign->scheduled_at->format('Y-m-d\TH:i') : '') }}">
                <small class="text-muted">Leave empty to send immediately when clicking Send.</small>
            </div>

            <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle me-1"></i> {{ isset($campaign) ? 'Update' : 'Create' }} Campaign</button>
        </form>
    </div>
</div>
@endsection

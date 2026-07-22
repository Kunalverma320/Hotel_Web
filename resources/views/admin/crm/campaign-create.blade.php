@extends('admin.layouts.app')
@section('title', 'Create Campaign')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0">Create Campaign</h4>
        <small class="text-muted">Set up a new marketing campaign</small>
    </div>
    <a href="{{ route('admin.crm.campaigns') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i> Back</a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.crm.store-campaign') }}">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Campaign Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Hotel <span class="text-danger">*</span></label>
                    <select name="hotel_id" class="form-select @error('hotel_id') is-invalid @enderror" required>
                        <option value="">Select Hotel</option>
                        @foreach($hotels as $hotel)
                            <option value="{{ $hotel->id }}" {{ old('hotel_id') == $hotel->id ? 'selected' : '' }}>{{ $hotel->name }}</option>
                        @endforeach
                    </select>
                    @error('hotel_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-12">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="2">{{ old('description') }}</textarea>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Type <span class="text-danger">*</span></label>
                    <select name="type" class="form-select" required>
                        @foreach(['email','sms','social','search','promotion','referral','other'] as $t)
                            <option value="{{ $t }}" {{ old('type') == $t ? 'selected' : '' }}>{{ ucfirst($t) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-select" required>
                        @foreach(['draft','scheduled','active'] as $s)
                            <option value="{{ $s }}" {{ old('status', 'draft') == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Budget</label>
                    <input type="number" step="0.01" name="budget" class="form-control" value="{{ old('budget') }}" min="0">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Start Date</label>
                    <input type="date" name="start_date" class="form-control" value="{{ old('start_date') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">End Date</label>
                    <input type="date" name="end_date" class="form-control" value="{{ old('end_date') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Target Audience</label>
                    <input type="text" name="target_audience" class="form-control" value="{{ old('target_audience') }}" placeholder="e.g. Business travelers">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Landing URL</label>
                    <input type="url" name="landing_url" class="form-control" value="{{ old('landing_url') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Channels</label>
                    <div class="d-flex gap-3 mt-2">
                        @foreach(['email','sms','social','website','print','radio','tv'] as $ch)
                            <div class="form-check">
                                <input type="checkbox" name="channels[]" value="{{ $ch }}" class="form-check-input" id="ch_{{ $ch }}" {{ in_array($ch, old('channels', [])) ? 'checked' : '' }}>
                                <label class="form-check-label" for="ch_{{ $ch }}">{{ ucfirst($ch) }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="col-md-12">
                    <label class="form-label">Campaign Content</label>
                    <textarea name="content" class="form-control" rows="4" placeholder="Campaign message/content...">{{ old('content') }}</textarea>
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i> Create Campaign</button>
                <a href="{{ route('admin.crm.campaigns') }}" class="btn btn-outline-secondary ms-2">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection

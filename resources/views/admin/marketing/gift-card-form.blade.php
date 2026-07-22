@extends('admin.layouts.app')

@section('title', 'Create Gift Card')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Create Gift Card</h4>
    <a href="{{ route('admin.marketing.gift-cards') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i> Back</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.marketing.gift-card-store') }}">
            @csrf

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Balance ($) <span class="text-danger">*</span></label>
                    <input type="number" name="balance" class="form-control @error('balance') is-invalid @enderror" value="{{ old('balance', 100) }}" step="0.01" min="1" required>
                    @error('balance') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    <small class="text-muted">A unique code will be auto-generated.</small>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Expiry Date</label>
                    <input type="date" name="expires_at" class="form-control" value="{{ old('expires_at') }}">
                    <small class="text-muted">Leave empty for no expiry.</small>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Recipient Name</label>
                    <input type="text" name="recipient_name" class="form-control" value="{{ old('recipient_name') }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Recipient Email</label>
                    <input type="email" name="recipient_email" class="form-control" value="{{ old('recipient_email') }}">
                </div>
            </div>

            <button type="submit" class="btn btn-primary"><i class="bi bi-gift me-1"></i> Create Gift Card</button>
        </form>
    </div>
</div>
@endsection

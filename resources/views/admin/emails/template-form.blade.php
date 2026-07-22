@extends('admin.layouts.app')

@section('title', isset($template) ? 'Edit Email Template' : 'Create Email Template')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">{{ isset($template) ? 'Edit Email Template' : 'Create Email Template' }}</h4>
    <a href="{{ route('admin.emails.templates') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i> Back</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form method="POST" action="{{ isset($template) ? route('admin.emails.template-update', $template->id) : route('admin.emails.template-store') }}">
            @csrf
            @if(isset($template)) @method('PUT') @endif

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $template->name ?? '') }}" required>
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Slug <span class="text-danger">*</span></label>
                    <input type="text" name="slug" class="form-control @error('slug') is-invalid @enderror" value="{{ old('slug', $template->slug ?? '') }}" required>
                    @error('slug') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Subject <span class="text-danger">*</span></label>
                <input type="text" name="subject" class="form-control @error('subject') is-invalid @enderror" value="{{ old('subject', $template->subject ?? '') }}" required>
                @error('subject') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Body <span class="text-danger">*</span></label>
                <textarea name="body" class="form-control @error('body') is-invalid @enderror" rows="15" required>{{ old('body', $template->body ?? '') }}</textarea>
                @error('body') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="alert alert-info">
                <h6 class="alert-heading"><i class="bi bi-info-circle me-1"></i> Available Variables</h6>
                <div class="row">
                    <div class="col-md-4">
                        <code>{{ '{{name}}' }}</code> - Guest name<br>
                        <code>{{ '{{email}}' }}</code> - Guest email
                    </div>
                    <div class="col-md-4">
                        <code>{{ '{{room}}' }}</code> - Room number<br>
                        <code>{{ '{{checkin}}' }}</code> - Check-in date
                    </div>
                    <div class="col-md-4">
                        <code>{{ '{{checkout}}' }}</code> - Check-out date<br>
                        <code>{{ '{{booking_id}}' }}</code> - Booking reference
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle me-1"></i> {{ isset($template) ? 'Update' : 'Create' }} Template</button>
                <a href="{{ route('admin.emails.templates') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection

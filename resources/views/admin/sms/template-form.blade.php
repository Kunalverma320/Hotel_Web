@extends('admin.layouts.app')

@section('title', isset($template) ? 'Edit SMS Template' : 'Create SMS Template')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">{{ isset($template) ? 'Edit SMS Template' : 'Create SMS Template' }}</h4>
    <a href="{{ route('admin.sms.templates') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i> Back</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form method="POST" action="{{ isset($template) ? route('admin.sms.template-update', $template->id) : route('admin.sms.template-store') }}">
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
                <label class="form-label">Message Body <span class="text-danger">*</span></label>
                <textarea name="body" class="form-control @error('body') is-invalid @enderror" rows="6" required maxlength="1600">{{ old('body', $template->body ?? '') }}</textarea>
                @error('body') <div class="invalid-feedback">{{ $message }}</div> @enderror
                <small class="text-muted">Max 1600 characters. Use <code>{{ '{{name}}' }}</code>, <code>{{ '{{room}}' }}</code>, <code>{{ '{{checkin}}' }}</code>, <code>{{ '{{checkout}}' }}</code> as variables.</small>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle me-1"></i> {{ isset($template) ? 'Update' : 'Create' }} Template</button>
                <a href="{{ route('admin.sms.templates') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection

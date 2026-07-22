@extends('admin.layouts.app')

@section('title', isset($faq) ? 'Edit FAQ' : 'Create FAQ')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">{{ isset($faq) ? 'Edit FAQ' : 'Create FAQ' }}</h4>
    <a href="{{ route('admin.cms.faqs') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i> Back</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form method="POST" action="{{ isset($faq) ? route('admin.cms.faq-update', $faq->id) : route('admin.cms.faq-store') }}">
            @csrf
            @if(isset($faq)) @method('PUT') @endif

            <div class="mb-3">
                <label class="form-label">Question <span class="text-danger">*</span></label>
                <input type="text" name="question" class="form-control @error('question') is-invalid @enderror" value="{{ old('question', $faq->question ?? '') }}" required>
                @error('question') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Answer <span class="text-danger">*</span></label>
                <textarea name="answer" class="form-control @error('answer') is-invalid @enderror" rows="8" required>{{ old('answer', $faq->answer ?? '') }}</textarea>
                @error('answer') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Sort Order</label>
                    <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $faq->sort_order ?? 0) }}" min="0">
                    <small class="text-muted">Lower values appear first.</small>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-select" required>
                        <option value="active" {{ ($faq->status ?? 'active') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ ($faq->status ?? '') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
            </div>

            <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle me-1"></i> {{ isset($faq) ? 'Update' : 'Create' }} FAQ</button>
        </form>
    </div>
</div>
@endsection

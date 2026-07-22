@extends('admin.layouts.app')

@section('title', isset($account) ? 'Edit Account' : 'Add Account')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">{{ isset($account) ? 'Edit Account' : 'Add New Account' }}</h4>
    <a href="{{ route('admin.finance.coa') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i> Back
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.finance.coa-store') }}" method="POST">
            @csrf
            @if(isset($account))
                @method('PUT')
            @endif

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Account Code <span class="text-danger">*</span></label>
                    <input type="text" name="code" class="form-control @error('code') is-invalid @enderror"
                           value="{{ old('code', $account->code ?? '') }}" required placeholder="e.g., 1001">
                    @error('code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Account Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name', $account->name ?? '') }}" required>
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Account Type <span class="text-danger">*</span></label>
                    <select name="type" class="form-select @error('type') is-invalid @enderror" required>
                        <option value="">Select Type</option>
                        @foreach(['asset', 'liability', 'equity', 'income', 'expense'] as $type)
                            <option value="{{ $type }}" {{ old('type', $account->type ?? '') == $type ? 'selected' : '' }}>
                                {{ ucfirst($type) }}
                            </option>
                        @endforeach
                    </select>
                    @error('type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Parent Account</label>
                    <select name="parent_id" class="form-select">
                        <option value="">None (Top Level)</option>
                        @foreach($parentAccounts as $parent)
                            <option value="{{ $parent->id }}" {{ old('parent_id', $account->parent_id ?? '') == $parent->id ? 'selected' : '' }}>
                                {{ $parent->code }} - {{ $parent->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Is Group Account? <span class="text-danger">*</span></label>
                    <select name="is_group" class="form-select" required>
                        <option value="0" {{ old('is_group', $account->is_group ?? 0) == 0 ? 'selected' : '' }}>No (Detail Account)</option>
                        <option value="1" {{ old('is_group', $account->is_group ?? 0) == 1 ? 'selected' : '' }}>Yes (Group Account)</option>
                    </select>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-select" required>
                        <option value="active" {{ old('status', $account->status ?? 'active') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status', $account->status ?? '') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                <div class="col-12 mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="2">{{ old('description', $account->description ?? '') }}</textarea>
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i> {{ isset($account) ? 'Update Account' : 'Create Account' }}
                </button>
                <a href="{{ route('admin.finance.coa') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection

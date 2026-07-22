@extends('admin.layouts.app')

@section('title', isset($supplier) ? 'Edit Supplier' : 'Add Supplier')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">{{ isset($supplier) ? 'Edit Supplier' : 'Add New Supplier' }}</h4>
    <a href="{{ route('admin.suppliers.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i> Back
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ isset($supplier) ? route('admin.suppliers.update', $supplier->id) : route('admin.suppliers.store') }}" method="POST">
            @csrf
            @if(isset($supplier))
                @method('PUT')
            @endif

            <div class="row">
                <div class="col-12"><h6 class="text-muted mb-3">Basic Information</h6></div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Supplier Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name', $supplier->name ?? '') }}" required>
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Contact Person</label>
                    <input type="text" name="contact_person" class="form-control @error('contact_person') is-invalid @enderror"
                           value="{{ old('contact_person', $supplier->contact_person ?? '') }}">
                    @error('contact_person') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                           value="{{ old('email', $supplier->email ?? '') }}">
                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Phone</label>
                    <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                           value="{{ old('phone', $supplier->phone ?? '') }}">
                    @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-12"><hr><h6 class="text-muted mb-3">Address</h6></div>

                <div class="col-12 mb-3">
                    <label class="form-label">Address</label>
                    <textarea name="address" class="form-control @error('address') is-invalid @enderror" rows="2">{{ old('address', $supplier->address ?? '') }}</textarea>
                    @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-3 mb-3">
                    <label class="form-label">City</label>
                    <input type="text" name="city" class="form-control @error('city') is-invalid @enderror"
                           value="{{ old('city', $supplier->city ?? '') }}">
                    @error('city') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-3 mb-3">
                    <label class="form-label">State</label>
                    <input type="text" name="state" class="form-control @error('state') is-invalid @enderror"
                           value="{{ old('state', $supplier->state ?? '') }}">
                    @error('state') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-3 mb-3">
                    <label class="form-label">Zip Code</label>
                    <input type="text" name="zip_code" class="form-control @error('zip_code') is-invalid @enderror"
                           value="{{ old('zip_code', $supplier->zip_code ?? '') }}">
                    @error('zip_code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-3 mb-3">
                    <label class="form-label">Country</label>
                    <input type="text" name="country" class="form-control @error('country') is-invalid @enderror"
                           value="{{ old('country', $supplier->country ?? 'India') }}">
                    @error('country') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-12"><hr><h6 class="text-muted mb-3">Financial Details</h6></div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Tax Number (GSTIN)</label>
                    <input type="text" name="tax_number" class="form-control @error('tax_number') is-invalid @enderror"
                           value="{{ old('tax_number', $supplier->tax_number ?? '') }}">
                    @error('tax_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Payment Terms (Days)</label>
                    <input type="number" name="payment_terms" class="form-control @error('payment_terms') is-invalid @enderror"
                           value="{{ old('payment_terms', $supplier->payment_terms ?? 30) }}" min="0">
                    @error('payment_terms') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                        <option value="active" {{ old('status', $supplier->status ?? 'active') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status', $supplier->status ?? '') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-12"><h6 class="text-muted mb-3">Bank Details</h6></div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Bank Name</label>
                    <input type="text" name="bank_name" class="form-control @error('bank_name') is-invalid @enderror"
                           value="{{ old('bank_name', $supplier->bank_name ?? '') }}">
                    @error('bank_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Bank Account Number</label>
                    <input type="text" name="bank_account" class="form-control @error('bank_account') is-invalid @enderror"
                           value="{{ old('bank_account', $supplier->bank_account ?? '') }}">
                    @error('bank_account') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-12 mb-3">
                    <label class="form-label">Notes</label>
                    <textarea name="notes" class="form-control" rows="2">{{ old('notes', $supplier->notes ?? '') }}</textarea>
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i> {{ isset($supplier) ? 'Update Supplier' : 'Create Supplier' }}
                </button>
                <a href="{{ route('admin.suppliers.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection

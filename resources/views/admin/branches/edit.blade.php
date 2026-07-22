@extends('admin.layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Edit Branch: {{ $branch->name }}</h4>
    <a href="{{ route('admin.branches.index') }}" class="btn btn-outline-secondary">
        <i class="ri-arrow-left-line me-1"></i> Back
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.branches.update', $branch->id) }}" method="POST">
            @csrf
            @method('PUT')

            @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            <div class="row g-3">
                <div class="col-md-6">
                    <label for="company_id" class="form-label">Company <span class="text-danger">*</span></label>
                    <select name="company_id" id="company_id" class="form-select @error('company_id') is-invalid @enderror" required>
                        <option value="">Select Company</option>
                        @foreach($companies as $id => $name)
                            <option value="{{ $id }}" {{ old('company_id', $branch->company_id) == $id ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                    @error('company_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label for="name" class="form-label">Branch Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $branch->name) }}" required>
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label for="code" class="form-label">Branch Code <span class="text-danger">*</span></label>
                    <input type="text" name="code" id="code" class="form-control @error('code') is-invalid @enderror" value="{{ old('code', $branch->code) }}" required>
                    @error('code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $branch->email) }}">
                </div>
                <div class="col-md-4">
                    <label for="phone" class="form-label">Phone</label>
                    <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone', $branch->phone) }}">
                </div>
                <div class="col-12">
                    <label for="address" class="form-label">Address</label>
                    <textarea name="address" id="address" class="form-control" rows="2">{{ old('address', $branch->address) }}</textarea>
                </div>
                <div class="col-md-3"><label class="form-label">City</label><input type="text" name="city" class="form-control" value="{{ old('city', $branch->city) }}"></div>
                <div class="col-md-3"><label class="form-label">State</label><input type="text" name="state" class="form-control" value="{{ old('state', $branch->state) }}"></div>
                <div class="col-md-3"><label class="form-label">Country</label><input type="text" name="country" class="form-control" value="{{ old('country', $branch->country) }}"></div>
                <div class="col-md-3"><label class="form-label">Zipcode</label><input type="text" name="zipcode" class="form-control" value="{{ old('zipcode', $branch->zipcode) }}"></div>
                <div class="col-md-6">
                    <label for="manager_id" class="form-label">Branch Manager</label>
                    <select name="manager_id" id="manager_id" class="form-select">
                        <option value="">Select Manager</option>
                        @foreach(\App\Models\User::where('role', 'manager')->orderBy('name')->get() as $user)
                            <option value="{{ $user->id }}" {{ old('manager_id', $branch->manager_id) == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                    <select name="status" id="status" class="form-select" required>
                        <option value="active" {{ old('status', $branch->status) == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status', $branch->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
            </div>

            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="ri-save-line me-1"></i> Update Branch</button>
                <a href="{{ route('admin.branches.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection

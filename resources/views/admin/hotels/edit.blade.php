@extends('admin.layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Edit Hotel: {{ $hotel->name }}</h4>
    <a href="{{ route('admin.hotels.index') }}" class="btn btn-outline-secondary">
        <i class="ri-arrow-left-line me-1"></i> Back
    </a>
</div>

<form action="{{ route('admin.hotels.update', $hotel->id) }}" method="POST" enctype="multipart/form-data">
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

    <ul class="nav nav-tabs mb-4" role="tablist">
        <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#tab-basic">Basic Info</a></li>
        <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab-address">Address & Contact</a></li>
        <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab-location">Location</a></li>
        <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab-policies">Policies</a></li>
        <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab-seo">SEO</a></li>
        <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab-amenities">Amenities</a></li>
    </ul>

    <div class="tab-content">
        {{-- Basic Info Tab --}}
        <div class="tab-pane fade show active" id="tab-basic">
            <div class="card">
                <div class="card-header"><h5 class="mb-0">Basic Information</h5></div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="company_id" class="form-label">Company <span class="text-danger">*</span></label>
                            <select name="company_id" id="company_id" class="form-select" required>
                                <option value="">Select Company</option>
                                @foreach($companies as $id => $name)
                                    <option value="{{ $id }}" {{ old('company_id', $hotel->company_id) == $id ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="branch_id" class="form-label">Branch</label>
                            <select name="branch_id" id="branch_id" class="form-select">
                                <option value="">Select Branch</option>
                                @foreach($branches as $branch)
                                    <option value="{{ $branch->id }}" data-company="{{ $branch->company_id }}" {{ old('branch_id', $hotel->branch_id) == $branch->id ? 'selected' : '' }}>
                                        {{ $branch->name }} {{ $branch->code ? '('.$branch->code.')' : '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="name" class="form-label">Hotel Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $hotel->name) }}" required>
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="tagline" class="form-label">Tagline</label>
                            <input type="text" name="tagline" id="tagline" class="form-control" value="{{ old('tagline', $hotel->tagline) }}">
                        </div>
                        <div class="col-12">
                            <label for="description" class="form-label">Description</label>
                            <textarea name="description" id="description" class="form-control" rows="4">{{ old('description', $hotel->description) }}</textarea>
                        </div>
                        <div class="col-md-4">
                            <label for="logo" class="form-label">Logo</label>
                            @if($hotel->logo)
                                <div class="mb-2"><img src="{{ asset('storage/' . $hotel->logo) }}" class="rounded" style="height: 50px;"></div>
                            @endif
                            <input type="file" name="logo" id="logo" class="form-control" accept="image/*">
                        </div>
                        <div class="col-md-4">
                            <label for="cover_image" class="form-label">Cover Image</label>
                            @if($hotel->cover_image)
                                <div class="mb-2"><img src="{{ asset('storage/' . $hotel->cover_image) }}" class="rounded" style="height: 50px;"></div>
                            @endif
                            <input type="file" name="cover_image" id="cover_image" class="form-control" accept="image/*">
                        </div>
                        <div class="col-md-2">
                            <label for="star_rating" class="form-label">Star Rating <span class="text-danger">*</span></label>
                            <select name="star_rating" id="star_rating" class="form-select" required>
                                @for($i = 1; $i <= 5; $i++)
                                    <option value="{{ $i }}" {{ old('star_rating', $hotel->star_rating) == $i ? 'selected' : '' }}>{{ $i }} Star{{ $i > 1 ? 's' : '' }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select name="status" id="status" class="form-select" required>
                                <option value="active" {{ old('status', $hotel->status) == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status', $hotel->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Address & Contact Tab --}}
        <div class="tab-pane fade" id="tab-address">
            <div class="card">
                <div class="card-header"><h5 class="mb-0">Address & Contact Information</h5></div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12"><label class="form-label">Address</label><textarea name="address" class="form-control" rows="2">{{ old('address', $hotel->address) }}</textarea></div>
                        <div class="col-md-3"><label class="form-label">City</label><input type="text" name="city" class="form-control" value="{{ old('city', $hotel->city) }}"></div>
                        <div class="col-md-3"><label class="form-label">State</label><input type="text" name="state" class="form-control" value="{{ old('state', $hotel->state) }}"></div>
                        <div class="col-md-3"><label class="form-label">Country</label><input type="text" name="country" class="form-control" value="{{ old('country', $hotel->country) }}"></div>
                        <div class="col-md-3"><label class="form-label">Zipcode</label><input type="text" name="zipcode" class="form-control" value="{{ old('zipcode', $hotel->zipcode) }}"></div>
                        <div class="col-md-4"><label class="form-label">Phone</label><input type="text" name="phone" class="form-control" value="{{ old('phone', $hotel->phone) }}"></div>
                        <div class="col-md-4"><label class="form-label">Email</label><input type="email" name="email" class="form-control" value="{{ old('email', $hotel->email) }}"></div>
                        <div class="col-md-4"><label class="form-label">Website</label><input type="url" name="website" class="form-control" value="{{ old('website', $hotel->website) }}"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Location Tab --}}
        <div class="tab-pane fade" id="tab-location">
            <div class="card">
                <div class="card-header"><h5 class="mb-0">Location Coordinates</h5></div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6"><label class="form-label">Latitude</label><input type="number" step="any" name="latitude" class="form-control" value="{{ old('latitude', $hotel->latitude) }}"></div>
                        <div class="col-md-6"><label class="form-label">Longitude</label><input type="number" step="any" name="longitude" class="form-control" value="{{ old('longitude', $hotel->longitude) }}"></div>
                        <div class="col-12">
                            <div id="map-preview" class="rounded border" style="height: 300px; background-color: #e9ecef; display: flex; align-items: center; justify-content: center;">
                                <span class="text-muted"><i class="ri-map-pin-line me-1"></i> Enter coordinates to preview location</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Policies Tab --}}
        <div class="tab-pane fade" id="tab-policies">
            <div class="card">
                <div class="card-header"><h5 class="mb-0">Hotel Policies</h5></div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6"><label class="form-label">Check-in Time</label><input type="time" name="check_in_time" class="form-control" value="{{ old('check_in_time', $hotel->check_in_time) }}"></div>
                        <div class="col-md-6"><label class="form-label">Check-out Time</label><input type="time" name="check_out_time" class="form-control" value="{{ old('check_out_time', $hotel->check_out_time) }}"></div>
                        <div class="col-12"><label class="form-label">Cancellation Policy</label><textarea name="cancellation_policy" class="form-control" rows="4">{{ old('cancellation_policy', $hotel->cancellation_policy) }}</textarea></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- SEO Tab --}}
        <div class="tab-pane fade" id="tab-seo">
            <div class="card">
                <div class="card-header"><h5 class="mb-0">SEO Settings</h5></div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12"><label class="form-label">Meta Title</label><input type="text" name="meta_title" class="form-control" value="{{ old('meta_title', $hotel->meta_title) }}" maxlength="255"></div>
                        <div class="col-12"><label class="form-label">Meta Description</label><textarea name="meta_description" class="form-control" rows="3" maxlength="500">{{ old('meta_description', $hotel->meta_description) }}</textarea></div>
                        <div class="col-12"><label class="form-label">Meta Keywords</label><input type="text" name="meta_keywords" class="form-control" value="{{ old('meta_keywords', $hotel->meta_keywords) }}"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Amenities Tab --}}
        <div class="tab-pane fade" id="tab-amenities">
            <div class="card">
                <div class="card-header"><h5 class="mb-0">Amenities</h5></div>
                <div class="card-body">
                    @if($amenities->count() > 0)
                    <div class="row g-2">
                        @foreach($amenities as $amenity)
                        <div class="col-md-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="amenities[]" value="{{ $amenity->id }}" id="amenity-{{ $amenity->id }}" {{ $hotel->amenities->contains($amenity->id) ? 'checked' : '' }}>
                                <label class="form-check-label" for="amenity-{{ $amenity->id }}">
                                    @if($amenity->icon)<i class="{{ $amenity->icon }} me-1"></i>@endif {{ $amenity->name }}
                                </label>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                        <p class="text-muted">No amenities available.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4 d-flex gap-2">
        <button type="submit" class="btn btn-primary"><i class="ri-save-line me-1"></i> Update Hotel</button>
        <a href="{{ route('admin.hotels.index') }}" class="btn btn-outline-secondary">Cancel</a>
    </div>
</form>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const companySelect = document.getElementById('company_id');
        const branchSelect = document.getElementById('branch_id');

        if (companySelect && branchSelect) {
            function filterBranches() {
                const selectedCompanyId = companySelect.value;
                const options = branchSelect.querySelectorAll('option');

                let hasValidSelection = false;

                options.forEach(option => {
                    const companyId = option.getAttribute('data-company');
                    if (!option.value) {
                        option.style.display = '';
                    } else if (!selectedCompanyId || companyId === selectedCompanyId) {
                        option.style.display = '';
                        option.disabled = false;
                        if (option.selected) {
                            hasValidSelection = true;
                        }
                    } else {
                        option.style.display = 'none';
                        option.disabled = true;
                        if (option.selected) {
                            option.selected = false;
                        }
                    }
                });

                if (!hasValidSelection && branchSelect.value && branchSelect.querySelector(`option[value="${branchSelect.value}"]`)?.disabled) {
                    branchSelect.value = '';
                }
            }

            companySelect.addEventListener('change', filterBranches);
            filterBranches();
        }
    });
</script>
@endpush
@endsection

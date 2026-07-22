@extends('admin.layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Hotel: {{ $hotel->name }}</h4>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.hotels.edit', $hotel->id) }}" class="btn btn-warning"><i class="ri-edit-line me-1"></i> Edit</a>
        <a href="{{ route('admin.hotels.images', $hotel->id) }}" class="btn btn-info"><i class="ri-image-line me-1"></i> Gallery</a>
        <a href="{{ route('admin.hotels.index') }}" class="btn btn-outline-secondary"><i class="ri-arrow-left-line me-1"></i> Back</a>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif

{{-- Cover Image Banner --}}
@if($hotel->cover_image)
<div class="card mb-4 overflow-hidden">
    <img src="{{ asset('storage/' . $hotel->cover_image) }}" alt="{{ $hotel->name }}" class="card-img-top" style="height: 250px; object-fit: cover;">
    <div class="card-body">
        <div class="d-flex align-items-center">
            @if($hotel->logo)
                <img src="{{ asset('storage/' . $hotel->logo) }}" class="rounded me-3 border" style="height: 60px; width: 60px; object-fit: cover;">
            @endif
            <div>
                <h4 class="mb-0">{{ $hotel->name }}</h4>
                @if($hotel->tagline)<p class="text-muted mb-1">{{ $hotel->tagline }}</p>@endif
                <div>
                    @for($i = 1; $i <= 5; $i++)
                        <i class="ri-star-{{ $i <= $hotel->star_rating ? 'fill text-warning' : 'line text-muted' }}"></i>
                    @endfor
                    @if($hotel->status == 'active')
                        <span class="badge bg-success ms-2">Active</span>
                    @else
                        <span class="badge bg-danger ms-2">Inactive</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@else
<div class="mb-4">
    <div class="d-flex align-items-center">
        @if($hotel->logo)
            <img src="{{ asset('storage/' . $hotel->logo) }}" class="rounded me-3 border" style="height: 60px; width: 60px; object-fit: cover;">
        @endif
        <div>
            <h4 class="mb-0">{{ $hotel->name }}</h4>
            <div>
                @for($i = 1; $i <= 5; $i++)
                    <i class="ri-star-{{ $i <= $hotel->star_rating ? 'fill text-warning' : 'line text-muted' }}"></i>
                @endfor
                @if($hotel->status == 'active')
                    <span class="badge bg-success ms-2">Active</span>
                @else
                    <span class="badge bg-danger ms-2">Inactive</span>
                @endif
            </div>
        </div>
    </div>
</div>
@endif

<div class="row g-4">
    {{-- Left Column --}}
    <div class="col-lg-8">
        {{-- Statistics Cards --}}
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card bg-primary bg-opacity-10 border-primary">
                    <div class="card-body text-center">
                        <i class="ri-door-open-line fs-3 text-primary"></i>
                        <h3 class="mb-0 text-primary">{{ $hotel->rooms_count ?? $hotel->rooms->count() }}</h3>
                        <small class="text-muted">Rooms</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success bg-opacity-10 border-success">
                    <div class="card-body text-center">
                        <i class="ri-calendar-check-line fs-3 text-success"></i>
                        <h3 class="mb-0 text-success">{{ $hotel->bookings_count ?? 0 }}</h3>
                        <small class="text-muted">Bookings</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning bg-opacity-10 border-warning">
                    <div class="card-body text-center">
                        <i class="ri-image-line fs-3 text-warning"></i>
                        <h3 class="mb-0 text-warning">{{ $hotel->images_count ?? $hotel->images->count() }}</h3>
                        <small class="text-muted">Photos</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info bg-opacity-10 border-info">
                    <div class="card-body text-center">
                        <i class="ri-star-line fs-3 text-info"></i>
                        <h3 class="mb-0 text-info">{{ number_format($hotel->reviews_avg_rating ?? 0, 1) }}</h3>
                        <small class="text-muted">Rating</small>
                    </div>
                </div>
            </div>
        </div>

        {{-- Description --}}
        @if($hotel->description)
        <div class="card mb-4">
            <div class="card-header"><h6 class="mb-0"><i class="ri-file-text-line me-1"></i> Description</h6></div>
            <div class="card-body">{!! nl2br(e($hotel->description)) !!}</div>
        </div>
        @endif

        {{-- Amenities --}}
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0"><i class="ri-service-line me-1"></i> Amenities</h6>
                <a href="{{ route('admin.hotels.amenities', $hotel->id) }}" class="btn btn-sm btn-outline-primary">Manage</a>
            </div>
            <div class="card-body">
                @if($hotel->amenities->count() > 0)
                <div class="row g-2">
                    @foreach($hotel->amenities as $amenity)
                    <div class="col-auto">
                        <span class="badge bg-light text-dark border p-2">
                            @if($amenity->icon)<i class="{{ $amenity->icon }} me-1"></i>@endif {{ $amenity->name }}
                        </span>
                    </div>
                    @endforeach
                </div>
                @else
                    <p class="text-muted mb-0">No amenities assigned.</p>
                @endif
            </div>
        </div>

        {{-- Gallery Preview --}}
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0"><i class="ri-gallery-line me-1"></i> Gallery</h6>
                <a href="{{ route('admin.hotels.images', $hotel->id) }}" class="btn btn-sm btn-outline-primary">Manage</a>
            </div>
            <div class="card-body">
                @if($hotel->images->count() > 0)
                <div class="row g-2">
                    @foreach($hotel->images->take(6) as $image)
                    <div class="col-md-4">
                        <img src="{{ asset('storage/' . $image->path) }}" alt="{{ $image->caption }}" class="rounded w-100" style="height: 120px; object-fit: cover;">
                    </div>
                    @endforeach
                </div>
                @if($hotel->images->count() > 6)
                    <p class="text-muted mt-2 text-center">+{{ $hotel->images->count() - 6 }} more images</p>
                @endif
                @else
                    <p class="text-muted mb-0">No images uploaded.</p>
                @endif
            </div>
        </div>

        {{-- Policies --}}
        <div class="card mb-4">
            <div class="card-header"><h6 class="mb-0"><i class="ri-shield-check-line me-1"></i> Policies</h6></div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <small class="text-muted d-block">Check-in Time</small>
                        <strong>{{ $hotel->check_in_time ?? '2:00 PM' }}</strong>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block">Check-out Time</small>
                        <strong>{{ $hotel->check_out_time ?? '11:00 AM' }}</strong>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block">Cancellation</small>
                        <strong>{{ Str::limit($hotel->cancellation_policy ?? 'Standard policy', 50) }}</strong>
                    </div>
                </div>
                @if($hotel->policies->count() > 0)
                <hr>
                @foreach($hotel->policies as $policy)
                    <div class="mb-2">
                        <span class="badge bg-secondary me-1">{{ ucfirst(str_replace('_', ' ', $policy->type)) }}</span>
                        <strong>{{ $policy->title }}</strong>
                        <p class="mb-0 text-muted small">{{ $policy->description }}</p>
                    </div>
                @endforeach
                @endif
            </div>
        </div>

        {{-- Nearby Places --}}
        @if($hotel->nearby_places->count() > 0)
        <div class="card mb-4">
            <div class="card-header"><h6 class="mb-0"><i class="ri-map-pin-2-line me-1"></i> Nearby Places</h6></div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead><tr><th>Place</th><th>Type</th><th>Distance</th></tr></thead>
                        <tbody>
                            @foreach($hotel->nearby_places as $place)
                            <tr>
                                <td>{{ $place->name }}</td>
                                <td><span class="badge bg-light text-dark">{{ $place->type }}</span></td>
                                <td>{{ $place->distance }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif
    </div>

    {{-- Right Column --}}
    <div class="col-lg-4">
        {{-- Info Card --}}
        <div class="card mb-4">
            <div class="card-header"><h6 class="mb-0"><i class="ri-information-line me-1"></i> Hotel Information</h6></div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li class="mb-2"><strong>Company:</strong> {{ $hotel->company->name ?? '-' }}</li>
                    <li class="mb-2"><strong>Branch:</strong> {{ $hotel->branch->name ?? '-' }}</li>
                    <li class="mb-2"><strong>Phone:</strong> {{ $hotel->phone ?? '-' }}</li>
                    <li class="mb-2"><strong>Email:</strong> {{ $hotel->email ?? '-' }}</li>
                    <li class="mb-2"><strong>Website:</strong> {{ $hotel->website ?? '-' }}</li>
                    <li class="mb-2"><strong>Created:</strong> {{ $hotel->created_at->format('d M Y') }}</li>
                </ul>
            </div>
        </div>

        {{-- Address Card --}}
        <div class="card mb-4">
            <div class="card-header"><h6 class="mb-0"><i class="ri-map-pin-line me-1"></i> Address</h6></div>
            <div class="card-body">
                <p class="mb-1">{{ $hotel->address ?? '-' }}</p>
                <p class="mb-1">{{ $hotel->city ?? '' }}{{ $hotel->state ? ', ' . $hotel->state : '' }}</p>
                <p class="mb-1">{{ $hotel->country ?? '' }}{{ $hotel->zipcode ? ' - ' . $hotel->zipcode : '' }}</p>
                @if($hotel->latitude && $hotel->longitude)
                <hr>
                <p class="mb-0 small text-muted">
                    <strong>Coordinates:</strong> {{ $hotel->latitude }}, {{ $hotel->longitude }}
                </p>
                @endif
            </div>
        </div>

        {{-- SEO Card --}}
        @if($hotel->meta_title || $hotel->meta_description)
        <div class="card mb-4">
            <div class="card-header"><h6 class="mb-0"><i class="ri-search-eye-line me-1"></i> SEO Information</h6></div>
            <div class="card-body">
                @if($hotel->meta_title)
                    <p class="mb-2"><strong>Title:</strong> {{ $hotel->meta_title }}</p>
                @endif
                @if($hotel->meta_description)
                    <p class="mb-2"><strong>Description:</strong> {{ Str::limit($hotel->meta_description, 150) }}</p>
                @endif
                @if($hotel->meta_keywords)
                    <p class="mb-0"><strong>Keywords:</strong> {{ $hotel->meta_keywords }}</p>
                @endif
            </div>
        </div>
        @endif

        {{-- Quick Actions --}}
        <div class="card">
            <div class="card-header"><h6 class="mb-0"><i class="ri-settings-3-line me-1"></i> Quick Actions</h6></div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.hotels.images', $hotel->id) }}" class="btn btn-outline-primary btn-sm"><i class="ri-image-line me-1"></i> Manage Gallery</a>
                    <a href="{{ route('admin.hotels.amenities', $hotel->id) }}" class="btn btn-outline-success btn-sm"><i class="ri-service-line me-1"></i> Manage Amenities</a>
                    <a href="{{ route('admin.hotels.rules', $hotel->id) }}" class="btn btn-outline-warning btn-sm"><i class="ri-list-check-2 me-1"></i> Manage Rules</a>
                    <a href="{{ route('admin.hotels.policies', $hotel->id) }}" class="btn btn-outline-info btn-sm"><i class="ri-shield-check-line me-1"></i> Manage Policies</a>
                    <a href="{{ route('admin.hotels.nearby-places', $hotel->id) }}" class="btn btn-outline-secondary btn-sm"><i class="ri-map-pin-line me-1"></i> Manage Nearby Places</a>
                    @if($hotel->status == 'active')
                        <form action="{{ route('admin.hotels.update-status', [$hotel->id, 'inactive']) }}" method="POST">@csrf @method('PATCH')
                            <button type="submit" class="btn btn-outline-danger btn-sm w-100"><i class="ri-forbid-line me-1"></i> Deactivate</button>
                        </form>
                    @else
                        <form action="{{ route('admin.hotels.update-status', [$hotel->id, 'active']) }}" method="POST">@csrf @method('PATCH')
                            <button type="submit" class="btn btn-outline-success btn-sm w-100"><i class="ri-check-line me-1"></i> Activate</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

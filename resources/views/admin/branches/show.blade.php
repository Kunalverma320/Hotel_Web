@extends('admin.layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Branch: {{ $branch->name }}</h4>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.branches.edit', $branch->id) }}" class="btn btn-warning"><i class="ri-edit-line me-1"></i> Edit</a>
        <a href="{{ route('admin.branches.index') }}" class="btn btn-outline-secondary"><i class="ri-arrow-left-line me-1"></i> Back</a>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif

<div class="row g-4">
    {{-- Branch Info Card --}}
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-primary bg-opacity-10 rounded p-3 me-3">
                        <i class="ri-building-2-line fs-2 text-primary"></i>
                    </div>
                    <div>
                        <h5 class="mb-0">{{ $branch->name }}</h5>
                        <code>{{ $branch->code }}</code>
                    </div>
                </div>
                @if($branch->status == 'active')
                    <span class="badge bg-success fs-6">Active</span>
                @else
                    <span class="badge bg-danger fs-6">Inactive</span>
                @endif
                <hr>
                <div>
                    <p class="mb-2"><strong><i class="ri-building-line me-1"></i> Company:</strong> {{ $branch->company->name ?? '-' }}</p>
                    <p class="mb-2"><strong><i class="ri-phone-line me-1"></i> Phone:</strong> {{ $branch->phone ?? '-' }}</p>
                    <p class="mb-2"><strong><i class="ri-mail-line me-1"></i> Email:</strong> {{ $branch->email ?? '-' }}</p>
                    <p class="mb-0"><strong><i class="ri-user-line me-1"></i> Manager:</strong> {{ $branch->manager->name ?? '-' }}</p>
                </div>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header"><h6 class="mb-0"><i class="ri-map-pin-line me-1"></i> Address</h6></div>
            <div class="card-body">
                <p class="mb-1">{{ $branch->address ?? '-' }}</p>
                <p class="mb-1">{{ $branch->city ?? '' }}{{ $branch->state ? ', ' . $branch->state : '' }}</p>
                <p class="mb-0">{{ $branch->country ?? '' }}{{ $branch->zipcode ? ' - ' . $branch->zipcode : '' }}</p>
            </div>
        </div>
    </div>

    {{-- Hotels under this Branch --}}
    <div class="col-lg-8">
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card bg-primary bg-opacity-10 border-primary">
                    <div class="card-body text-center">
                        <h3 class="mb-0 text-primary">{{ $branch->hotels_count ?? $branch->hotels->count() }}</h3>
                        <small class="text-muted">Hotels</small>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-success bg-opacity-10 border-success">
                    <div class="card-body text-center">
                        <h3 class="mb-0 text-success">{{ $branch->rooms_count ?? 0 }}</h3>
                        <small class="text-muted">Total Rooms</small>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-warning bg-opacity-10 border-warning">
                    <div class="card-body text-center">
                        <h3 class="mb-0 text-warning">{{ $branch->bookings_count ?? 0 }}</h3>
                        <small class="text-muted">Bookings</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0"><i class="ri-hotel-bed-line me-1"></i> Hotels under this Branch</h6>
                <a href="{{ route('admin.hotels.create', ['branch_id' => $branch->id, 'company_id' => $branch->company_id]) }}" class="btn btn-sm btn-primary"><i class="ri-add-line"></i> Add Hotel</a>
            </div>
            <div class="card-body">
                @if($branch->hotels->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Hotel</th>
                                <th>Star Rating</th>
                                <th>City</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($branch->hotels as $index => $hotel)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($hotel->logo)
                                            <img src="{{ asset('storage/' . $hotel->logo) }}" class="rounded me-2" style="width: 35px; height: 35px; object-fit: cover;">
                                        @endif
                                        <a href="{{ route('admin.hotels.show', $hotel->id) }}">{{ $hotel->name }}</a>
                                    </div>
                                </td>
                                <td>
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="ri-star-{{ $i <= $hotel->star_rating ? 'fill text-warning' : 'line text-muted' }}"></i>
                                    @endfor
                                </td>
                                <td>{{ $hotel->city ?? '-' }}</td>
                                <td>
                                    @if($hotel->status == 'active')
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-danger">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.hotels.show', $hotel->id) }}" class="btn btn-sm btn-outline-info"><i class="ri-eye-line"></i></a>
                                    <a href="{{ route('admin.hotels.edit', $hotel->id) }}" class="btn btn-sm btn-outline-warning"><i class="ri-edit-line"></i></a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                    <p class="text-muted text-center mb-0">No hotels found under this branch.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@extends('admin.layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Company: {{ $company->name }}</h4>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.companies.edit', $company->id) }}" class="btn btn-warning"><i class="ri-edit-line me-1"></i> Edit</a>
        <a href="{{ route('admin.companies.index') }}" class="btn btn-outline-secondary"><i class="ri-arrow-left-line me-1"></i> Back</a>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif

<div class="row g-4">
    {{-- Company Info Card --}}
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body text-center">
                @if($company->logo)
                    <img src="{{ asset('storage/' . $company->logo) }}" alt="{{ $company->name }}" class="rounded mb-3" style="height: 80px;">
                @else
                    <div class="bg-secondary bg-opacity-25 rounded d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                        <i class="ri-building-line fs-1 text-muted"></i>
                    </div>
                @endif
                <h5>{{ $company->name }}</h5>
                <p class="text-muted mb-2">{{ $company->email ?? '-' }}</p>
                @if($company->status == 'active')
                    <span class="badge bg-success fs-6">Active</span>
                @else
                    <span class="badge bg-danger fs-6">Inactive</span>
                @endif
                <hr>
                <div class="text-start">
                    <p class="mb-1"><strong>GST:</strong> {{ $company->gst_number ?? '-' }}</p>
                    <p class="mb-1"><strong>PAN:</strong> {{ $company->pan_number ?? '-' }}</p>
                    <p class="mb-1"><strong>Phone:</strong> {{ $company->phone ?? '-' }}</p>
                    <p class="mb-1"><strong>Website:</strong> {{ $company->website ?? '-' }}</p>
                    <p class="mb-0"><strong>Currency:</strong> {{ $company->currency ?? 'INR' }}</p>
                </div>
            </div>
        </div>

        {{-- Address Card --}}
        <div class="card mt-4">
            <div class="card-header"><h6 class="mb-0"><i class="ri-map-pin-line me-1"></i> Address</h6></div>
            <div class="card-body">
                <p class="mb-1">{{ $company->address ?? '-' }}</p>
                <p class="mb-1">{{ $company->city ?? '' }} {{ $company->state ? ', ' . $company->state : '' }}</p>
                <p class="mb-1">{{ $company->country ?? '' }} {{ $company->zipcode ? '- ' . $company->zipcode : '' }}</p>
            </div>
        </div>
    </div>

    {{-- Main Content --}}
    <div class="col-lg-8">
        {{-- Statistics --}}
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card bg-primary bg-opacity-10 border-primary">
                    <div class="card-body text-center">
                        <h3 class="mb-0 text-primary">{{ $company->branches_count ?? $company->branches->count() }}</h3>
                        <small class="text-muted">Branches</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success bg-opacity-10 border-success">
                    <div class="card-body text-center">
                        <h3 class="mb-0 text-success">{{ $company->hotels_count ?? $company->hotels->count() }}</h3>
                        <small class="text-muted">Hotels</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning bg-opacity-10 border-warning">
                    <div class="card-body text-center">
                        <h3 class="mb-0 text-warning">{{ $company->rooms_count ?? 0 }}</h3>
                        <small class="text-muted">Rooms</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info bg-opacity-10 border-info">
                    <div class="card-body text-center">
                        <h3 class="mb-0 text-info">{{ $company->bookings_count ?? 0 }}</h3>
                        <small class="text-muted">Bookings</small>
                    </div>
                </div>
            </div>
        </div>

        {{-- Settings Overview --}}
        <div class="card mb-4">
            <div class="card-header"><h6 class="mb-0"><i class="ri-settings-3-line me-1"></i> Settings Overview</h6></div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="d-flex align-items-center">
                            <i class="ri-mail-send-line fs-4 text-primary me-2"></i>
                            <div>
                                <small class="text-muted d-block">SMTP</small>
                                <strong>{{ $company->smtp_host ?? 'Not Configured' }}</strong>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="d-flex align-items-center">
                            <i class="ri-chat-1-line fs-4 text-success me-2"></i>
                            <div>
                                <small class="text-muted d-block">SMS Provider</small>
                                <strong>{{ ucfirst($company->sms_provider ?? 'Not Configured') }}</strong>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="d-flex align-items-center">
                            <i class="ri-whatsapp-line fs-4 text-success me-2"></i>
                            <div>
                                <small class="text-muted d-block">WhatsApp</small>
                                <strong>{{ ($company->whatsapp_enabled ?? false) ? 'Enabled' : 'Disabled' }}</strong>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="d-flex align-items-center">
                            <i class="ri-bank-card-line fs-4 text-warning me-2"></i>
                            <div>
                                <small class="text-muted d-block">Payment Gateway</small>
                                <strong>{{ ucfirst($company->payment_gateway ?? 'manual') }}</strong>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="d-flex align-items-center">
                            <i class="ri-map-pin-line fs-4 text-info me-2"></i>
                            <div>
                                <small class="text-muted d-block">Google Maps</small>
                                <strong>{{ ($company->google_maps_enabled ?? false) ? 'Enabled' : 'Disabled' }}</strong>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="d-flex align-items-center">
                            <i class="ri-time-zone-line fs-4 text-secondary me-2"></i>
                            <div>
                                <small class="text-muted d-block">Timezone</small>
                                <strong>{{ $company->timezone ?? 'Asia/Kolkata' }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Branch List --}}
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0"><i class="ri-building-2-line me-1"></i> Branches</h6>
                <a href="{{ route('admin.branches.create', ['company_id' => $company->id]) }}" class="btn btn-sm btn-primary"><i class="ri-add-line"></i> Add</a>
            </div>
            <div class="card-body">
                @if($company->branches->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Code</th>
                                <th>City</th>
                                <th>Hotels</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($company->branches as $index => $branch)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td><a href="{{ route('admin.branches.show', $branch->id) }}">{{ $branch->name }}</a></td>
                                <td><code>{{ $branch->code }}</code></td>
                                <td>{{ $branch->city ?? '-' }}</td>
                                <td><span class="badge bg-secondary">{{ $branch->hotels_count ?? $branch->hotels->count() }}</span></td>
                                <td>
                                    @if($branch->status == 'active')
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-danger">Inactive</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                    <p class="text-muted text-center mb-0">No branches found.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

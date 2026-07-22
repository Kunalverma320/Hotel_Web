@extends('admin.layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Hotels</h4>
    <a href="{{ route('admin.hotels.create') }}" class="btn btn-primary">
        <i class="ri-add-line me-1"></i> Add Hotel
    </a>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Hotel List</h5>
        <form action="{{ route('admin.hotels.index') }}" method="GET" class="d-flex gap-2">
            <input type="text" name="search" class="form-control form-control-sm" placeholder="Search hotels..." value="{{ request('search') }}">
            <select name="company_id" class="form-select form-select-sm" style="width: 170px;">
                <option value="">All Companies</option>
                @foreach($companies as $id => $name)
                    <option value="{{ $id }}" {{ request('company_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                @endforeach
            </select>
            <select name="status" class="form-select form-select-sm" style="width: 130px;">
                <option value="">All Status</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
            <button type="submit" class="btn btn-sm btn-outline-primary"><i class="ri-search-line"></i></button>
            <a href="{{ route('admin.hotels.index') }}" class="btn btn-sm btn-outline-secondary"><i class="ri-refresh-line"></i></a>
        </form>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover table-striped align-middle">
                <thead class="table-dark">
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th>Image</th>
                        <th>Hotel Name</th>
                        <th>Company</th>
                        <th>Branch</th>
                        <th>City</th>
                        <th>Rating</th>
                        <th>Status</th>
                        <th style="width: 180px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($hotels as $hotel)
                    <tr>
                        <td>{{ $hotels->firstItem() + $loop->index }}</td>
                        <td>
                            @if($hotel->cover_image)
                                <img src="{{ asset('storage/' . $hotel->cover_image) }}" alt="{{ $hotel->name }}" class="rounded" style="width: 50px; height: 35px; object-fit: cover;">
                            @elseif($hotel->logo)
                                <img src="{{ asset('storage/' . $hotel->logo) }}" alt="{{ $hotel->name }}" class="rounded" style="width: 50px; height: 35px; object-fit: cover;">
                            @else
                                <div class="bg-secondary bg-opacity-25 rounded d-flex align-items-center justify-content-center" style="width: 50px; height: 35px;">
                                    <i class="ri-hotel-bed-line text-muted"></i>
                                </div>
                            @endif
                        </td>
                        <td>
                            <strong>{{ $hotel->name }}</strong>
                            @if($hotel->tagline)
                                <br><small class="text-muted">{{ Str::limit($hotel->tagline, 40) }}</small>
                            @endif
                        </td>
                        <td>{{ $hotel->company->name ?? '-' }}</td>
                        <td>{{ $hotel->branch->name ?? '-' }}</td>
                        <td>{{ $hotel->city ?? '-' }}</td>
                        <td>
                            @for($i = 1; $i <= 5; $i++)
                                <i class="ri-star-{{ $i <= $hotel->star_rating ? 'fill text-warning' : 'line text-muted' }} fs-6"></i>
                            @endfor
                        </td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-{{ $hotel->status == 'active' ? 'success' : 'danger' }} dropdown-toggle" data-bs-toggle="dropdown">
                                    {{ ucfirst($hotel->status) }}
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('hstatus-{{ $hotel->id }}-active').submit();">Active</a></li>
                                    <li><a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('hstatus-{{ $hotel->id }}-inactive').submit();">Inactive</a></li>
                                </ul>
                                <form id="hstatus-{{ $hotel->id }}-active" action="{{ route('admin.hotels.update-status', [$hotel->id, 'active']) }}" method="POST" class="d-none">@csrf @method('PATCH')</form>
                                <form id="hstatus-{{ $hotel->id }}-inactive" action="{{ route('admin.hotels.update-status', [$hotel->id, 'inactive']) }}" method="POST" class="d-none">@csrf @method('PATCH')</form>
                            </div>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.hotels.show', $hotel->id) }}" class="btn btn-outline-info" title="View"><i class="ri-eye-line"></i></a>
                                <a href="{{ route('admin.hotels.edit', $hotel->id) }}" class="btn btn-outline-warning" title="Edit"><i class="ri-edit-line"></i></a>
                                <a href="{{ route('admin.hotels.images', $hotel->id) }}" class="btn btn-outline-secondary" title="Images"><i class="ri-image-line"></i></a>
                                <form action="{{ route('admin.hotels.destroy', $hotel->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this hotel?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger" title="Delete"><i class="ri-delete-bin-line"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-4">
                            <i class="ri-hotel-bed-line fs-1 text-muted d-block mb-2"></i>
                            No hotels found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-end">
            {{ $hotels->withQueryString()->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection

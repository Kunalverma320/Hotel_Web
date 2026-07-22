@extends('admin.layouts.app')
@section('title', 'Guests')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0">Guests</h4>
        <small class="text-muted">Manage hotel guests</small>
    </div>
    <a href="{{ route('admin.guests.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i> Add Guest</a>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.guests.index') }}">
            <div class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label">Search</label>
                    <input type="text" name="search" class="form-control form-control-sm" placeholder="Name, email, phone..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Nationality</label>
                    <select name="nationality" class="form-select form-select-sm">
                        <option value="">All</option>
                        @foreach($nationalities as $nat)
                            <option value="{{ $nat }}" {{ request('nationality') == $nat ? 'selected' : '' }}>{{ $nat }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Loyalty Tier</label>
                    <select name="loyalty_tier" class="form-select form-select-sm">
                        <option value="">All</option>
                        @foreach(['standard','silver','gold','platinum','diamond'] as $tier)
                            <option value="{{ $tier }}" {{ request('loyalty_tier') == $tier ? 'selected' : '' }}>{{ ucfirst($tier) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Blacklisted</label>
                    <select name="is_blacklisted" class="form-select form-select-sm">
                        <option value="">All</option>
                        <option value="1" {{ request('is_blacklisted') == '1' ? 'selected' : '' }}>Yes</option>
                        <option value="0" {{ request('is_blacklisted') == '0' ? 'selected' : '' }}>No</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-1">
                    <button type="submit" class="btn btn-sm btn-primary"><i class="bi bi-search"></i></button>
                    <a href="{{ route('admin.guests.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-counterclockwise"></i></a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Guest</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Nationality</th>
                    <th>Loyalty</th>
                    <th>Bookings</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($guests as $guest)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-2" style="width:36px;height:36px;font-size:0.85rem;">
                                    {{ strtoupper(substr($guest->first_name, 0, 1)) }}
                                </div>
                                <div>
                                    <a href="{{ route('admin.guests.show', $guest->id) }}" class="fw-semibold text-decoration-none">{{ $guest->full_name }}</a>
                                    @if($guest->company_name)<div class="small text-muted">{{ $guest->company_name }}</div>@endif
                                </div>
                            </div>
                        </td>
                        <td>{{ $guest->email ?? '-' }}</td>
                        <td>{{ $guest->phone ?? '-' }}</td>
                        <td>{{ $guest->nationality ?? '-' }}</td>
                        <td><span class="badge bg-{{ ['silver'=>'secondary','gold'=>'warning','platinum'=>'info','diamond'=>'primary'][$guest->loyalty_tier] ?? 'light text-dark' }}">{{ ucfirst($guest->loyalty_tier ?? 'Standard') }}</span></td>
                        <td><span class="badge bg-info">{{ $guest->bookings_count ?? $guest->bookings->count() }}</span></td>
                        <td>
                            @if($guest->is_blacklisted)
                                <span class="badge bg-danger"><i class="bi bi-slash-circle me-1"></i>Blacklisted</span>
                            @else
                                <span class="badge bg-success">Active</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <a href="{{ route('admin.guests.show', $guest->id) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('admin.guests.edit', $guest->id) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="text-center py-4 text-muted">No guests found</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $guests->withQueryString()->links() }}</div>
@endsection

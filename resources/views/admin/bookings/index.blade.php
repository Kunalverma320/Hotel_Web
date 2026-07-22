@extends('admin.layouts.app')
@section('title', 'Bookings')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0">Bookings</h4>
        <small class="text-muted">Manage all hotel bookings</small>
    </div>
    <a href="{{ route('admin.bookings.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i> New Booking</a>
</div>

<ul class="nav nav-pills mb-4">
    <li class="nav-item">
        <a class="nav-link {{ !request('status') ? 'active' : '' }}" href="{{ route('admin.bookings.index') }}">All <span class="badge bg-light text-dark ms-1">{{ $bookings->total() }}</span></a>
    </li>
    @foreach(['pending' => 'Pending', 'confirmed' => 'Confirmed', 'checked_in' => 'Checked In', 'checked_out' => 'Checked Out', 'cancelled' => 'Cancelled'] as $key => $label)
        <li class="nav-item">
            <a class="nav-link {{ request('status') == $key ? 'active' : '' }}" href="{{ route('admin.bookings.index', ['status' => $key] + request()->except(['status', 'page'])) }}">
                {{ $label }} <span class="badge bg-light text-dark ms-1">{{ $statusCounts[$key] ?? 0 }}</span>
            </a>
        </li>
    @endforeach
</ul>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.bookings.index') }}">
            <div class="row g-3 align-items-end">
                <div class="col-md-2">
                    <label class="form-label">Hotel</label>
                    <select name="hotel_id" class="form-select form-select-sm">
                        <option value="">All Hotels</option>
                        @foreach($hotels as $hotel)
                            <option value="{{ $hotel->id }}" {{ request('hotel_id') == $hotel->id ? 'selected' : '' }}>{{ $hotel->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Room Type</label>
                    <select name="room_type_id" class="form-select form-select-sm">
                        <option value="">All Types</option>
                        @foreach($roomTypes as $type)
                            <option value="{{ $type->id }}" {{ request('room_type_id') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Date From</label>
                    <input type="date" name="date_from" class="form-control form-control-sm" value="{{ request('date_from') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Date To</label>
                    <input type="date" name="date_to" class="form-control form-control-sm" value="{{ request('date_to') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Search</label>
                    <input type="text" name="search" class="form-control form-control-sm" placeholder="Booking #, Guest..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2 d-flex gap-1">
                    <button type="submit" class="btn btn-sm btn-primary"><i class="bi bi-search"></i></button>
                    <a href="{{ route('admin.bookings.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-counterclockwise"></i></a>
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
                    <th>Booking #</th>
                    <th>Guest</th>
                    <th>Room Type</th>
                    <th>Check-in</th>
                    <th>Check-out</th>
                    <th>Nights</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bookings as $booking)
                    <tr>
                        <td><a href="{{ route('admin.bookings.show', $booking->id) }}" class="fw-semibold text-decoration-none">{{ $booking->booking_number }}</a></td>
                        <td>{{ $booking->guest->full_name ?? 'N/A' }}</td>
                        <td>{{ $booking->roomType->name ?? 'N/A' }}</td>
                        <td>{{ $booking->check_in_date->format('M d, Y') }}</td>
                        <td>{{ $booking->check_out_date->format('M d, Y') }}</td>
                        <td>{{ $booking->nights }}</td>
                        <td>${{ number_format($booking->total_amount, 2) }}</td>
                        <td>
                            @php
                                $statusColors = ['pending' => 'warning', 'confirmed' => 'info', 'checked_in' => 'success', 'checked_out' => 'primary', 'cancelled' => 'danger', 'no_show' => 'dark'];
                            @endphp
                            <span class="badge bg-{{ $statusColors[$booking->status] ?? 'secondary' }}">{{ ucfirst(str_replace('_', ' ', $booking->status)) }}</span>
                        </td>
                        <td class="text-end">
                            <a href="{{ route('admin.bookings.show', $booking->id) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a>
                            @if(in_array($booking->status, ['pending', 'confirmed']))
                                <a href="{{ route('admin.bookings.edit', $booking->id) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></a>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="9" class="text-center py-4 text-muted">No bookings found</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $bookings->withQueryString()->links() }}</div>
@endsection

@extends('admin.layouts.app')
@section('title', 'Check-outs')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0">Today's Check-outs</h4>
        <small class="text-muted">Expected and processed check-outs</small>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.checkout') }}">
            <div class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label">Hotel</label>
                    <select name="hotel_id" class="form-select form-select-sm">
                        <option value="">All Hotels</option>
                        @foreach(\App\Models\Hotel::active()->get() as $hotel)
                            <option value="{{ $hotel->id }}" {{ request('hotel_id') == $hotel->id ? 'selected' : '' }}>{{ $hotel->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Search</label>
                    <input type="text" name="search" class="form-control form-control-sm" placeholder="Booking #, Guest..." value="{{ request('search') }}">
                </div>
                <div class="col-md-4 d-flex gap-1">
                    <button type="submit" class="btn btn-sm btn-primary"><i class="bi bi-search"></i> Search</button>
                    <a href="{{ route('admin.checkout') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-counterclockwise"></i></a>
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
                    <th>Room</th>
                    <th>Check-out Date</th>
                    <th>Hotel</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bookings as $booking)
                    @php
                        $hasCheckedOut = $booking->checkOuts->where('status', 'completed')->count() > 0;
                    @endphp
                    <tr class="{{ $booking->check_out_date->isToday() ? 'table-light' : '' }}">
                        <td>
                            <a href="{{ route('admin.bookings.show', $booking->id) }}" class="fw-semibold text-decoration-none">{{ $booking->booking_number }}</a>
                        </td>
                        <td>
                            <div class="fw-semibold">{{ $booking->guest->full_name ?? 'N/A' }}</div>
                            <div class="small text-muted">{{ $booking->guest->phone ?? '' }}</div>
                        </td>
                        <td>
                            @php $activeCheckIn = $booking->checkIns->where('status', 'active')->last(); @endphp
                            {{ $activeCheckIn->room->number ?? 'N/A' }}
                        </td>
                        <td>
                            <div>{{ $booking->check_out_date->format('M d, Y') }}</div>
                            <div class="small text-muted">{{ $booking->check_out_date->format('l') }}</div>
                        </td>
                        <td>{{ $booking->hotel->name ?? 'N/A' }}</td>
                        <td>
                            @if($hasCheckedOut)
                                <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i> Checked Out</span>
                            @elseif($booking->check_out_date->isToday())
                                <span class="badge bg-warning text-dark"><i class="bi bi-clock me-1"></i> Due Today</span>
                            @elseif($booking->check_out_date->isPast())
                                <span class="badge bg-danger"><i class="bi bi-exclamation-triangle me-1"></i> Overdue</span>
                            @else
                                <span class="badge bg-info">Upcoming</span>
                            @endif
                        </td>
                        <td class="text-end">
                            @if(!$hasCheckedOut)
                                <a href="{{ route('admin.checkout.show', $booking->id) }}" class="btn btn-sm btn-warning"><i class="bi bi-box-arrow-right me-1"></i> Check-out</a>
                            @else
                                <a href="{{ route('admin.checkout.show', $booking->id) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye me-1"></i> View</a>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center py-4 text-muted">No check-outs found for today</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $bookings->withQueryString()->links() }}</div>
@endsection

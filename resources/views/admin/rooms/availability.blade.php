@extends('admin.layouts.app')
@section('title', 'Room Availability')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0">Room Availability</h4>
        <small class="text-muted">Calendar view of room availability</small>
    </div>
    <a href="{{ route('admin.rooms.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i> Back to Rooms</a>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.rooms.availability') }}" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label">Hotel</label>
                <select name="hotel_id" class="form-select form-select-sm" required>
                    @foreach($hotels as $hotel)
                        <option value="{{ $hotel->id }}" {{ $hotelId == $hotel->id ? 'selected' : '' }}>{{ $hotel->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Start Date</label>
                <input type="date" name="start_date" class="form-control form-control-sm" value="{{ $startDate }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">End Date</label>
                <input type="date" name="end_date" class="form-control form-control-sm" value="{{ $endDate }}">
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-search me-1"></i> Load</button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-bordered mb-0 availability-grid">
                <thead>
                    <tr>
                        <th class="sticky-col" style="min-width:120px;">Room</th>
                        <th class="sticky-col-2" style="min-width:100px;">Type</th>
                        <th class="sticky-col-3" style="min-width:80px;">Status</th>
                        @php
                            $start = \Carbon\Carbon::parse($startDate);
                            $end = \Carbon\Carbon::parse($endDate);
                            $days = $start->diffInDays($end) + 1;
                        @endphp
                        @for($i = 0; $i < min($days, 31); $i++)
                            @php $date = $start->copy()->addDays($i); @endphp
                            <th class="text-center {{ $date->isToday() ? 'table-primary' : '' }}" style="min-width:50px;">
                                <div class="small">{{ $date->format('D') }}</div>
                                <div class="fw-semibold">{{ $date->format('d') }}</div>
                            </th>
                        @endfor
                    </tr>
                </thead>
                <tbody>
                    @forelse($rooms as $room)
                        <tr>
                            <td class="sticky-col fw-semibold">{{ $room->number }}</td>
                            <td class="sticky-col-2 small">{{ $room->roomType->name ?? 'N/A' }}</td>
                            <td class="sticky-col-3">
                                <span class="badge bg-{{ $room->status == 'available' ? 'success' : ($room->status == 'occupied' ? 'danger' : ($room->status == 'maintenance' ? 'warning' : 'dark')) }}">
                                    {{ ucfirst($room->status) }}
                                </span>
                            </td>
                            @for($i = 0; $i < min($days, 31); $i++)
                                @php $date = $start->copy()->addDays($i); @endphp
                                <td class="text-center p-1 {{ $date->isToday() ? 'table-primary' : '' }}">
                                    <div class="avail-cell avail-{{ $room->status == 'available' ? 'free' : ($room->status == 'occupied' ? 'booked' : 'blocked') }}" title="Room {{ $room->number }} - {{ $date->format('M d') }}">
                                    </div>
                                </td>
                            @endfor
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ min($days, 31) + 3 }}" class="text-center py-4 text-muted">No rooms found for this hotel</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-3 d-flex gap-3">
    <span><span class="d-inline-block rounded avail-cell avail-free me-1"></span> Available</span>
    <span><span class="d-inline-block rounded avail-cell avail-booked me-1"></span> Booked</span>
    <span><span class="d-inline-block rounded avail-cell avail-blocked me-1"></span> Blocked/Maintenance</span>
</div>
@endsection

@push('styles')
<style>
    .sticky-col { position: sticky; left: 0; z-index: 2; background: #fff; }
    .sticky-col-2 { position: sticky; left: 120px; z-index: 2; background: #fff; }
    .sticky-col-3 { position: sticky; left: 220px; z-index: 2; background: #fff; }
    .avail-cell { width: 100%; height: 24px; border-radius: 3px; }
    .avail-free { background: #d1e7dd; }
    .avail-booked { background: #f8d7da; }
    .avail-blocked { background: #fff3cd; }
    .availability-grid th { white-space: nowrap; }
</style>
@endpush

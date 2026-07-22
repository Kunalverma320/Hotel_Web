@extends('admin.layouts.app')

@section('title', 'Event: ' . $event->title)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-calendar-event"></i> {{ $event->title }}</h4>
    <div>
        <a href="{{ route('admin.events.edit', $event->id) }}" class="btn btn-outline-primary">
            <i class="bi bi-pencil"></i> Edit
        </a>
        <a href="{{ route('admin.events.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Event Details</h6>
                @switch($event->status)
                    @case('planning')
                        <span class="badge bg-warning text-dark fs-6">Planning</span>
                        @break
                    @case('confirmed')
                        <span class="badge bg-success fs-6">Confirmed</span>
                        @break
                    @case('cancelled')
                        <span class="badge bg-danger fs-6">Cancelled</span>
                        @break
                    @case('completed')
                        <span class="badge bg-secondary fs-6">Completed</span>
                        @break
                @endswitch
            </div>
            <div class="card-body">
                @if($event->description)
                    <p class="text-muted">{{ $event->description }}</p>
                @endif
                <hr>
                <div class="row">
                    <div class="col-md-6">
                        <small class="text-muted">Date</small><br>
                        <strong><i class="bi bi-calendar"></i> {{ \Carbon\Carbon::parse($event->event_date)->format('l, F d, Y') }}</strong>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted">Time</small><br>
                        <strong><i class="bi bi-clock"></i> {{ $event->start_time }} - {{ $event->end_time }}</strong>
                    </div>
                    <div class="col-md-6 mt-3">
                        <small class="text-muted">Venue</small><br>
                        <strong><i class="bi bi-geo-alt"></i> {{ $event->venue->name ?? 'N/A' }}</strong>
                        @if($event->venue)
                            <br><small class="text-muted">Capacity: {{ $event->venue->capacity ?? 'N/A' }}</small>
                        @endif
                    </div>
                    <div class="col-md-6 mt-3">
                        <small class="text-muted">Expected Guests</small><br>
                        <strong><i class="bi bi-people"></i> {{ $event->expected_guests }} guests</strong>
                    </div>
                    <div class="col-md-6 mt-3">
                        <small class="text-muted">Budget</small><br>
                        <strong><i class="bi bi-currency-dollar"></i> {{ $event->budget ? number_format($event->budget, 2) : 'Not set' }}</strong>
                    </div>
                    <div class="col-md-6 mt-3">
                        <small class="text-muted">Created</small><br>
                        <strong>{{ $event->created_at->format('M d, Y h:i A') }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0"><i class="bi bi-arrow-repeat"></i> Update Status</h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    @if($event->status !== 'confirmed')
                        <a href="{{ route('admin.events.update-status', [$event->id, 'confirmed']) }}" class="btn btn-success">
                            <i class="bi bi-check-lg"></i> Confirm Event
                        </a>
                    @endif
                    @if($event->status !== 'completed')
                        <a href="{{ route('admin.events.update-status', [$event->id, 'completed']) }}" class="btn btn-primary">
                            <i class="bi bi-flag"></i> Mark Completed
                        </a>
                    @endif
                    @if($event->status !== 'cancelled')
                        <a href="{{ route('admin.events.update-status', [$event->id, 'cancelled']) }}" class="btn btn-outline-danger">
                            <i class="bi bi-x-lg"></i> Cancel Event
                        </a>
                    @endif
                    @if($event->status !== 'planning')
                        <a href="{{ route('admin.events.update-status', [$event->id, 'planning']) }}" class="btn btn-outline-warning">
                            <i class="bi bi-arrow-counterclockwise"></i> Revert to Planning
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Quick Info</h6>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width:50px;height:50px;">
                        <i class="bi bi-calendar-event" style="font-size:20px;"></i>
                    </div>
                    <div>
                        <strong>{{ $event->title }}</strong>
                        <br><small class="text-muted">{{ \Carbon\Carbon::parse($event->event_date)->diffForHumans() }}</small>
                    </div>
                </div>
                <hr>
                <div class="text-center">
                    <div class="d-inline-flex align-items-center justify-content-center bg-light rounded-circle mb-2" style="width:80px;height:80px;">
                        <div>
                            <h3 class="mb-0 text-primary">{{ $event->expected_guests }}</h3>
                            <small class="text-muted">Guests</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

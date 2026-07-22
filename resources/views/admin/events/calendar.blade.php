@extends('admin.layouts.app')

@section('title', 'Event Calendar')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-calendar3"></i> Event Calendar</h4>
    <div>
        <a href="{{ route('admin.events.create') }}" class="btn btn-primary me-2">
            <i class="bi bi-plus-lg"></i> New Event
        </a>
        <a href="{{ route('admin.events.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-list"></i> List View
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <button class="btn btn-outline-primary" id="prevMonth"><i class="bi bi-chevron-left"></i> Previous</button>
            <h5 class="mb-0" id="currentMonth">{{ now()->format('F Y') }}</h5>
            <button class="btn btn-outline-primary" id="nextMonth">Next <i class="bi bi-chevron-right"></i></button>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered calendar-table mb-0">
                <thead>
                    <tr>
                        <th class="text-center" style="width:14.28%">Sunday</th>
                        <th class="text-center" style="width:14.28%">Monday</th>
                        <th class="text-center" style="width:14.28%">Tuesday</th>
                        <th class="text-center" style="width:14.28%">Wednesday</th>
                        <th class="text-center" style="width:14.28%">Thursday</th>
                        <th class="text-center" style="width:14.28%">Friday</th>
                        <th class="text-center" style="width:14.28%">Saturday</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $startDate = now()->startOfMonth()->startOfWeek();
                        $endDate = now()->endOfMonth()->endOfWeek();
                    @endphp
                    @for($date = $startDate->copy(); $date->lte($endDate); $date->addDay())
                        @if($date->dayOfWeek === 0)
                            <tr>
                        @endif
                        <td style="height:120px;vertical-align:top;" class="{{ $date->isToday() ? 'table-primary' : '' }} {{ $date->month !== now()->month ? 'bg-light' : '' }}">
                            <div class="d-flex justify-content-between">
                                <span class="{{ $date->isToday() ? 'fw-bold text-primary' : '' }}" style="font-size:0.85rem;">
                                    {{ $date->format('d') }}
                                </span>
                            </div>
                            @php
                                $dayEvents = $events->where('event_date', $date->format('Y-m-d'));
                            @endphp
                            @foreach($dayEvents as $event)
                                <a href="{{ route('admin.events.show', $event->id) }}" class="d-block text-decoration-none mt-1">
                                    <div class="badge w-100 text-start {{ $event->status === 'confirmed' ? 'bg-success' : ($event->status === 'cancelled' ? 'bg-danger' : ($event->status === 'completed' ? 'bg-secondary' : 'bg-primary')) }}" style="font-size:0.7rem;">
                                        {{ \Carbon\Carbon::parse($event->start_time)->format('g:i A') }} {{ Str::limit($event->title, 15) }}
                                    </div>
                                </a>
                            @endforeach
                        </td>
                        @if($date->dayOfWeek === 6)
                            </tr>
                        @endif
                    @endfor
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="row g-3 mt-3">
    <div class="col-md-3">
        <div class="card">
            <div class="card-body py-2">
                <span class="badge bg-primary me-1">&nbsp;&nbsp;&nbsp;</span>
                <small>Planning</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body py-2">
                <span class="badge bg-success me-1">&nbsp;&nbsp;&nbsp;</span>
                <small>Confirmed</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body py-2">
                <span class="badge bg-danger me-1">&nbsp;&nbsp;&nbsp;</span>
                <small>Cancelled</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body py-2">
                <span class="badge bg-secondary me-1">&nbsp;&nbsp;&nbsp;</span>
                <small>Completed</small>
            </div>
        </div>
    </div>
</div>

<style>
    .calendar-table td { padding: 8px; }
    .calendar-table th { padding: 10px; background: #f8f9fa; }
</style>
@endsection

@extends('admin.layouts.app')

@section('title', 'Events Management')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-calendar-event"></i> Events Management</h4>
    <div>
        <a href="{{ route('admin.events.calendar') }}" class="btn btn-outline-primary me-2">
            <i class="bi bi-calendar3"></i> Calendar View
        </a>
        <a href="{{ route('admin.events.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> New Event
        </a>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.events.index') }}" class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="planning" {{ request('status') == 'planning' ? 'selected' : '' }}>Planning</option>
                    <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                </select>
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-primary me-2"><i class="bi bi-search"></i> Filter</button>
                <a href="{{ route('admin.events.index') }}" class="btn btn-outline-secondary">Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Event</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Venue</th>
                        <th>Guests</th>
                        <th>Budget</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($events as $event)
                        <tr>
                            <td>{{ $event->id }}</td>
                            <td><strong>{{ $event->title }}</strong></td>
                            <td>{{ \Carbon\Carbon::parse($event->event_date)->format('M d, Y') }}</td>
                            <td>{{ $event->start_time }} - {{ $event->end_time }}</td>
                            <td>{{ $event->venue->name ?? 'N/A' }}</td>
                            <td><span class="badge bg-info">{{ $event->expected_guests }} guests</span></td>
                            <td>{{ $event->budget ? '$' . number_format($event->budget, 2) : '-' }}</td>
                            <td>
                                @switch($event->status)
                                    @case('planning')
                                        <span class="badge bg-warning text-dark">Planning</span>
                                        @break
                                    @case('confirmed')
                                        <span class="badge bg-success">Confirmed</span>
                                        @break
                                    @case('cancelled')
                                        <span class="badge bg-danger">Cancelled</span>
                                        @break
                                    @case('completed')
                                        <span class="badge bg-secondary">Completed</span>
                                        @break
                                    @default
                                        <span class="badge bg-secondary">{{ ucfirst($event->status) }}</span>
                                @endswitch
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.events.show', $event->id) }}" class="btn btn-outline-primary"><i class="bi bi-eye"></i></a>
                                    <a href="{{ route('admin.events.edit', $event->id) }}" class="btn btn-outline-secondary"><i class="bi bi-pencil"></i></a>
                                    <form action="{{ route('admin.events.destroy', $event->id) }}" method="POST" onsubmit="return confirm('Delete this event?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-outline-danger"><i class="bi bi-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-4 text-muted">No events found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer">
        {{ $events->links() }}
    </div>
</div>
@endsection

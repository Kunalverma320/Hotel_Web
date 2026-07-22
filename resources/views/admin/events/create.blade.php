@extends('admin.layouts.app')

@section('title', isset($event) ? 'Edit Event' : 'New Event')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">
        <i class="bi bi-calendar-event"></i>
        {{ isset($event) ? 'Edit Event: ' . $event->title : 'New Event' }}
    </h4>
    <a href="{{ route('admin.events.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Back
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ isset($event) ? route('admin.events.update', $event->id) : route('admin.events.store') }}" method="POST">
            @csrf
            @if(isset($event))
                @method('PUT')
            @endif

            <div class="row g-3">
                <div class="col-md-8">
                    <label class="form-label">Event Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-control" value="{{ old('title', $event->title ?? '') }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="planning" {{ old('status', $event->status ?? '') == 'planning' ? 'selected' : '' }}>Planning</option>
                        <option value="confirmed" {{ old('status', $event->status ?? '') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                        <option value="cancelled" {{ old('status', $event->status ?? '') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        <option value="completed" {{ old('status', $event->status ?? '') == 'completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="3">{{ old('description', $event->description ?? '') }}</textarea>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Event Date <span class="text-danger">*</span></label>
                    <input type="date" name="event_date" class="form-control" value="{{ old('event_date', $event->event_date ?? '') }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Start Time <span class="text-danger">*</span></label>
                    <input type="time" name="start_time" class="form-control" value="{{ old('start_time', $event->start_time ?? '') }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">End Time <span class="text-danger">*</span></label>
                    <input type="time" name="end_time" class="form-control" value="{{ old('end_time', $event->end_time ?? '') }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Venue <span class="text-danger">*</span></label>
                    <select name="venue_id" class="form-select" required>
                        <option value="">-- Select Venue --</option>
                        @foreach($venues as $venue)
                            <option value="{{ $venue->id }}" {{ old('venue_id', $event->venue_id ?? '') == $venue->id ? 'selected' : '' }}>
                                {{ $venue->name }} (Cap: {{ $venue->capacity ?? 'N/A' }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Expected Guests <span class="text-danger">*</span></label>
                    <input type="number" name="expected_guests" class="form-control" value="{{ old('expected_guests', $event->expected_guests ?? '') }}" min="1" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Budget</label>
                    <input type="number" name="budget" class="form-control" value="{{ old('budget', $event->budget ?? '') }}" step="0.01" min="0">
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="bi bi-check-lg"></i> {{ isset($event) ? 'Update Event' : 'Create Event' }}
                </button>
                <a href="{{ route('admin.events.index') }}" class="btn btn-outline-secondary btn-lg ms-2">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection

@extends('admin.layouts.app')

@section('title', 'Pool & Gym Schedules')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-water"></i> Pool & Gym Schedules</h4>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#scheduleModal">
        <i class="bi bi-plus-lg"></i> Add Schedule
    </button>
</div>

<div class="row g-4">
    <div class="col-md-6">
        <div class="card border-primary">
            <div class="card-header bg-primary text-white">
                <h6 class="mb-0"><i class="bi bi-water"></i> Pool Schedule</h6>
            </div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Day</th>
                            <th>Opens</th>
                            <th>Closes</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                            $poolSchedules = $schedules->where('facility', 'pool');
                        @endphp
                        @foreach($days as $day)
                            @php $sched = $poolSchedules->where('day', $day)->first(); @endphp
                            <tr>
                                <td><strong>{{ $day }}</strong></td>
                                <td>{{ $sched->open_time ?? '06:00' }}</td>
                                <td>{{ $sched->close_time ?? '22:00' }}</td>
                                <td>
                                    @if($sched && $sched->is_closed)
                                        <span class="badge bg-danger">Closed</span>
                                    @else
                                        <span class="badge bg-success">Open</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card border-success">
            <div class="card-header bg-success text-white">
                <h6 class="mb-0"><i class="bi bi-bicycle"></i> Gym Schedule</h6>
            </div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Day</th>
                            <th>Opens</th>
                            <th>Closes</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $gymSchedules = $schedules->where('facility', 'gym');
                        @endphp
                        @foreach($days as $day)
                            @php $sched = $gymSchedules->where('day', $day)->first(); @endphp
                            <tr>
                                <td><strong>{{ $day }}</strong></td>
                                <td>{{ $sched->open_time ?? '05:00' }}</td>
                                <td>{{ $sched->close_time ?? '23:00' }}</td>
                                <td>
                                    @if($sched && $sched->is_closed)
                                        <span class="badge bg-danger">Closed</span>
                                    @else
                                        <span class="badge bg-success">Open</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="card mt-4">
    <div class="card-header">
        <h6 class="mb-0">Weekly Overview</h6>
    </div>
    <div class="card-body">
        <div class="row g-2">
            @foreach($days as $day)
                @php
                    $poolSched = $schedules->where('facility', 'pool')->where('day', $day)->first();
                    $gymSched = $schedules->where('facility', 'gym')->where('day', $day)->first();
                @endphp
                <div class="col">
                    <div class="card text-center {{ now()->format('l') === $day ? 'border-primary border-2' : '' }}">
                        <div class="card-body py-2">
                            <h6 class="card-title mb-2" style="font-size:0.8rem;">{{ substr($day, 0, 3) }}</h6>
                            <div class="mb-1">
                                <small class="text-muted">Pool</small><br>
                                @if($poolSched && !$poolSched->is_closed)
                                    <span class="badge bg-info">{{ $poolSched->open_time }}-{{ $poolSched->close_time }}</span>
                                @else
                                    <span class="badge bg-secondary">Closed</span>
                                @endif
                            </div>
                            <div>
                                <small class="text-muted">Gym</small><br>
                                @if($gymSched && !$gymSched->is_closed)
                                    <span class="badge bg-success">{{ $gymSched->open_time }}-{{ $gymSched->close_time }}</span>
                                @else
                                    <span class="badge bg-secondary">Closed</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<div class="modal fade" id="scheduleModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.gym.schedule-store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add Schedule</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Facility</label>
                        <select name="facility" class="form-select" required>
                            <option value="pool">Pool</option>
                            <option value="gym">Gym</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Day</label>
                        <select name="day" class="form-select" required>
                            @foreach($days as $day)
                                <option value="{{ $day }}">{{ $day }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Open Time</label>
                            <input type="time" name="open_time" class="form-control" value="06:00" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Close Time</label>
                            <input type="time" name="close_time" class="form-control" value="22:00" required>
                        </div>
                    </div>
                    <div class="form-check form-switch mt-3">
                        <input class="form-check-input" type="checkbox" name="is_closed" value="1">
                        <label class="form-check-label">Closed on this day</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Schedule</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

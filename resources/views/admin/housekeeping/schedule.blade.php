@extends('admin.layouts.app')

@section('title', 'Housekeeping Schedule')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-calendar3"></i> Housekeeping Schedule</h4>
    <a href="{{ route('admin.housekeeping.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Back
    </a>
</div>

<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="mb-0">Week of {{ now()->startOfWeek()->format('M d, Y') }} - {{ now()->endOfWeek()->format('M d, Y') }}</h6>
        <div>
            <button class="btn btn-sm btn-outline-primary" id="prevWeek"><i class="bi bi-chevron-left"></i></button>
            <button class="btn btn-sm btn-outline-primary" id="todayBtn">Today</button>
            <button class="btn btn-sm btn-outline-primary" id="nextWeek"><i class="bi bi-chevron-right"></i></button>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-bordered mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width:120px;">Time</th>
                        @for($i = 0; $i < 7; $i++)
                            <th class="text-center {{ now()->startOfWeek()->addDays($i)->isToday() ? 'table-primary' : '' }}">
                                {{ now()->startOfWeek()->addDays($i)->format('D') }}
                                <br><small>{{ now()->startOfWeek()->addDays($i)->format('M d') }}</small>
                            </th>
                        @endfor
                    </tr>
                </thead>
                <tbody>
                    @php
                        $hours = ['08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00'];
                    @endphp
                    @foreach($hours as $hour)
                        <tr>
                            <td class="text-muted fw-bold">{{ $hour }}</td>
                            @for($i = 0; $i < 7; $i++)
                                @php
                                    $date = now()->startOfWeek()->addDays($i)->format('Y-m-d');
                                    $taskAtTime = $tasks->where('scheduled_date', $date)->where('scheduled_time', $hour)->first();
                                @endphp
                                <td class="p-1" style="min-height:60px;vertical-align:top;">
                                    @if($taskAtTime)
                                        <div class="p-2 rounded {{ $taskAtTime->status === 'completed' ? 'bg-success bg-opacity-10 border-start border-success border-3' : ($taskAtTime->status === 'in_progress' ? 'bg-warning bg-opacity-10 border-start border-warning border-3' : 'bg-info bg-opacity-10 border-start border-info border-3') }}">
                                            <small class="fw-bold d-block">{{ $taskAtTime->room->room_number ?? 'N/A' }}</small>
                                            <small class="text-muted">{{ $taskAtTime->assignee->name ?? 'Unassigned' }}</small>
                                            <div class="mt-1">
                                                @if($taskAtTime->status === 'completed')
                                                    <span class="badge bg-success bg-opacity-25 text-success">Done</span>
                                                @elseif($taskAtTime->status === 'in_progress')
                                                    <span class="badge bg-warning bg-opacity-25 text-warning">Active</span>
                                                @else
                                                    <span class="badge bg-info bg-opacity-25 text-info">Pending</span>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                </td>
                            @endfor
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-3">
        <div class="card">
            <div class="card-body text-center">
                <span class="badge bg-info bg-opacity-25 text-info fs-6">&nbsp;&nbsp;&nbsp;&nbsp;</span>
                <small class="d-block mt-2">Pending</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body text-center">
                <span class="badge bg-warning bg-opacity-25 text-warning fs-6">&nbsp;&nbsp;&nbsp;&nbsp;</span>
                <small class="d-block mt-2">In Progress</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body text-center">
                <span class="badge bg-success bg-opacity-25 text-success fs-6">&nbsp;&nbsp;&nbsp;&nbsp;</span>
                <small class="d-block mt-2">Completed</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <h6>Summary</h6>
                <small>Total Tasks: <strong>{{ $tasks->count() }}</strong></small><br>
                <small>Completed: <strong class="text-success">{{ $tasks->where('status', 'completed')->count() }}</strong></small><br>
                <small>Pending: <strong class="text-warning">{{ $tasks->where('status', 'pending')->count() }}</strong></small>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('admin.layouts.app')

@section('title', 'Spa Appointments')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-calendar-check"></i> Spa Appointments</h4>
    <a href="{{ route('admin.spa.appointment-create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> New Appointment
    </a>
</div>

<div class="card mb-4">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-2">
                <div class="card text-center bg-primary text-white">
                    <div class="card-body py-3">
                        <h4 class="mb-0">{{ $appointments->total() }}</h4>
                        <small>Total</small>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card text-center bg-warning text-dark">
                    <div class="card-body py-3">
                        <h4 class="mb-0">{{ $appointments->where('status', 'confirmed')->count() }}</h4>
                        <small>Confirmed</small>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card text-center bg-info text-white">
                    <div class="card-body py-3">
                        <h4 class="mb-0">{{ $appointments->where('status', 'in_progress')->count() }}</h4>
                        <small>In Progress</small>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card text-center bg-success text-white">
                    <div class="card-body py-3">
                        <h4 class="mb-0">{{ $appointments->where('status', 'completed')->count() }}</h4>
                        <small>Completed</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Guest</th>
                        <th>Package</th>
                        <th>Therapist</th>
                        <th>Date & Time</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($appointments as $apt)
                        <tr>
                            <td>{{ $apt->id }}</td>
                            <td>
                                <strong>{{ $apt->guest->name ?? $apt->guest_name ?? 'N/A' }}</strong>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark">{{ $apt->package->name ?? 'N/A' }}</span>
                            </td>
                            <td>{{ $apt->therapist->name ?? 'N/A' }}</td>
                            <td>
                                {{ \Carbon\Carbon::parse($apt->appointment_date)->format('M d, Y') }}
                                <br><small class="text-muted">{{ $apt->appointment_time }}</small>
                            </td>
                            <td>
                                @switch($apt->status)
                                    @case('pending')
                                        <span class="badge bg-secondary">Pending</span>
                                        @break
                                    @case('confirmed')
                                        <span class="badge bg-warning text-dark">Confirmed</span>
                                        @break
                                    @case('in_progress')
                                        <span class="badge bg-info">In Progress</span>
                                        @break
                                    @case('completed')
                                        <span class="badge bg-success">Completed</span>
                                        @break
                                    @case('cancelled')
                                        <span class="badge bg-danger">Cancelled</span>
                                        @break
                                    @default
                                        <span class="badge bg-secondary">{{ ucfirst($apt->status) }}</span>
                                @endswitch
                            </td>
                            <td>
                                @if(!in_array($apt->status, ['completed', 'cancelled']))
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">Actions</button>
                                        <ul class="dropdown-menu">
                                            @if($apt->status === 'pending')
                                                <li><a class="dropdown-item" href="{{ route('admin.spa.update-appointment-status', [$apt->id, 'confirmed']) }}">Confirm</a></li>
                                            @endif
                                            @if(in_array($apt->status, ['pending', 'confirmed']))
                                                <li><a class="dropdown-item" href="{{ route('admin.spa.update-appointment-status', [$apt->id, 'in_progress']) }}">Start</a></li>
                                            @endif
                                            @if($apt->status === 'in_progress')
                                                <li><a class="dropdown-item" href="{{ route('admin.spa.update-appointment-status', [$apt->id, 'completed']) }}">Complete</a></li>
                                            @endif
                                            <li><hr class="dropdown-divider"></li>
                                            <li><a class="dropdown-item text-danger" href="{{ route('admin.spa.update-appointment-status', [$apt->id, 'cancelled']) }}">Cancel</a></li>
                                        </ul>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">No appointments found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer">
        {{ $appointments->links() }}
    </div>
</div>
@endsection

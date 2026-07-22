@extends('admin.layouts.app')

@section('title', 'Gym Memberships')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-person-badge"></i> Gym Memberships</h4>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#membershipModal">
        <i class="bi bi-plus-lg"></i> New Membership
    </button>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <h6>Total Members</h6>
                <h2 class="mb-0">{{ $memberships->total() }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h6>Active</h6>
                <h2 class="mb-0">{{ $memberships->where('status', 'active')->count() }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-dark">
            <div class="card-body">
                <h6>Expiring Soon</h6>
                <h2 class="mb-0">{{ $memberships->filter(fn($m) => \Carbon\Carbon::parse($m->end_date)->diffInDays(now()) <= 7)->count() }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-secondary text-white">
            <div class="card-body">
                <h6>Expired</h6>
                <h2 class="mb-0">{{ $memberships->where('status', 'expired')->count() }}</h2>
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
                        <th>Type</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($memberships as $mem)
                        <tr>
                            <td>{{ $mem->id }}</td>
                            <td><strong>{{ $mem->guest->name ?? 'N/A' }}</strong></td>
                            <td>
                                <span class="badge bg-light text-dark">
                                    @switch($mem->type)
                                        @case('daily') Daily @break
                                        @case('weekly') Weekly @break
                                        @case('monthly') Monthly @break
                                        @case('yearly') Yearly @break
                                    @endswitch
                                </span>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($mem->start_date)->format('M d, Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($mem->end_date)->format('M d, Y') }}</td>
                            <td><strong>${{ number_format($mem->amount, 2) }}</strong></td>
                            <td>
                                @php
                                    $isExpired = \Carbon\Carbon::parse($mem->end_date)->isPast();
                                    $isExpiring = \Carbon\Carbon::parse($mem->end_date)->diffInDays(now()) <= 7;
                                @endphp
                                @if($isExpired)
                                    <span class="badge bg-secondary">Expired</span>
                                @elseif($isExpiring)
                                    <span class="badge bg-warning text-dark">Expiring Soon</span>
                                @else
                                    <span class="badge bg-success">Active</span>
                                @endif
                            </td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editMembership{{ $mem->id }}">
                                    <i class="bi bi-pencil"></i>
                                </button>
                            </td>
                        </tr>

                        <div class="modal fade" id="editMembership{{ $mem->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('admin.gym.membership-update', $mem->id) }}" method="POST">
                                        @csrf @method('PUT')
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Membership</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label">End Date</label>
                                                <input type="date" name="end_date" class="form-control" value="{{ $mem->end_date }}">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Amount</label>
                                                <input type="number" name="amount" class="form-control" value="{{ $mem->amount }}" step="0.01">
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-primary">Save</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <tr><td colspan="8" class="text-center py-4 text-muted">No memberships found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer">
        {{ $memberships->links() }}
    </div>
</div>

<div class="modal fade" id="membershipModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.gym.membership-store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">New Gym Membership</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Guest <span class="text-danger">*</span></label>
                        <select name="guest_id" class="form-select" required>
                            <option value="">-- Select Guest --</option>
                            @foreach($guests as $guest)
                                <option value="{{ $guest->id }}">{{ $guest->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Membership Type <span class="text-danger">*</span></label>
                        <select name="type" class="form-select" required>
                            <option value="daily">Daily</option>
                            <option value="weekly">Weekly</option>
                            <option value="monthly" selected>Monthly</option>
                            <option value="yearly">Yearly</option>
                        </select>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Start Date</label>
                            <input type="date" name="start_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">End Date</label>
                            <input type="date" name="end_date" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3 mt-3">
                        <label class="form-label">Amount</label>
                        <input type="number" name="amount" class="form-control" step="0.01" min="0" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Membership</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

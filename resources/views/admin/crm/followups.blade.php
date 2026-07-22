@extends('admin.layouts.app')
@section('title', 'Follow-ups')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0">Follow-ups</h4>
        <small class="text-muted">Manage lead follow-ups and tasks</small>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addFollowupModal"><i class="bi bi-plus-lg me-1"></i> Add Follow-up</button>
</div>

<ul class="nav nav-pills mb-4">
    <li class="nav-item"><a class="nav-link {{ !request('status') ? 'active' : '' }}" href="{{ route('admin.crm.followups') }}">All</a></li>
    <li class="nav-item"><a class="nav-link {{ request('status') == 'pending' ? 'active' : '' }}" href="{{ route('admin.crm.followups', ['status' => 'pending']) }}">Pending</a></li>
    <li class="nav-item"><a class="nav-link {{ request('status') == 'upcoming' ? 'active' : '' }}" href="{{ route('admin.crm.followups', ['status' => 'upcoming']) }}">Upcoming</a></li>
    <li class="nav-item"><a class="nav-link {{ request('status') == 'completed' ? 'active' : '' }}" href="{{ route('admin.crm.followups', ['status' => 'completed']) }}">Completed</a></li>
</ul>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Lead</th>
                    <th>Type</th>
                    <th>Subject</th>
                    <th>Next Follow-up</th>
                    <th>Status</th>
                    <th>Assigned To</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($followups as $fu)
                    <tr>
                        <td>
                            @if($fu->lead)
                                <a href="{{ route('admin.crm.lead-edit', $fu->lead->id) }}" class="fw-semibold text-decoration-none">{{ $fu->lead->full_name }}</a>
                            @else
                                <span class="text-muted">N/A</span>
                            @endif
                        </td>
                        <td><span class="badge bg-secondary">{{ ucfirst($fu->type) }}</span></td>
                        <td>{{ $fu->subject }}</td>
                        <td>
                            @if($fu->next_followup_date)
                                <span class="{{ $fu->next_followup_date->isPast() ? 'text-danger' : '' }}">{{ $fu->next_followup_date->format('M d, Y') }}</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            @if($fu->completed_at)
                                <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Completed</span>
                            @else
                                <span class="badge bg-warning text-dark"><i class="bi bi-clock me-1"></i>Pending</span>
                            @endif
                        </td>
                        <td>{{ $fu->user->name ?? '-' }}</td>
                        <td class="text-muted small">{{ $fu->created_at->format('M d, Y') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center py-4 text-muted">No follow-ups found</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $followups->withQueryString()->links() }}</div>

{{-- Add Follow-up Modal --}}
<div class="modal fade" id="addFollowupModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.crm.add-followup') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Add Follow-up</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Lead <span class="text-danger">*</span></label>
                        <select name="lead_id" class="form-select" required>
                            <option value="">Select Lead</option>
                            @foreach(\App\Models\Lead::whereNotIn('status', ['converted','lost'])->orderBy('first_name')->get() as $lead)
                                <option value="{{ $lead->id }}">{{ $lead->full_name }} ({{ $lead->company ?? 'N/A' }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Type <span class="text-danger">*</span></label>
                            <select name="type" class="form-select" required>
                                @foreach(['call','email','meeting','visit','other'] as $t)
                                    <option value="{{ $t }}">{{ ucfirst($t) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Subject <span class="text-danger">*</span></label>
                            <input type="text" name="subject" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3 mt-3">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Outcome</label>
                            <input type="text" name="outcome" class="form-control" placeholder="Result of this follow-up">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Next Follow-up Date</label>
                            <input type="date" name="next_followup_date" class="form-control">
                        </div>
                    </div>
                    <div class="mb-3 mt-3">
                        <label class="form-label">Completed?</label>
                        <input type="datetime-local" name="completed_at" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Follow-up</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

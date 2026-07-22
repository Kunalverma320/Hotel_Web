@extends('admin.layouts.app')
@section('title', 'CRM Leads')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0">Leads</h4>
        <small class="text-muted">Manage sales leads and prospects</small>
    </div>
    <a href="{{ route('admin.crm.lead-create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i> Add Lead</a>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.crm.leads') }}">
            <div class="row g-3 align-items-end">
                <div class="col-md-2">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select form-select-sm">
                        <option value="">All</option>
                        @foreach(['new','contacted','qualified','proposal','negotiation','converted','lost'] as $s)
                            <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Source</label>
                    <select name="source" class="form-select form-select-sm">
                        <option value="">All</option>
                        @foreach(['website','phone','walk_in','referral','travel_agent','corporate','social_media','other'] as $s)
                            <option value="{{ $s }}" {{ request('source') == $s ? 'selected' : '' }}>{{ ucfirst(str_replace('_',' ',$s)) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Search</label>
                    <input type="text" name="search" class="form-control form-control-sm" placeholder="Name, email, company..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3 d-flex gap-1">
                    <button type="submit" class="btn btn-sm btn-primary"><i class="bi bi-search"></i></button>
                    <a href="{{ route('admin.crm.leads') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-counterclockwise"></i></a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Lead</th>
                    <th>Company</th>
                    <th>Source</th>
                    <th>Status</th>
                    <th>Priority</th>
                    <th>Next Follow-up</th>
                    <th>Assigned To</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($leads as $lead)
                    <tr>
                        <td>
                            <div class="fw-semibold">{{ $lead->full_name }}</div>
                            <div class="small text-muted">{{ $lead->email ?? '' }} {{ $lead->phone ?? '' }}</div>
                        </td>
                        <td>{{ $lead->company ?? '-' }}</td>
                        <td><span class="badge bg-secondary">{{ ucfirst(str_replace('_',' ',$lead->source ?? 'N/A')) }}</span></td>
                        <td>
                            @php
                                $leadStatusColors = ['new'=>'info','contacted'=>'primary','qualified'=>'warning','proposal'=>'secondary','negotiation'=>'dark','converted'=>'success','lost'=>'danger'];
                            @endphp
                            <span class="badge bg-{{ $leadStatusColors[$lead->status] ?? 'secondary' }}">{{ ucfirst($lead->status) }}</span>
                        </td>
                        <td>
                            <span class="badge bg-{{ $lead->priority == 'urgent' ? 'danger' : ($lead->priority == 'high' ? 'warning' : ($lead->priority == 'medium' ? 'info' : 'secondary')) }}">{{ ucfirst($lead->priority ?? 'Low') }}</span>
                        </td>
                        <td>{{ $lead->next_followup_at ? $lead->next_followup_at->format('M d, Y') : '-' }}</td>
                        <td>{{ $lead->assignedTo->name ?? '-' }}</td>
                        <td class="text-end">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.crm.lead-edit', $lead->id) }}" class="btn btn-outline-secondary"><i class="bi bi-pencil"></i></a>
                                @if(!in_array($lead->status, ['converted', 'lost']))
                                    <a href="{{ route('admin.crm.lead-convert', $lead->id) }}" class="btn btn-outline-success" onclick="return confirm('Convert this lead to a guest?')"><i class="bi bi-arrow-right-circle"></i></a>
                                @endif
                                <form method="POST" action="{{ route('admin.crm.lead-delete', $lead->id) }}" class="d-inline" onsubmit="return confirm('Delete this lead?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="text-center py-4 text-muted">No leads found</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $leads->withQueryString()->links() }}</div>
@endsection

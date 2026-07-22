@extends('admin.layouts.app')
@section('title', 'Campaigns')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0">Marketing Campaigns</h4>
        <small class="text-muted">Manage marketing campaigns</small>
    </div>
    <a href="{{ route('admin.crm.create-campaign') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i> Create Campaign</a>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.crm.campaigns') }}">
            <div class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select form-select-sm">
                        <option value="">All</option>
                        @foreach(['draft','scheduled','active','paused','completed','cancelled'] as $s)
                            <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Type</label>
                    <select name="type" class="form-select form-select-sm">
                        <option value="">All</option>
                        @foreach(['email','sms','social','search','promotion','referral','other'] as $t)
                            <option value="{{ $t }}" {{ request('type') == $t ? 'selected' : '' }}>{{ ucfirst($t) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 d-flex gap-1">
                    <button type="submit" class="btn btn-sm btn-primary"><i class="bi bi-search"></i></button>
                    <a href="{{ route('admin.crm.campaigns') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-counterclockwise"></i></a>
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
                    <th>Campaign</th>
                    <th>Hotel</th>
                    <th>Type</th>
                    <th>Budget</th>
                    <th>Spent</th>
                    <th>Impressions</th>
                    <th>Conversions</th>
                    <th>Status</th>
                    <th>Dates</th>
                </tr>
            </thead>
            <tbody>
                @forelse($campaigns as $campaign)
                    <tr>
                        <td>
                            <div class="fw-semibold">{{ $campaign->name }}</div>
                            <div class="small text-muted">{{ Str::limit($campaign->description, 40) }}</div>
                        </td>
                        <td>{{ $campaign->hotel->name ?? 'N/A' }}</td>
                        <td><span class="badge bg-secondary">{{ ucfirst($campaign->type) }}</span></td>
                        <td>${{ number_format($campaign->budget ?? 0, 2) }}</td>
                        <td>${{ number_format($campaign->spent_amount ?? 0, 2) }}</td>
                        <td>{{ number_format($campaign->impressions ?? 0) }}</td>
                        <td>{{ $campaign->conversions ?? 0 }}</td>
                        <td>
                            @php
                                $campColors = ['draft'=>'secondary','scheduled'=>'info','active'=>'success','paused'=>'warning','completed'=>'primary','cancelled'=>'danger'];
                            @endphp
                            <span class="badge bg-{{ $campColors[$campaign->status] ?? 'secondary' }}">{{ ucfirst($campaign->status) }}</span>
                        </td>
                        <td class="small">
                            {{ $campaign->start_date?->format('M d') ?? '-' }} - {{ $campaign->end_date?->format('M d, Y') ?? '-' }}
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="9" class="text-center py-4 text-muted">No campaigns found</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $campaigns->withQueryString()->links() }}</div>
@endsection

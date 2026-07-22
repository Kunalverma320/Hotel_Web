@extends('admin.layouts.app')
@section('title', 'Booking Report')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0"><i class="bi bi-bookmark-check"></i> Booking Report</h1>
    <button class="btn btn-outline-secondary" onclick="window.print()"><i class="bi bi-printer"></i> Print</button>
</div>

<form method="GET" action="{{ route('admin.reports.bookings') }}" class="mb-4">
    <div class="row g-3 align-items-end">
        <div class="col-md-3">
            <label class="form-label">Start Date</label>
            <input type="date" name="start_date" class="form-control" value="{{ $startDate }}">
        </div>
        <div class="col-md-3">
            <label class="form-label">End Date</label>
            <input type="date" name="end_date" class="form-control" value="{{ $endDate }}">
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i> Generate</button>
        </div>
    </div>
</form>

<div class="row g-4 mb-4">
    <div class="col-md-3"><div class="card border-0 shadow-sm"><div class="card-body"><h6 class="text-muted">Total Bookings</h6><h3 class="text-primary">--</h3></div></div></div>
    <div class="col-md-3"><div class="card border-0 shadow-sm"><div class="card-body"><h6 class="text-muted">Confirmed</h6><h3 class="text-success">--</h3></div></div></div>
    <div class="col-md-3"><div class="card border-0 shadow-sm"><div class="card-body"><h6 class="text-muted">Cancelled</h6><h3 class="text-danger">--</h3></div></div></div>
    <div class="col-md-3"><div class="card border-0 shadow-sm"><div class="card-body"><h6 class="text-muted">Cancellation Rate</h6><h3 class="text-warning">--%</h3></div></div></div>
</div>

<div class="row g-4">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white"><h5 class="mb-0">Booking Channels</h5></div>
            <div class="card-body"><canvas id="channelChart" height="300"></canvas></div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white"><h5 class="mb-0">Booking Status Breakdown</h5></div>
            <div class="card-body"><canvas id="statusChart" height="300"></canvas></div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm mt-4">
    <div class="card-header bg-white"><h5 class="mb-0">Booking Trend</h5></div>
    <div class="card-body"><canvas id="trendChart" height="250"></canvas></div>
</div>

@push('styles')
<style>
    @media print { #sidebar-wrapper, .btn, nav { display: none !important; } #page-content-wrapper { margin: 0 !important; width: 100% !important; } .card { border: 1px solid #dee2e6 !important; box-shadow: none !important; } }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    new Chart(document.getElementById('channelChart'), {
        type: 'bar',
        data: { labels: ['Website', 'OTA', 'Phone', 'Walk-in', 'Agent'], datasets: [{ label: 'Bookings', data: [0,0,0,0,0], backgroundColor: ['#0d6efd','#198754','#ffc107','#dc3545','#6c757d'] }] }
    });
    new Chart(document.getElementById('statusChart'), {
        type: 'doughnut',
        data: { labels: ['Confirmed', 'Checked-in', 'Checked-out', 'Cancelled', 'No-show'], datasets: [{ data: [0,0,0,0,0], backgroundColor: ['#198754','#0d6efd','#6c757d','#dc3545','#ffc107'] }] }
    });
    new Chart(document.getElementById('trendChart'), {
        type: 'line',
        data: { labels: [], datasets: [{ label: 'Bookings', data: [], borderColor: '#0d6efd', tension: 0.3, fill: false }] }
    });
</script>
@endpush
@endsection

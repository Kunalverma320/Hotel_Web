@extends('admin.layouts.app')
@section('title', 'Occupancy Report')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0"><i class="bi bi-clipboard-pie"></i> Occupancy Report</h1>
    <div>
        <button class="btn btn-outline-success" onclick="exportCSV()"><i class="bi bi-download"></i> Export CSV</button>
        <button class="btn btn-outline-secondary" onclick="window.print()"><i class="bi bi-printer"></i> Print</button>
    </div>
</div>

<form method="GET" action="{{ route('admin.reports.occupancy') }}" class="mb-4">
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
    <div class="col-md-3"><div class="card border-0 shadow-sm"><div class="card-body"><h6 class="text-muted">Avg Occupancy</h6><h3 class="text-primary">--%</h3></div></div></div>
    <div class="col-md-3"><div class="card border-0 shadow-sm"><div class="card-body"><h6 class="text-muted">Peak Occupancy</h6><h3 class="text-success">--%</h3></div></div></div>
    <div class="col-md-3"><div class="card border-0 shadow-sm"><div class="card-body"><h6 class="text-muted">Lowest Occupancy</h6><h3 class="text-warning">--%</h3></div></div></div>
    <div class="col-md-3"><div class="card border-0 shadow-sm"><div class="card-body"><h6 class="text-muted">Total Room Nights</h6><h3 class="text-info">--</h3></div></div></div>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white"><h5 class="mb-0">Occupancy Trend</h5></div>
    <div class="card-body"><canvas id="occupancyChart" height="300"></canvas></div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white"><h5 class="mb-0">Daily Breakdown</h5></div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover" id="occupancyTable">
                <thead><tr><th>Date</th><th>Available</th><th>Occupied</th><th>Reserved</th><th>Rate %</th></tr></thead>
                <tbody>
                    <tr><td colspan="5" class="text-center text-muted">Data will populate here.</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('styles')
<style>
    @media print { #sidebar-wrapper, .btn, nav { display: none !important; } #page-content-wrapper { margin: 0 !important; width: 100% !important; } .card { border: 1px solid #dee2e6 !important; box-shadow: none !important; } }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    new Chart(document.getElementById('occupancyChart'), {
        type: 'line',
        data: { labels: [], datasets: [{ label: 'Occupancy %', data: [], borderColor: '#0d6efd', backgroundColor: 'rgba(13,110,253,0.1)', fill: true, tension: 0.3 }] },
        options: { responsive: true, scales: { y: { beginAtZero: true, max: 100 } }, plugins: { legend: { display: false } } }
    });
    function exportCSV() { window.location.href = '{{ route("admin.reports.export", ["type" => "occupancy"]) }}?start_date={{ $startDate }}&end_date={{ $endDate }}&format=csv'; }
</script>
@endpush
@endsection

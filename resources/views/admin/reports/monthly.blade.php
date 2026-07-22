@extends('admin.layouts.app')
@section('title', 'Monthly Report')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0"><i class="bi bi-calendar-month"></i> Monthly Report</h1>
    <button class="btn btn-outline-secondary" onclick="window.print()"><i class="bi bi-printer"></i> Print</button>
</div>

<form method="GET" action="{{ route('admin.reports.monthly') }}" class="mb-4">
    <div class="row g-3 align-items-end">
        <div class="col-md-2">
            <label class="form-label">Month</label>
            <select name="month" class="form-select">
                @for($m = 1; $m <= 12; $m++)
                    <option value="{{ $m }}" {{ ($month ?? now()->month) == $m ? 'selected' : '' }}>{{ Carbon\Carbon::create()->month($m)->format('F') }}</option>
                @endfor
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label">Year</label>
            <input type="number" name="year" class="form-control" value="{{ $year ?? now()->year }}" min="2000" max="2100">
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i> Generate</button>
        </div>
    </div>
</form>

<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm"><div class="card-body">
            <h6 class="text-muted">Total Revenue</h6><h3 class="text-success">$--</h3>
            <small class="text-muted">vs previous month: --%</small>
        </div></div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm"><div class="card-body">
            <h6 class="text-muted">Avg Occupancy</h6><h3 class="text-primary">--%</h3>
            <small class="text-muted">vs previous month: --%</small>
        </div></div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm"><div class="card-body">
            <h6 class="text-muted">Total Bookings</h6><h3 class="text-info">--</h3>
            <small class="text-muted">vs previous month: --%</small>
        </div></div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm"><div class="card-body">
            <h6 class="text-muted">Avg Daily Rate</h6><h3 class="text-warning">$--</h3>
            <small class="text-muted">vs previous month: --%</small>
        </div></div>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white"><h5 class="mb-0">Daily Revenue Trend</h5></div>
            <div class="card-body"><canvas id="monthlyRevenueChart" height="300"></canvas></div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white"><h5 class="mb-0">Comparison</h5></div>
            <div class="card-body"><canvas id="comparisonChart" height="300"></canvas></div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm mt-4">
    <div class="card-header bg-white"><h5 class="mb-0">Monthly Summary</h5></div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead><tr><th>Metric</th><th>Current Month</th><th>Previous Month</th><th>Change</th></tr></thead>
                <tbody>
                    <tr><td colspan="4" class="text-center text-muted">Summary data will populate here.</td></tr>
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
    new Chart(document.getElementById('monthlyRevenueChart'), {
        type: 'line',
        data: { labels: @json(collect()->range(1, 31)->map(fn($d) => $d)), datasets: [{ label: 'Revenue', data: @json(array_fill(0, 31, 0)), borderColor: '#0d6efd', backgroundColor: 'rgba(13,110,253,0.1)', fill: true, tension: 0.3 }] },
        options: { responsive: true, plugins: { legend: { display: false } } }
    });
    new Chart(document.getElementById('comparisonChart'), {
        type: 'bar',
        data: { labels: ['Revenue', 'Occupancy', 'Bookings'], datasets: [{ label: 'Current', data: [0, 0, 0], backgroundColor: '#0d6efd' }, { label: 'Previous', data: [0, 0, 0], backgroundColor: '#6c757d' }] }
    });
</script>
@endpush
@endsection

@extends('admin.layouts.app')
@section('title', 'Revenue Report')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0"><i class="bi bi-currency-dollar"></i> Revenue Report</h1>
    <div>
        <button class="btn btn-outline-success" onclick="exportCSV()"><i class="bi bi-download"></i> Export CSV</button>
        <button class="btn btn-outline-secondary" onclick="window.print()"><i class="bi bi-printer"></i> Print</button>
    </div>
</div>

<form method="GET" action="{{ route('admin.reports.revenue') }}" class="mb-4">
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
    <div class="col-md-3"><div class="card border-0 shadow-sm"><div class="card-body"><h6 class="text-muted">Total Revenue</h6><h3 class="text-success">$--</h3></div></div></div>
    <div class="col-md-3"><div class="card border-0 shadow-sm"><div class="card-body"><h6 class="text-muted">Room Revenue</h6><h3 class="text-primary">$--</h3></div></div></div>
    <div class="col-md-3"><div class="card border-0 shadow-sm"><div class="card-body"><h6 class="text-muted">F&B Revenue</h6><h3 class="text-warning">$--</h3></div></div></div>
    <div class="col-md-3"><div class="card border-0 shadow-sm"><div class="card-body"><h6 class="text-muted">Other Revenue</h6><h3 class="text-info">$--</h3></div></div></div>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white"><h5 class="mb-0">Revenue by Category</h5></div>
    <div class="card-body"><canvas id="revenueStackedChart" height="350"></canvas></div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white"><h5 class="mb-0">Revenue Summary</h5></div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead><tr><th>Category</th><th>Amount</th><th>% of Total</th><th>vs Previous Period</th></tr></thead>
                <tbody>
                    <tr><td colspan="4" class="text-center text-muted">Revenue data will populate here.</td></tr>
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
    new Chart(document.getElementById('revenueStackedChart'), {
        type: 'bar',
        data: { labels: [], datasets: [
            { label: 'Room Revenue', data: [], backgroundColor: '#0d6efd' },
            { label: 'Restaurant', data: [], backgroundColor: '#198754' },
            { label: 'Laundry', data: [], backgroundColor: '#ffc107' },
            { label: 'Other', data: [], backgroundColor: '#dc3545' }
        ] },
        options: { responsive: true, scales: { x: { stacked: true }, y: { stacked: true } } }
    });
    function exportCSV() { window.location.href = '{{ route("admin.reports.export", ["type" => "revenue"]) }}?start_date={{ $startDate }}&end_date={{ $endDate }}&format=csv'; }
</script>
@endpush
@endsection

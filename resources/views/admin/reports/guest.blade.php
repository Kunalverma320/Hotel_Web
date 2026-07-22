@extends('admin.layouts.app')
@section('title', 'Guest Report')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0"><i class="bi bi-people"></i> Guest Report</h1>
    <button class="btn btn-outline-secondary" onclick="window.print()"><i class="bi bi-printer"></i> Print</button>
</div>

<form method="GET" action="{{ route('admin.reports.guests') }}" class="mb-4">
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
    <div class="col-md-3"><div class="card border-0 shadow-sm"><div class="card-body"><h6 class="text-muted">Total Guests</h6><h3 class="text-primary">--</h3></div></div></div>
    <div class="col-md-3"><div class="card border-0 shadow-sm"><div class="card-body"><h6 class="text-muted">New Guests</h6><h3 class="text-success">--</h3></div></div></div>
    <div class="col-md-3"><div class="card border-0 shadow-sm"><div class="card-body"><h6 class="text-muted">Returning Guests</h6><h3 class="text-warning">--</h3></div></div></div>
    <div class="col-md-3"><div class="card border-0 shadow-sm"><div class="card-body"><h6 class="text-muted">Avg Stay (Nights)</h6><h3 class="text-info">--</h3></div></div></div>
</div>

<div class="row g-4">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white"><h5 class="mb-0">Guests by Nationality</h5></div>
            <div class="card-body"><canvas id="nationalityChart" height="300"></canvas></div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white"><h5 class="mb-0">New vs Returning Guests</h5></div>
            <div class="card-body"><canvas id="newReturningChart" height="300"></canvas></div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm mt-4">
    <div class="card-header bg-white"><h5 class="mb-0">Top Guests</h5></div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead><tr><th>#</th><th>Guest Name</th><th>Total Stays</th><th>Total Spent</th><th>Last Visit</th></tr></thead>
                <tbody>
                    <tr><td colspan="5" class="text-center text-muted">Top guests data will populate here.</td></tr>
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
    new Chart(document.getElementById('nationalityChart'), {
        type: 'pie',
        data: { labels: ['USA', 'UK', 'Germany', 'France', 'Other'], datasets: [{ data: [0, 0, 0, 0, 0], backgroundColor: ['#0d6efd','#198754','#ffc107','#dc3545','#6c757d'] }] }
    });
    new Chart(document.getElementById('newReturningChart'), {
        type: 'doughnut',
        data: { labels: ['New Guests', 'Returning Guests'], datasets: [{ data: [0, 0], backgroundColor: ['#0d6efd', '#198754'] }] }
    });
</script>
@endpush
@endsection

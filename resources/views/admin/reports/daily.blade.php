@extends('admin.layouts.app')
@section('title', 'Daily Report')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0"><i class="bi bi-calendar-day"></i> Daily Report</h1>
    <button class="btn btn-outline-secondary" onclick="window.print()"><i class="bi bi-printer"></i> Print</button>
</div>

<form method="GET" action="{{ route('admin.reports.daily') }}" class="mb-4">
    <div class="row g-3 align-items-end">
        <div class="col-md-3">
            <label class="form-label">Date</label>
            <input type="date" name="date" class="form-control" value="{{ $date ?? now()->toDateString() }}">
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i> Generate</button>
        </div>
    </div>
</form>

<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div><h6 class="text-muted mb-1">Total Rooms</h6><h3 class="mb-0">--</h3></div>
                    <div class="text-primary"><i class="bi bi-door-open fs-2"></i></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div><h6 class="text-muted mb-1">Check-ins Today</h6><h3 class="mb-0">--</h3></div>
                    <div class="text-success"><i class="bi bi-box-arrow-in-right fs-2"></i></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div><h6 class="text-muted mb-1">Check-outs Today</h6><h3 class="mb-0">--</h3></div>
                    <div class="text-warning"><i class="bi bi-box-arrow-right fs-2"></i></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div><h6 class="text-muted mb-1">Today's Revenue</h6><h3 class="mb-0">$--</h3></div>
                    <div class="text-danger"><i class="bi bi-currency-dollar fs-2"></i></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white"><h5 class="mb-0">Room Status</h5></div>
            <div class="card-body">
                <canvas id="roomStatusChart" height="250"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white"><h5 class="mb-0">Revenue Breakdown</h5></div>
            <div class="card-body">
                <canvas id="revenueChart" height="250"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm mt-4">
    <div class="card-header bg-white"><h5 class="mb-0">Check-in / Check-out List</h5></div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead><tr><th>Guest</th><th>Room</th><th>Type</th><th>Time</th><th>Status</th></tr></thead>
                <tbody>
                    <tr><td colspan="5" class="text-center text-muted">No data available. Connect your models to populate this report.</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm mt-4">
    <div class="card-header bg-white"><h5 class="mb-0">Revenue Details</h5></div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead><tr><th>Category</th><th>Amount</th><th>% of Total</th></tr></thead>
                <tbody>
                    <tr><td colspan="3" class="text-center text-muted">Revenue data will populate here.</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('styles')
<style>
    @media print {
        #sidebar-wrapper, .btn, nav, .no-print { display: none !important; }
        #page-content-wrapper { margin: 0 !important; width: 100% !important; }
        .card { border: 1px solid #dee2e6 !important; box-shadow: none !important; }
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    new Chart(document.getElementById('roomStatusChart'), {
        type: 'doughnut',
        data: {
            labels: ['Occupied', 'Vacant', 'Maintenance', 'Reserved'],
            datasets: [{ data: [0, 0, 0, 0], backgroundColor: ['#0d6efd','#198754','#ffc107','#6c757d'] }]
        }
    });
    new Chart(document.getElementById('revenueChart'), {
        type: 'pie',
        data: {
            labels: ['Room Revenue', 'Restaurant', 'Laundry', 'Other'],
            datasets: [{ data: [0, 0, 0, 0], backgroundColor: ['#0d6efd','#198754','#ffc107','#dc3545'] }]
        }
    });
</script>
@endpush
@endsection

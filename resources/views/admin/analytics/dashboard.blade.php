@extends('admin.layouts.app')
@section('title', 'Analytics Dashboard')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0"><i class="bi bi-graph-up"></i> Analytics Dashboard</h1>
    <span class="text-muted">Real-time insights and performance metrics</span>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div><h6 class="mb-1">Today's Revenue</h6><h3 class="mb-0" id="todayRevenue">$0</h3></div>
                    <i class="bi bi-currency-dollar fs-1 opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div><h6 class="mb-1">Occupancy Rate</h6><h3 class="mb-0" id="occupancyRate">0%</h3></div>
                    <i class="bi bi-clipboard-pie fs-1 opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm bg-warning text-dark">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div><h6 class="mb-1">Active Bookings</h6><h3 class="mb-0" id="activeBookings">0</h3></div>
                    <i class="bi bi-bookmark-check fs-1 opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div><h6 class="mb-1">Guests In-House</h6><h3 class="mb-0" id="guestsInHouse">0</h3></div>
                    <i class="bi bi-people fs-1 opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white"><h5 class="mb-0">Revenue Trend (30 Days)</h5></div>
            <div class="card-body"><canvas id="revenueTrendChart" height="280"></canvas></div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white"><h5 class="mb-0">Occupancy Rate</h5></div>
            <div class="card-body text-center"><canvas id="occupancyGauge" height="280"></canvas></div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white"><h5 class="mb-0">Bookings by Type</h5></div>
            <div class="card-body"><canvas id="bookingsTypeChart" height="280"></canvas></div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white"><h5 class="mb-0">Room Type Performance</h5></div>
            <div class="card-body"><canvas id="roomTypeRadar" height="280"></canvas></div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white"><h5 class="mb-0">Monthly Comparison</h5></div>
            <div class="card-body"><canvas id="monthlyCompare" height="280"></canvas></div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white"><h5 class="mb-0">Top 5 Hotels</h5></div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead><tr><th>#</th><th>Hotel</th><th>Revenue</th><th>Occupancy</th></tr></thead>
                        <tbody><tr><td colspan="4" class="text-center text-muted">--</td></tr></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white"><h5 class="mb-0">Top 5 Rooms</h5></div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead><tr><th>#</th><th>Room</th><th>Revenue</th><th>Nights</th></tr></thead>
                        <tbody><tr><td colspan="4" class="text-center text-muted">--</td></tr></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white"><h5 class="mb-0">Top 10 Guests</h5></div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead><tr><th>#</th><th>Guest</th><th>Stays</th><th>Spent</th></tr></thead>
                        <tbody><tr><td colspan="4" class="text-center text-muted">--</td></tr></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    new Chart(document.getElementById('revenueTrendChart'), {
        type: 'line',
        data: { labels: @json(collect()->range(1, 30)->map(fn($d) => now()->subDays(30 - $d)->format('M d'))), datasets: [{ label: 'Revenue', data: @json(array_fill(0, 30, 0)), borderColor: '#0d6efd', backgroundColor: 'rgba(13,110,253,0.1)', fill: true, tension: 0.4 }] },
        options: { responsive: true, plugins: { legend: { display: false } } }
    });
    new Chart(document.getElementById('occupancyGauge'), {
        type: 'doughnut',
        data: { labels: ['Occupied', 'Vacant'], datasets: [{ data: [0, 100], backgroundColor: ['#198754', '#e9ecef'] }] },
        options: { responsive: true, cutout: '75%', plugins: { legend: { position: 'bottom' } } }
    });
    new Chart(document.getElementById('bookingsTypeChart'), {
        type: 'bar',
        data: { labels: ['Online', 'Offline', 'Corporate', 'Group'], datasets: [{ label: 'Bookings', data: [0,0,0,0], backgroundColor: ['#0d6efd','#198754','#ffc107','#dc3545'] }] }
    });
    new Chart(document.getElementById('roomTypeRadar'), {
        type: 'radar',
        data: { labels: ['Single', 'Double', 'Suite', 'Deluxe', 'Presidential'], datasets: [{ label: 'Revenue', data: [0,0,0,0,0], borderColor: '#0d6efd', backgroundColor: 'rgba(13,110,253,0.2)' }, { label: 'Occupancy', data: [0,0,0,0,0], borderColor: '#198754', backgroundColor: 'rgba(25,135,84,0.2)' }] }
    });
    new Chart(document.getElementById('monthlyCompare'), {
        type: 'bar',
        data: { labels: @json(collect()->range(1, 12)->map(fn($m) => Carbon\Carbon::create()->month($m)->format('M'))), datasets: [{ label: 'This Year', data: @json(array_fill(0, 12, 0)), backgroundColor: '#0d6efd' }, { label: 'Last Year', data: @json(array_fill(0, 12, 0)), backgroundColor: '#6c757d' }] }
    });
</script>
@endpush
@endsection

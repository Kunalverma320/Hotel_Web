@extends('admin.layouts.app')

@section('title', 'Laundry Reports')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-graph-up"></i> Laundry Reports</h4>
    <a href="{{ route('admin.laundry.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Back
    </a>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <h6 class="card-title"><i class="bi bi-tshirt"></i> Total Orders</h6>
                <h2 class="mb-0">{{ $totalOrders }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h6 class="card-title"><i class="bi bi-currency-dollar"></i> Revenue</h6>
                <h2 class="mb-0">${{ number_format($totalRevenue, 2) }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-dark">
            <div class="card-body">
                <h6 class="card-title"><i class="bi bi-hourglass-split"></i> Pending</h6>
                <h2 class="mb-0">{{ $ordersByStatus->where('status', 'received')->first()->total ?? 0 }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <h6 class="card-title"><i class="bi bi-check-circle"></i> Delivered</h6>
                <h2 class="mb-0">{{ $ordersByStatus->where('status', 'delivered')->first()->total ?? 0 }}</h2>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header"><h6 class="mb-0">Orders by Status</h6></div>
            <div class="card-body">
                <canvas id="ordersByStatusChart" height="250"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header"><h6 class="mb-0">Orders by Priority</h6></div>
            <div class="card-body">
                <canvas id="ordersByPriorityChart" height="250"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="card mt-4">
    <div class="card-header"><h6 class="mb-0">Daily Orders (Last 30 Days)</h6></div>
    <div class="card-body">
        <canvas id="dailyOrdersChart" height="200"></canvas>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    new Chart(document.getElementById('ordersByStatusChart'), {
        type: 'doughnut',
        data: {
            labels: @json($ordersByStatus->pluck('status')->map(fn($s) => ucfirst($s))),
            datasets: [{
                data: @json($ordersByStatus->pluck('total')),
                backgroundColor: ['#0dcaf0', '#0d6efd', '#ffc107', '#198754', '#6c757d']
            }]
        },
        options: { responsive: true, maintainAspectRatio: false }
    });

    new Chart(document.getElementById('ordersByPriorityChart'), {
        type: 'pie',
        data: {
            labels: @json($ordersByPriority->pluck('priority')->map(fn($p) => ucfirst($p))),
            datasets: [{
                data: @json($ordersByPriority->pluck('total')),
                backgroundColor: ['#6c757d', '#ffc107', '#dc3545']
            }]
        },
        options: { responsive: true, maintainAspectRatio: false }
    });

    new Chart(document.getElementById('dailyOrdersChart'), {
        type: 'line',
        data: {
            labels: @json($dailyOrders->pluck('date')->map(fn($d) => \Carbon\Carbon::parse($d)->format('M d'))),
            datasets: [{
                label: 'Orders',
                data: @json($dailyOrders->pluck('total')),
                borderColor: '#0d6efd',
                backgroundColor: 'rgba(13,110,253,0.1)',
                fill: true,
                tension: 0.3
            }]
        },
        options: { responsive: true, maintainAspectRatio: false }
    });
</script>
@endsection

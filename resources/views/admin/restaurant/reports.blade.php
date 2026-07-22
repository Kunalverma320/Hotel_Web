@extends('admin.layouts.app')

@section('title', 'Restaurant Reports')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-graph-up"></i> Restaurant Reports</h4>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <h6 class="card-title"><i class="bi bi-receipt"></i> Total Orders</h6>
                <h2 class="mb-0">{{ $totalOrders }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h6 class="card-title"><i class="bi bi-currency-dollar"></i> Total Revenue</h6>
                <h2 class="mb-0">${{ number_format($totalRevenue, 2) }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <h6 class="card-title"><i class="bi bi-cup-hot"></i> Avg. Order Value</h6>
                <h2 class="mb-0">${{ $totalOrders > 0 ? number_format($totalRevenue / $totalOrders, 2) : '0.00' }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-dark">
            <div class="card-body">
                <h6 class="card-title"><i class="bi bi-star"></i> Top Items Sold</h6>
                <h2 class="mb-0">{{ $topItems->sum('total_qty') }}</h2>
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
            <div class="card-header"><h6 class="mb-0">Daily Revenue (Last 30 Days)</h6></div>
            <div class="card-body">
                <canvas id="dailyRevenueChart" height="250"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="card mt-4">
    <div class="card-header"><h6 class="mb-0">Top Selling Items</h6></div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Item Name</th>
                        <th>Quantity Sold</th>
                        <th>Performance</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($topItems as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td><strong>{{ $item->name }}</strong></td>
                            <td>{{ $item->total_qty }} orders</td>
                            <td>
                                <div class="progress" style="height:20px;">
                                    <div class="progress-bar bg-success" style="width:{{ $topItems->first()->total_qty > 0 ? round(($item->total_qty / $topItems->first()->total_qty) * 100) : 0 }}%"></div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center py-4 text-muted">No data available.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
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
                backgroundColor: ['#ffc107', '#0d6efd', '#198754', '#6c757d', '#dc3545']
            }]
        },
        options: { responsive: true, maintainAspectRatio: false }
    });

    new Chart(document.getElementById('dailyRevenueChart'), {
        type: 'bar',
        data: {
            labels: @json($dailyRevenue->pluck('date')->map(fn($d) => \Carbon\Carbon::parse($d)->format('M d'))),
            datasets: [{
                label: 'Revenue',
                data: @json($dailyRevenue->pluck('revenue')),
                backgroundColor: '#198754'
            }]
        },
        options: { responsive: true, maintainAspectRatio: false }
    });
</script>
@endsection

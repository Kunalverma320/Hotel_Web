@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Dashboard</h4>
        <p class="text-muted mb-0">Welcome back, {{ auth()->user()->name ?? 'Admin' }}. Here's your hotel overview.</p>
    </div>
    <div class="d-flex gap-2">
        <select class="form-select form-select-sm" style="width:auto;" id="dashboardPeriod">
            <option value="today">Today</option>
            <option value="week" selected>This Week</option>
            <option value="month">This Month</option>
            <option value="year">This Year</option>
        </select>
        <a href="{{ route('admin.reports.index') }}" class="btn btn-sm btn-primary">
            <i class="bi bi-bar-chart-line me-1"></i> Full Report
        </a>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-xl-2 col-lg-4 col-md-4 col-sm-6">
        <div class="card stat-card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="stat-label text-muted mb-1">Total Revenue</p>
                        <h4 class="stat-value fw-bold mb-0">{{ number_format($stats['total_revenue'] ?? 0, 2) }}</h4>
                        <small class="{{ ($stats['revenue_change'] ?? 0) >= 0 ? 'text-success' : 'text-danger' }}">
                            <i class="bi bi-{{ ($stats['revenue_change'] ?? 0) >= 0 ? 'arrow-up' : 'arrow-down' }}"></i>
                            {{ abs($stats['revenue_change'] ?? 0) }}%
                        </small>
                    </div>
                    <div class="stat-icon bg-success-subtle text-success">
                        <i class="bi bi-currency-dollar"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-2 col-lg-4 col-md-4 col-sm-6">
        <div class="card stat-card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="stat-label text-muted mb-1">Occupancy Rate</p>
                        <h4 class="stat-value fw-bold mb-0">{{ $stats['occupancy_rate'] ?? 0 }}%</h4>
                        <small class="text-muted">of total rooms</small>
                    </div>
                    <div class="stat-icon bg-primary-subtle text-primary">
                        <i class="bi bi-pie-chart"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-2 col-lg-4 col-md-4 col-sm-6">
        <div class="card stat-card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="stat-label text-muted mb-1">Today's Check-in</p>
                        <h4 class="stat-value fw-bold mb-0">{{ $stats['today_checkin'] ?? 0 }}</h4>
                        <small class="text-muted">arrivals today</small>
                    </div>
                    <div class="stat-icon bg-info-subtle text-info">
                        <i class="bi bi-box-arrow-in-right"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-2 col-lg-4 col-md-4 col-sm-6">
        <div class="card stat-card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="stat-label text-muted mb-1">Today's Check-out</p>
                        <h4 class="stat-value fw-bold mb-0">{{ $stats['today_checkout'] ?? 0 }}</h4>
                        <small class="text-muted">departures today</small>
                    </div>
                    <div class="stat-icon bg-warning-subtle text-warning">
                        <i class="bi bi-box-arrow-right"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-2 col-lg-4 col-md-4 col-sm-6">
        <div class="card stat-card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="stat-label text-muted mb-1">Pending Payments</p>
                        <h4 class="stat-value fw-bold mb-0">{{ number_format($stats['pending_payments'] ?? 0, 2) }}</h4>
                        <small class="text-muted">{{ $stats['pending_count'] ?? 0 }} invoices</small>
                    </div>
                    <div class="stat-icon bg-danger-subtle text-danger">
                        <i class="bi bi-hourglass-split"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-2 col-lg-4 col-md-4 col-sm-6">
        <div class="card stat-card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="stat-label text-muted mb-1">Total Rooms</p>
                        <h4 class="stat-value fw-bold mb-0">{{ $stats['total_rooms'] ?? 0 }}</h4>
                        <small class="text-muted">{{ $stats['available_rooms'] ?? 0 }} available</small>
                    </div>
                    <div class="stat-icon bg-secondary-subtle text-secondary">
                        <i class="bi bi-door-open"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-xl-8 col-lg-7">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="card-title fw-bold mb-0">Revenue Overview</h6>
                <div class="btn-group btn-group-sm" role="group">
                    <button type="button" class="btn btn-outline-primary active" onclick="switchRevenueChart('line', this)">Line</button>
                    <button type="button" class="btn btn-outline-primary" onclick="switchRevenueChart('bar', this)">Bar</button>
                </div>
            </div>
            <div class="card-body">
                <canvas id="revenueChart" height="280"></canvas>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-lg-5">
        <div class="card h-100">
            <div class="card-header">
                <h6 class="card-title fw-bold mb-0">Occupancy Distribution</h6>
            </div>
            <div class="card-body d-flex align-items-center justify-content-center">
                <canvas id="occupancyChart" height="260"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-xl-6 col-lg-6">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="card-title fw-bold mb-0">
                    <i class="bi bi-box-arrow-in-right text-success me-1"></i> Today's Check-in
                </h6>
                <a href="{{ route('admin.check-ins.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Guest</th>
                                <th>Room</th>
                                <th>Check-in</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($checkins ?? [] as $booking)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm me-2">{{ strtoupper(substr($booking->guest->name ?? 'G', 0, 1)) }}</div>
                                            <div>
                                                <div class="fw-medium">{{ $booking->guest->name ?? 'N/A' }}</div>
                                                <small class="text-muted">{{ $booking->guest->phone ?? '' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-light text-dark">{{ $booking->rooms->first()?->room_number ?? 'N/A' }}</span></td>
                                    <td><small>{{ \Carbon\Carbon::parse($booking->check_in_date)->format('d M, h:i A') }}</small></td>
                                    <td><span class="badge {{ $booking->status == 'confirmed' ? 'bg-success' : 'bg-warning' }}">{{ ucfirst($booking->status) }}</span></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">
                                        <i class="bi bi-calendar-check d-block mb-2" style="font-size:2rem;opacity:0.3;"></i>
                                        No check-ins scheduled for today
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-6 col-lg-6">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="card-title fw-bold mb-0">
                    <i class="bi bi-box-arrow-right text-warning me-1"></i> Today's Check-out
                </h6>
                <a href="{{ route('admin.check-outs.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Guest</th>
                                <th>Room</th>
                                <th>Check-out</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($checkouts ?? [] as $booking)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm me-2">{{ strtoupper(substr($booking->guest->name ?? 'G', 0, 1)) }}</div>
                                            <div>
                                                <div class="fw-medium">{{ $booking->guest->name ?? 'N/A' }}</div>
                                                <small class="text-muted">{{ $booking->guest->phone ?? '' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-light text-dark">{{ $booking->rooms->first()?->room_number ?? 'N/A' }}</span></td>
                                    <td><small>{{ \Carbon\Carbon::parse($booking->check_out_date)->format('d M, h:i A') }}</small></td>
                                    <td><span class="badge {{ $booking->status == 'checked_out' ? 'bg-secondary' : 'bg-info' }}">{{ ucfirst(str_replace('_', ' ', $booking->status)) }}</span></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">
                                        <i class="bi bi-calendar-x d-block mb-2" style="font-size:2rem;opacity:0.3;"></i>
                                        No check-outs scheduled for today
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-xl-6 col-lg-6">
        <div class="card h-100">
            <div class="card-header">
                <h6 class="card-title fw-bold mb-0">Room Status</h6>
            </div>
            <div class="card-body">
                <canvas id="roomStatusChart" height="220"></canvas>
            </div>
        </div>
    </div>
    <div class="col-xl-6 col-lg-6">
        <div class="card h-100">
            <div class="card-header">
                <h6 class="card-title fw-bold mb-0">Housekeeping Status</h6>
            </div>
            <div class="card-body">
                <canvas id="housekeepingChart" height="220"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-xl-8">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="card-title fw-bold mb-0">Recent Bookings</h6>
                <a href="{{ route('admin.bookings.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0" id="recentBookingsTable">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Guest</th>
                                <th>Room</th>
                                <th>Check-in</th>
                                <th>Check-out</th>
                                <th>Amount</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentBookings ?? [] as $booking)
                                <tr>
                                    <td><small class="text-muted">#{{ $booking->id }}</small></td>
                                    <td>{{ $booking->guest->name ?? 'N/A' }}</td>
                                    <td><span class="badge bg-light text-dark">{{ $booking->rooms->first()?->room_number ?? 'N/A' }}</span></td>
                                    <td><small>{{ \Carbon\Carbon::parse($booking->check_in_date)->format('d M Y') }}</small></td>
                                    <td><small>{{ \Carbon\Carbon::parse($booking->check_out_date)->format('d M Y') }}</small></td>
                                    <td class="fw-medium">{{ number_format($booking->total_amount ?? 0, 2) }}</td>
                                    <td>
                                        @php
                                            $statusClasses = [
                                                'confirmed' => 'bg-success',
                                                'pending' => 'bg-warning text-dark',
                                                'cancelled' => 'bg-danger',
                                                'checked_in' => 'bg-info',
                                                'checked_out' => 'bg-secondary',
                                            ];
                                        @endphp
                                        <span class="badge {{ $statusClasses[$booking->status] ?? 'bg-secondary' }}">{{ ucfirst(str_replace('_', ' ', $booking->status)) }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">
                                        <i class="bi bi-inbox d-block mb-2" style="font-size:2rem;opacity:0.3;"></i>
                                        No recent bookings found
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4">
        <div class="card h-100">
            <div class="card-header">
                <h6 class="card-title fw-bold mb-0">Top Customers</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Guest</th>
                                <th>Bookings</th>
                                <th>Spent</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topCustomers ?? [] as $customer)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm me-2">{{ strtoupper(substr($customer->name, 0, 1)) }}</div>
                                            <div>
                                                <div class="fw-medium">{{ $customer->name }}</div>
                                                <small class="text-muted">{{ $customer->email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-primary-subtle text-primary">{{ $customer->bookings_count }}</span></td>
                                    <td class="fw-medium">{{ number_format($customer->total_spent ?? 0, 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-4">
                                        <i class="bi bi-people d-block mb-2" style="font-size:2rem;opacity:0.3;"></i>
                                        No customer data available
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-xl-6">
        <div class="card h-100">
            <div class="card-header">
                <h6 class="card-title fw-bold mb-0">Top Hotels by Revenue</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Hotel</th>
                                <th>Bookings</th>
                                <th>Revenue</th>
                                <th>Occupancy</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topHotels ?? [] as $hotel)
                                <tr>
                                    <td>
                                        <div class="fw-medium">{{ $hotel->name }}</div>
                                        <small class="text-muted">{{ $hotel->branch->name ?? '' }}</small>
                                    </td>
                                    <td>{{ $hotel->bookings_count ?? 0 }}</td>
                                    <td class="fw-medium">{{ number_format($hotel->revenue ?? 0, 2) }}</td>
                                    <td>
                                        <div class="progress" style="height:6px;width:80px;">
                                            <div class="progress-bar bg-success" style="width:{{ $hotel->occupancy ?? 0 }}%"></div>
                                        </div>
                                        <small class="text-muted">{{ $hotel->occupancy ?? 0 }}%</small>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">
                                        <i class="bi bi-hotel d-block mb-2" style="font-size:2rem;opacity:0.3;"></i>
                                        No hotel data available
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-6">
        <div class="card h-100">
            <div class="card-header">
                <h6 class="card-title fw-bold mb-0">Top Rooms by Booking</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Room</th>
                                <th>Type</th>
                                <th>Bookings</th>
                                <th>Revenue</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topRooms ?? [] as $room)
                                <tr>
                                    <td><span class="badge bg-light text-dark">Room {{ $room->room_number ?? 'N/A' }}</span></td>
                                    <td>{{ $room->type->name ?? 'N/A' }}</td>
                                    <td>{{ $room->bookings_count ?? 0 }}</td>
                                    <td class="fw-medium">{{ number_format($room->revenue ?? 0, 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">
                                        <i class="bi bi-door-open d-block mb-2" style="font-size:2rem;opacity:0.3;"></i>
                                        No room data available
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
@php
    $revenueLabelsJson = json_encode($revenueLabels ?? ['Mon','Tue','Wed','Thu','Fri','Sat','Sun']);
    $revenueDataJson = json_encode($revenueData ?? [0,0,0,0,0,0,0]);
    $roomStatusLabelsJson = json_encode($roomStatusLabels ?? ['Available', 'Occupied', 'Reserved', 'Maintenance', 'Out of Order']);
    $roomStatusDataJson = json_encode($roomStatusData ?? [0,0,0,0,0]);
    $hkLabelsJson = json_encode($hkLabels ?? ['Clean', 'Dirty', 'In Progress', 'Inspected']);
    $hkDataJson = json_encode($hkData ?? [0,0,0,0]);
@endphp
<script>
document.addEventListener('DOMContentLoaded', function() {
    const revenueLabels = <?php echo $revenueLabelsJson; ?>;
    const revenueData = <?php echo $revenueDataJson; ?>;
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');

    const revenueColors = getComputedStyle(document.documentElement);
    const gridColor = document.documentElement.getAttribute('data-theme') === 'dark' ? 'rgba(255,255,255,0.08)' : 'rgba(0,0,0,0.06)';
    const textColor = document.documentElement.getAttribute('data-theme') === 'dark' ? '#94a3b8' : '#6c757d';

    let revenueChart = new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: revenueLabels,
            datasets: [{
                label: 'Revenue',
                data: revenueData,
                borderColor: '#4f46e5',
                backgroundColor: 'rgba(79,70,229,0.08)',
                borderWidth: 2.5,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#4f46e5',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, grid: { color: gridColor }, ticks: { color: textColor } },
                x: { grid: { display: false }, ticks: { color: textColor } }
            }
        }
    });

    window.switchRevenueChart = function(type, btn) {
        document.querySelectorAll('.btn-group .btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        revenueChart.destroy();
        const config = {
            type: type,
            data: {
                labels: revenueLabels,
                datasets: [{
                    label: 'Revenue',
                    data: revenueData,
                    borderColor: '#4f46e5',
                    backgroundColor: type === 'bar' ? 'rgba(79,70,229,0.7)' : 'rgba(79,70,229,0.08)',
                    borderWidth: type === 'line' ? 2.5 : 0,
                    fill: type === 'line',
                    tension: 0.4,
                    borderRadius: type === 'bar' ? 6 : 0,
                    pointBackgroundColor: '#4f46e5',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: type === 'line' ? 4 : 0,
                    pointHoverRadius: type === 'line' ? 6 : 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { color: gridColor }, ticks: { color: textColor } },
                    x: { grid: { display: false }, ticks: { color: textColor } }
                }
            }
        };
        revenueChart = new Chart(revenueCtx, config);
    };

    const occupancyCtx = document.getElementById('occupancyChart').getContext('2d');
    new Chart(occupancyCtx, {
        type: 'doughnut',
        data: {
            labels: ['Occupied', 'Available', 'Maintenance'],
            datasets: [{
                data: [
                    {{ $stats['occupied_rooms'] ?? 0 }},
                    {{ $stats['available_rooms'] ?? 0 }},
                    {{ $stats['maintenance_rooms'] ?? 0 }}
                ],
                backgroundColor: ['#4f46e5', '#22c55e', '#f59e0b'],
                borderWidth: 0,
                hoverOffset: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '68%',
            plugins: {
                legend: { position: 'bottom', labels: { padding: 16, usePointStyle: true, color: textColor } }
            }
        }
    });

    const roomStatusCtx = document.getElementById('roomStatusChart').getContext('2d');
    new Chart(roomStatusCtx, {
        type: 'bar',
        data: {
            labels: <?php echo $roomStatusLabelsJson; ?>,
            datasets: [{
                label: 'Rooms',
                data: <?php echo $roomStatusDataJson; ?>,
                backgroundColor: ['#22c55e', '#4f46e5', '#3b82f6', '#f59e0b', '#ef4444'],
                borderRadius: 6,
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            indexAxis: 'y',
            plugins: { legend: { display: false } },
            scales: {
                x: { beginAtZero: true, grid: { color: gridColor }, ticks: { color: textColor } },
                y: { grid: { display: false }, ticks: { color: textColor } }
            }
        }
    });

    const hkCtx = document.getElementById('housekeepingChart').getContext('2d');
    new Chart(hkCtx, {
        type: 'pie',
        data: {
            labels: <?php echo $hkLabelsJson; ?>,
            datasets: [{
                data: <?php echo $hkDataJson; ?>,
                backgroundColor: ['#22c55e', '#ef4444', '#f59e0b', '#3b82f6'],
                borderWidth: 0,
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom', labels: { padding: 16, usePointStyle: true, color: textColor } }
            }
        }
    });
});
</script>
@endpush

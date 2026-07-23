@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
<style>
    .dashboard-header {
        margin-bottom: 2rem;
        transition: var(--transition);
    }
    
    .stat-card {
        border: 1px solid var(--border);
        border-radius: 20px;
        background: var(--card-bg);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }
    
    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.05);
        border-color: rgba(99, 102, 241, 0.3);
    }
    
    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        font-weight: bold;
    }
    
    .dashboard-card {
        border: 1px solid var(--border);
        border-radius: 20px;
        background: var(--card-bg);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.02);
        overflow: hidden;
        margin-bottom: 1.5rem;
    }
    
    .dashboard-card .card-header {
        background: transparent;
        border-bottom: 1px solid var(--border);
        padding: 1.25rem 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .dashboard-card .card-body {
        padding: 1.5rem;
    }

    .table-custom th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        color: var(--text-muted);
        border-bottom: 1px solid var(--border);
        padding: 1rem 1.5rem;
    }

    .table-custom td {
        padding: 1.25rem 1.5rem;
        vertical-align: middle;
        border-bottom: 1px solid var(--border);
    }

    .avatar-sm {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        background: var(--primary-light);
        color: var(--primary);
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.875rem;
    }

    @media (max-width: 576px) {
        .dashboard-header {
            flex-direction: column;
            align-items: flex-start !important;
            gap: 15px;
        }
        .dashboard-header .d-flex {
            width: 100%;
            justify-content: space-between;
        }
        .dashboard-card .card-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
        }
        .dashboard-card .card-header .btn, .dashboard-card .card-header .btn-group {
            width: 100%;
        }
    }
</style>

<div class="d-flex justify-content-between align-items-center dashboard-header">
    <div>
        <h4 class="fw-bold mb-1">Overview Dashboard</h4>
        <p class="text-muted mb-0">Welcome back, <strong class="text-dark">{{ auth()->user()->name ?? 'Admin' }}</strong>. Here is your hotel metrics summary.</p>
    </div>
    <div class="d-flex gap-2">
        <select class="form-select form-select-sm rounded-3" style="width:auto;" id="dashboardPeriod">
            <option value="today">Today</option>
            <option value="week" selected>This Week</option>
            <option value="month">This Month</option>
            <option value="year">This Year</option>
        </select>
        @can('view reports')
        <a href="{{ route('admin.reports.index') }}" class="btn btn-sm btn-primary rounded-3 px-3">
            <i class="bi bi-bar-chart-line me-1"></i> Full Reports
        </a>
        @endcan
    </div>
</div>

<!-- Stat Cards Section -->
<div class="row row-cols-1 row-cols-sm-2 row-cols-md-2 row-cols-xl-4 g-3 mb-4">
    @can('view finance')
    <div class="col">
        <div class="card stat-card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="stat-label text-muted mb-1 small fw-medium">Total Revenue</p>
                        <h3 class="stat-value fw-bold mb-1 text-dark" style="font-size: calc(1.3rem + 0.4vw); word-break: break-all;">${{ number_format($stats['total_revenue'] ?? 0, 2) }}</h3>
                        <small class="{{ ($stats['revenue_change'] ?? 0) >= 0 ? 'text-success' : 'text-danger' }} fw-semibold">
                            <i class="bi bi-{{ ($stats['revenue_change'] ?? 0) >= 0 ? 'arrow-up' : 'arrow-down' }} me-1"></i>
                            {{ abs($stats['revenue_change'] ?? 0) }}% this month
                        </small>
                    </div>
                    <div class="stat-icon bg-success-subtle text-success">
                        <i class="bi bi-currency-dollar"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endcan

    @can('view rooms')
    <div class="col">
        <div class="card stat-card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="stat-label text-muted mb-1 small fw-medium">Occupancy Rate</p>
                        <h3 class="stat-value fw-bold mb-1 text-dark" style="font-size: calc(1.3rem + 0.4vw);">{{ $stats['occupancy_rate'] ?? 0 }}%</h3>
                        <small class="text-muted fw-medium">{{ $stats['occupied_rooms'] ?? 0 }} / {{ $stats['total_rooms'] ?? 0 }} rooms active</small>
                    </div>
                    <div class="stat-icon bg-primary-subtle text-primary">
                        <i class="bi bi-pie-chart"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endcan

    @can('view bookings')
    <div class="col">
        <div class="card stat-card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="stat-label text-muted mb-1 small fw-medium">Today's Check-ins</p>
                        <h3 class="stat-value fw-bold mb-1 text-dark" style="font-size: calc(1.3rem + 0.4vw);">{{ $stats['today_checkin'] ?? 0 }}</h3>
                        <small class="text-muted fw-medium">Arrival guests today</small>
                    </div>
                    <div class="stat-icon bg-info-subtle text-info">
                        <i class="bi bi-box-arrow-in-right"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="card stat-card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="stat-label text-muted mb-1 small fw-medium">Today's Check-outs</p>
                        <h3 class="stat-value fw-bold mb-1 text-dark" style="font-size: calc(1.3rem + 0.4vw);">{{ $stats['today_checkout'] ?? 0 }}</h3>
                        <small class="text-muted fw-medium">Departure guests today</small>
                    </div>
                    <div class="stat-icon bg-warning-subtle text-warning">
                        <i class="bi bi-box-arrow-right"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endcan
</div>

<!-- Charts Section -->
@php
    $showRevenue = auth()->user()->can('view finance');
    $showOccupancy = auth()->user()->can('view rooms');
@endphp
@if($showRevenue || $showOccupancy)
<div class="row g-3 mb-2">
    @if($showRevenue)
    <div class="{{ $showOccupancy ? 'col-xl-8 col-lg-7' : 'col-12' }}">
        <div class="card dashboard-card h-100">
            <div class="card-header">
                <h6 class="card-title fw-bold mb-0">Revenue Overview</h6>
                <div class="btn-group btn-group-sm" role="group">
                    <button type="button" class="btn btn-outline-primary active" onclick="switchRevenueChart('line', this)">Line</button>
                    <button type="button" class="btn btn-outline-primary" onclick="switchRevenueChart('bar', this)">Bar</button>
                </div>
            </div>
            <div class="card-body">
                <div class="chart-container" style="position: relative; height: 320px; width: 100%;">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if($showOccupancy)
    <div class="{{ $showRevenue ? 'col-xl-4 col-lg-5' : 'col-12' }}">
        <div class="card dashboard-card h-100">
            <div class="card-header">
                <h6 class="card-title fw-bold mb-0">Occupancy Distribution</h6>
            </div>
            <div class="card-body d-flex align-items-center justify-content-center">
                <div class="chart-container" style="position: relative; height: 280px; width: 100%;">
                    <canvas id="occupancyChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endif

<!-- Check-ins / Check-outs Tables Section -->
@php
    $showCheckins = auth()->user()->can('view check-ins');
    $showCheckouts = auth()->user()->can('view check-outs');
@endphp
@if($showCheckins || $showCheckouts)
<div class="row g-3 mb-2">
    @if($showCheckins)
    <div class="{{ $showCheckouts ? 'col-xl-6 col-lg-6' : 'col-12' }}">
        <div class="card dashboard-card h-100">
            <div class="card-header">
                <h6 class="card-title fw-bold mb-0">
                    <i class="bi bi-box-arrow-in-right text-success me-1"></i> Today's Check-ins
                </h6>
                <a href="{{ route('admin.check-ins.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-custom mb-0">
                        <thead>
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
                                                <div class="fw-medium text-dark">{{ $booking->guest->name ?? 'N/A' }}</div>
                                                <small class="text-muted">{{ $booking->guest->phone ?? '' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-light text-dark fw-bold border">{{ $booking->rooms->first()?->room_number ?? 'N/A' }}</span></td>
                                    <td><small class="fw-medium">{{ \Carbon\Carbon::parse($booking->check_in_date)->format('d M, h:i A') }}</small></td>
                                    <td><span class="badge {{ $booking->status == 'confirmed' ? 'bg-success' : 'bg-warning text-dark' }}">{{ ucfirst($booking->status) }}</span></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-5">
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
    @endif

    @if($showCheckouts)
    <div class="{{ $showCheckins ? 'col-xl-6 col-lg-6' : 'col-12' }}">
        <div class="card dashboard-card h-100">
            <div class="card-header">
                <h6 class="card-title fw-bold mb-0">
                    <i class="bi bi-box-arrow-right text-warning me-1"></i> Today's Check-outs
                </h6>
                <a href="{{ route('admin.check-outs.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-custom mb-0">
                        <thead>
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
                                                <div class="fw-medium text-dark">{{ $booking->guest->name ?? 'N/A' }}</div>
                                                <small class="text-muted">{{ $booking->guest->phone ?? '' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-light text-dark fw-bold border">{{ $booking->rooms->first()?->room_number ?? 'N/A' }}</span></td>
                                    <td><small class="fw-medium">{{ \Carbon\Carbon::parse($booking->check_out_date)->format('d M, h:i A') }}</small></td>
                                    <td><span class="badge {{ $booking->status == 'checked_out' ? 'bg-secondary' : 'bg-info' }}">{{ ucfirst(str_replace('_', ' ', $booking->status)) }}</span></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-5">
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
    @endif
</div>
@endif

<!-- Room & Housekeeping Status Section -->
@php
    $showRoomStatus = auth()->user()->can('view rooms');
    $showHousekeeping = auth()->user()->can('view housekeeping');
@endphp
@if($showRoomStatus || $showHousekeeping)
<div class="row g-3 mb-2">
    @if($showRoomStatus)
    <div class="{{ $showHousekeeping ? 'col-xl-6 col-lg-6' : 'col-12' }}">
        <div class="card dashboard-card h-100">
            <div class="card-header">
                <h6 class="card-title fw-bold mb-0">Room Status Distribution</h6>
            </div>
            <div class="card-body">
                <div class="chart-container" style="position: relative; height: 240px; width: 100%;">
                    <canvas id="roomStatusChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if($showHousekeeping)
    <div class="{{ $showRoomStatus ? 'col-xl-6 col-lg-6' : 'col-12' }}">
        <div class="card dashboard-card h-100">
            <div class="card-header">
                <h6 class="card-title fw-bold mb-0">Housekeeping Metrics</h6>
            </div>
            <div class="card-body">
                <div class="chart-container" style="position: relative; height: 240px; width: 100%;">
                    <canvas id="housekeepingChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endif

<!-- Recent Bookings & Top Customers Section -->
@php
    $showBookings = auth()->user()->can('view bookings');
    $showCustomers = auth()->user()->can('view guests');
@endphp
@if($showBookings || $showCustomers)
<div class="row g-3 mb-2">
    @if($showBookings)
    <div class="{{ $showCustomers ? 'col-xl-8' : 'col-12' }}">
        <div class="card dashboard-card h-100">
            <div class="card-header">
                <h6 class="card-title fw-bold mb-0">Recent Reservations</h6>
                <a href="{{ route('admin.bookings.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-custom mb-0">
                        <thead>
                            <tr>
                                <th>ID</th>
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
                                    <td><small class="text-muted font-monospace">#{{ $booking->id }}</small></td>
                                    <td class="fw-medium text-dark">{{ $booking->guest->name ?? 'N/A' }}</td>
                                    <td><span class="badge bg-light text-dark border">{{ $booking->rooms->first()?->room_number ?? 'N/A' }}</span></td>
                                    <td><small class="fw-medium">{{ \Carbon\Carbon::parse($booking->check_in_date)->format('d M Y') }}</small></td>
                                    <td><small class="fw-medium">{{ \Carbon\Carbon::parse($booking->check_out_date)->format('d M Y') }}</small></td>
                                    <td class="fw-bold text-dark">${{ number_format($booking->total_amount ?? 0, 2) }}</td>
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
                                    <td colspan="7" class="text-center text-muted py-5">
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
    @endif

    @if($showCustomers)
    <div class="{{ $showBookings ? 'col-xl-4' : 'col-12' }}">
        <div class="card dashboard-card h-100">
            <div class="card-header">
                <h6 class="card-title fw-bold mb-0">Top Customers</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-custom mb-0">
                        <thead>
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
                                                <div class="fw-medium text-dark">{{ $customer->name }}</div>
                                                <small class="text-muted" style="font-size:0.75rem;">{{ Str::limit($customer->email, 22) }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-primary-light text-primary fw-bold">{{ $customer->bookings_count }}</span></td>
                                    <td class="fw-bold text-dark">${{ number_format($customer->total_spent ?? 0, 0) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-5">
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
    @endif
</div>
@endif

<!-- Top Properties Section -->
@php
    $showTopHotels = auth()->user()->can('view hotels');
    $showTopRooms = auth()->user()->can('view rooms');
@endphp
@if($showTopHotels || $showTopRooms)
<div class="row g-3 mb-4">
    @if($showTopHotels)
    <div class="{{ $showTopRooms ? 'col-xl-6' : 'col-12' }}">
        <div class="card dashboard-card h-100">
            <div class="card-header">
                <h6 class="card-title fw-bold mb-0">Top Performing Hotels</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-custom mb-0">
                        <thead>
                            <tr>
                                <th>Hotel</th>
                                <th>Bookings</th>
                                <th>Revenue</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topHotels ?? [] as $hotel)
                                <tr>
                                    <td>
                                        <div class="fw-medium text-dark">{{ $hotel->name }}</div>
                                        <small class="text-muted">{{ $hotel->branch->name ?? '' }}</small>
                                    </td>
                                    <td><span class="badge bg-light text-dark border">{{ $hotel->bookings_count ?? 0 }}</span></td>
                                    <td class="fw-bold text-dark">${{ number_format($hotel->revenue ?? 0, 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-5">
                                        <i class="bi bi-hotel d-block mb-2" style="font-size:2rem;opacity:0.3;"></i>
                                        No hotel performance data available
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if($showTopRooms)
    <div class="{{ $showTopHotels ? 'col-xl-6' : 'col-12' }}">
        <div class="card dashboard-card h-100">
            <div class="card-header">
                <h6 class="card-title fw-bold mb-0">Top Rooms by Bookings</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-custom mb-0">
                        <thead>
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
                                    <td><span class="badge bg-light text-dark fw-bold border">Room {{ $room->room_number ?? 'N/A' }}</span></td>
                                    <td class="fw-medium">{{ $room->type->name ?? 'N/A' }}</td>
                                    <td><span class="badge bg-primary-light text-primary fw-bold">{{ $room->bookings_count ?? 0 }}</span></td>
                                    <td class="fw-bold text-dark">${{ number_format($room->revenue ?? 0, 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-5">
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
    @endif
</div>
@endif

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
    const gridColor = document.documentElement.getAttribute('data-theme') === 'dark' ? 'rgba(255,255,255,0.08)' : 'rgba(0,0,0,0.06)';
    const textColor = document.documentElement.getAttribute('data-theme') === 'dark' ? '#94a3b8' : '#6c757d';

    // 1. Revenue Chart
    const revenueCanvas = document.getElementById('revenueChart');
    if (revenueCanvas) {
        const revenueLabels = <?php echo $revenueLabelsJson; ?>;
        const revenueData = <?php echo $revenueDataJson; ?>;
        const revenueCtx = revenueCanvas.getContext('2d');

        let revenueChart = new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: revenueLabels,
                datasets: [{
                    label: 'Revenue',
                    data: revenueData,
                    borderColor: '#6366f1',
                    backgroundColor: 'rgba(99,102,241,0.06)',
                    borderWidth: 2.5,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#6366f1',
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
            const btnGroup = btn.parentElement;
            btnGroup.querySelectorAll('.btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            revenueChart.destroy();
            
            const config = {
                type: type,
                data: {
                    labels: revenueLabels,
                    datasets: [{
                        label: 'Revenue',
                        data: revenueData,
                        borderColor: '#6366f1',
                        backgroundColor: type === 'bar' ? 'rgba(99,102,241,0.75)' : 'rgba(99,102,241,0.06)',
                        borderWidth: type === 'line' ? 2.5 : 0,
                        fill: type === 'line',
                        tension: 0.4,
                        borderRadius: type === 'bar' ? 8 : 0,
                        pointBackgroundColor: '#6366f1',
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
    }

    // 2. Occupancy Doughnut Chart
    const occupancyCanvas = document.getElementById('occupancyChart');
    if (occupancyCanvas) {
        const occupancyCtx = occupancyCanvas.getContext('2d');
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
                    backgroundColor: ['#6366f1', '#10b981', '#f59e0b'],
                    borderWidth: 0,
                    hoverOffset: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '72%',
                plugins: {
                    legend: { position: 'bottom', labels: { padding: 16, usePointStyle: true, color: textColor } }
                }
            }
        });
    }

    // 3. Room Status Horizontal Bar Chart
    const roomStatusCanvas = document.getElementById('roomStatusChart');
    if (roomStatusCanvas) {
        const roomStatusCtx = roomStatusCanvas.getContext('2d');
        new Chart(roomStatusCtx, {
            type: 'bar',
            data: {
                labels: <?php echo $roomStatusLabelsJson; ?>,
                datasets: [{
                    label: 'Rooms',
                    data: <?php echo $roomStatusDataJson; ?>,
                    backgroundColor: ['#10b981', '#6366f1', '#3b82f6', '#f59e0b', '#ef4444'],
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
    }

    // 4. Housekeeping Pie Chart
    const hkCanvas = document.getElementById('housekeepingChart');
    if (hkCanvas) {
        const hkCtx = hkCanvas.getContext('2d');
        new Chart(hkCtx, {
            type: 'pie',
            data: {
                labels: <?php echo $hkLabelsJson; ?>,
                datasets: [{
                    data: <?php echo $hkDataJson; ?>,
                    backgroundColor: ['#10b981', '#ef4444', '#f59e0b', '#3b82f6'],
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
    }
});
</script>
@endpush

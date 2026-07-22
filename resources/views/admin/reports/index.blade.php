@extends('admin.layouts.app')
@section('title', 'Reports Hub')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0"><i class="bi bi-file-earmark-text"></i> Reports Hub</h1>
    <span class="text-muted">Generate and view operational reports</span>
</div>

<div class="row g-4">
    @php
        $reports = [
            ['icon' => 'bi-calendar-day', 'color' => 'primary', 'title' => 'Daily Report', 'desc' => 'Daily operations summary', 'route' => 'admin.reports.daily'],
            ['icon' => 'bi-calendar-month', 'color' => 'success', 'title' => 'Monthly Report', 'desc' => 'Monthly performance summary', 'route' => 'admin.reports.monthly'],
            ['icon' => 'bi-calendar-year', 'color' => 'info', 'title' => 'Yearly Report', 'desc' => 'Annual performance summary', 'route' => 'admin.reports.monthly'],
            ['icon' => 'bi-clipboard-pie', 'color' => 'warning', 'title' => 'Occupancy Report', 'desc' => 'Room occupancy rates over time', 'route' => 'admin.reports.occupancy'],
            ['icon' => 'bi-currency-dollar', 'color' => 'danger', 'title' => 'Revenue Report', 'desc' => 'Revenue breakdown by category', 'route' => 'admin.reports.revenue'],
            ['icon' => 'bi-people', 'color' => 'secondary', 'title' => 'Guest Report', 'desc' => 'Guest statistics and demographics', 'route' => 'admin.reports.guest'],
            ['icon' => 'bi-bookmark-check', 'color' => 'primary', 'title' => 'Booking Report', 'desc' => 'Booking statistics and channels', 'route' => 'admin.reports.booking'],
            ['icon' => 'bi-person-badge', 'color' => 'success', 'title' => 'Employee Report', 'desc' => 'Employee statistics', 'route' => 'admin.reports.employee'],
            ['icon' => 'bi-stars', 'color' => 'info', 'title' => 'Housekeeping Report', 'desc' => 'Housekeeping performance stats', 'route' => 'admin.reports.housekeeping'],
            ['icon' => 'bi-cup-hot', 'color' => 'warning', 'title' => 'Restaurant Report', 'desc' => 'Restaurant operations stats', 'route' => 'admin.reports.restaurant'],
            ['icon' => 'bi-tshirt', 'color' => 'danger', 'title' => 'Laundry Report', 'desc' => 'Laundry service statistics', 'route' => 'admin.reports.laundry'],
            ['icon' => 'bi-box-seam', 'color' => 'secondary', 'title' => 'Inventory Report', 'desc' => 'Inventory levels and usage', 'route' => 'admin.reports.inventory'],
            ['icon' => 'bi-journal-check', 'color' => 'dark', 'title' => 'Audit Report', 'desc' => 'System audit trail', 'route' => 'admin.reports.audit'],
        ];
    @endphp

    @foreach($reports as $report)
        <div class="col-md-4 col-lg-3">
            <a href="{{ route($report['route']) }}" class="text-decoration-none">
                <div class="card h-100 border-0 shadow-sm report-card">
                    <div class="card-body text-center p-4">
                        <div class="mb-3">
                            <i class="bi {{ $report['icon'] }} fs-1 text-{{ $report['color'] }}"></i>
                        </div>
                        <h5 class="card-title text-dark">{{ $report['title'] }}</h5>
                        <p class="card-text text-muted small">{{ $report['desc'] }}</p>
                    </div>
                    <div class="card-footer bg-transparent border-0 text-center pb-3">
                        <span class="btn btn-sm btn-outline-{{ $report['color'] }}">View Report <i class="bi bi-arrow-right"></i></span>
                    </div>
                </div>
            </a>
        </div>
    @endforeach
</div>

<style>
    .report-card { transition: transform 0.2s, box-shadow 0.2s; }
    .report-card:hover { transform: translateY(-5px); box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15) !important; }
</style>
@endsection

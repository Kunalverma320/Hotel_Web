@extends('admin.layouts.app')

@section('title', 'Housekeeping Reports')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-graph-up"></i> Housekeeping Reports</h4>
    <a href="{{ route('admin.housekeeping.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Back
    </a>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <h6 class="card-title"><i class="bi bi-clipboard-check"></i> Total Tasks</h6>
                <h2 class="mb-0">{{ $totalTasks }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h6 class="card-title"><i class="bi bi-check-circle"></i> Completed</h6>
                <h2 class="mb-0">{{ $completedTasks }}</h2>
                <small>{{ $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0 }}%</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-dark">
            <div class="card-body">
                <h6 class="card-title"><i class="bi bi-hourglass-split"></i> In Progress</h6>
                <h2 class="mb-0">{{ $inProgressTasks }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <h6 class="card-title"><i class="bi bi-clock"></i> Pending</h6>
                <h2 class="mb-0">{{ $pendingTasks }}</h2>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header">
                <h6 class="mb-0">Tasks by Type</h6>
            </div>
            <div class="card-body">
                <canvas id="tasksByTypeChart" height="250"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header">
                <h6 class="mb-0">Daily Task Volume (Last 30 Days)</h6>
            </div>
            <div class="card-body">
                <canvas id="dailyStatsChart" height="250"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="card mt-4">
    <div class="card-header">
        <h6 class="mb-0">Completion Rate</h6>
    </div>
    <div class="card-body">
        <div class="progress" style="height: 30px;">
            <div class="progress-bar bg-success" style="width: {{ $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0 }}%">
                {{ $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0 }}% Completed
            </div>
            <div class="progress-bar bg-warning" style="width: {{ $totalTasks > 0 ? round(($inProgressTasks / $totalTasks) * 100) : 0 }}%">
                {{ $totalTasks > 0 ? round(($inProgressTasks / $totalTasks) * 100) : 0 }}%
            </div>
            <div class="progress-bar bg-info" style="width: {{ $totalTasks > 0 ? round(($pendingTasks / $totalTasks) * 100) : 0 }}%">
                {{ $totalTasks > 0 ? round(($pendingTasks / $totalTasks) * 100) : 0 }}%
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    new Chart(document.getElementById('tasksByTypeChart'), {
        type: 'doughnut',
        data: {
            labels: @json($tasksByType->pluck('type')),
            datasets: [{
                data: @json($tasksByType->pluck('total')),
                backgroundColor: ['#0d6efd', '#198754', '#ffc107', '#dc3545', '#0dcaf0']
            }]
        },
        options: { responsive: true, maintainAspectRatio: false }
    });

    new Chart(document.getElementById('dailyStatsChart'), {
        type: 'line',
        data: {
            labels: @json($dailyStats->pluck('date')->map(fn($d) => \Carbon\Carbon::parse($d)->format('M d'))),
            datasets: [{
                label: 'Tasks',
                data: @json($dailyStats->pluck('total')),
                borderColor: '#0d6efd',
                tension: 0.3,
                fill: false
            }]
        },
        options: { responsive: true, maintainAspectRatio: false }
    });
</script>
@endsection

@extends('admin.layouts.app')
@section('title', 'Notifications')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0"><i class="bi bi-bell"></i> Notifications</h1>
    <div>
        <a href="{{ route('admin.notifications.mark-all-read') }}" class="btn btn-outline-primary btn-sm">
            <i class="bi bi-check-all"></i> Mark All as Read
        </a>
        <a href="{{ route('admin.notifications.settings') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-gear"></i> Notification Settings
        </a>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="list-group list-group-flush">
            @php
                $notifications = [
                    ['id' => 1, 'title' => 'New booking received', 'message' => 'Booking #1234 from John Doe for Suite 501', 'time' => '5 minutes ago', 'read' => false, 'icon' => 'bi-bookmark-plus', 'color' => 'primary'],
                    ['id' => 2, 'title' => 'Check-in completed', 'message' => 'Guest Jane Smith checked into Room 203', 'time' => '1 hour ago', 'read' => false, 'icon' => 'bi-box-arrow-in-right', 'color' => 'success'],
                    ['id' => 3, 'title' => 'Payment received', 'message' => '$1,250.00 payment received for booking #1230', 'time' => '3 hours ago', 'read' => true, 'icon' => 'bi-credit-card', 'color' => 'info'],
                    ['id' => 4, 'title' => 'Cancellation request', 'message' => 'Booking #1225 cancellation requested by guest', 'time' => '5 hours ago', 'read' => true, 'icon' => 'bi-x-circle', 'color' => 'danger'],
                    ['id' => 5, 'title' => 'Low inventory alert', 'message' => 'Towel stock below minimum threshold', 'time' => '1 day ago', 'read' => true, 'icon' => 'bi-exclamation-triangle', 'color' => 'warning'],
                ];
            @endphp

            @forelse($notifications as $notification)
                <a href="{{ route('admin.notifications.mark-read', $notification['id']) }}" class="list-group-item list-group-item-action {{ $notification['read'] ? '' : 'list-group-item-light border-start border-primary border-3' }}">
                    <div class="d-flex align-items-start">
                        <div class="me-3">
                            <i class="bi {{ $notification['icon'] }} fs-4 text-{{ $notification['color'] }}"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-1 {{ $notification['read'] ? 'text-muted' : 'fw-bold' }}">{{ $notification['title'] }}</h6>
                            <p class="mb-1 small text-muted">{{ $notification['message'] }}</p>
                            <small class="text-muted">{{ $notification['time'] }}</small>
                        </div>
                        @if(!$notification['read'])
                            <span class="badge bg-primary rounded-pill">New</span>
                        @endif
                    </div>
                </a>
            @empty
                <div class="text-center py-5">
                    <i class="bi bi-bell-slash fs-1 text-muted"></i>
                    <p class="mt-3 text-muted">No notifications</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection

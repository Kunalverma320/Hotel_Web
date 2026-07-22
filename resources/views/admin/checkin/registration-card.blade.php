<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Card - {{ $checkIn->booking->booking_number }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>@media print{body{font-size:11px}.no-print{display:none!important}}.reg-card{border:2px solid #333;max-width:700px;margin:0 auto;padding:20px}</style>
</head>
<body>
<div class="container py-3">
    <div class="no-print mb-3 text-end">
        <button onclick="window.print()" class="btn btn-primary btn-sm"><i class="bi bi-printer me-1"></i> Print</button>
        <button onclick="window.close()" class="btn btn-outline-secondary btn-sm">Close</button>
    </div>
    <div class="reg-card">
        <div class="text-center mb-3">
            <h4 class="fw-bold mb-0">{{ $checkIn->booking->hotel->name ?? config('app.name') }}</h4>
            <div class="small text-muted">{{ $checkIn->booking->hotel->address ?? '' }}</div>
            <div class="small text-muted">{{ $checkIn->booking->hotel->phone ?? '' }}</div>
            <hr>
            <h5 class="fw-bold">REGISTRATION CARD</h5>
        </div>
        <div class="row g-2 mb-3">
            <div class="col-6"><strong>Booking #:</strong> {{ $checkIn->booking->booking_number }}</div>
            <div class="col-6"><strong>Room #:</strong> {{ $checkIn->room->number ?? 'N/A' }}</div>
            <div class="col-6"><strong>Guest Name:</strong> {{ $checkIn->guest->full_name ?? 'N/A' }}</div>
            <div class="col-6"><strong>Room Type:</strong> {{ $checkIn->booking->roomType->name ?? 'N/A' }}</div>
            <div class="col-6"><strong>Email:</strong> {{ $checkIn->guest->email ?? 'N/A' }}</div>
            <div class="col-6"><strong>Phone:</strong> {{ $checkIn->guest->phone ?? 'N/A' }}</div>
            <div class="col-6"><strong>Nationality:</strong> {{ $checkIn->guest->nationality ?? 'N/A' }}</div>
            <div class="col-6"><strong>ID Type:</strong> {{ $checkIn->guest->id_type ?? 'N/A' }} {{ $checkIn->guest->id_number ?? '' }}</div>
            <div class="col-6"><strong>Check-in:</strong> {{ $checkIn->check_in_time?->format('M d, Y h:i A') ?? 'N/A' }}</div>
            <div class="col-6"><strong>Check-out:</strong> {{ $checkIn->booking->check_out_date->format('M d, Y') }}</div>
            <div class="col-6"><strong>Key Cards:</strong> {{ $checkIn->key_cards_issued }}</div>
            <div class="col-6"><strong>Nights:</strong> {{ $checkIn->booking->nights }}</div>
            <div class="col-6"><strong>Adults:</strong> {{ $checkIn->booking->adults }}</div>
            <div class="col-6"><strong>Children:</strong> {{ $checkIn->booking->children ?? 0 }}</div>
        </div>
        @if($checkIn->booking->special_requests)
        <div class="mb-3"><strong>Special Requests:</strong> {{ $checkIn->booking->special_requests }}</div>
        @endif
        <hr>
        <div class="row">
            <div class="col-6"><div class="mt-4 border-top pt-2 text-center small">Guest Signature</div></div>
            <div class="col-6"><div class="mt-4 border-top pt-2 text-center small">Front Desk Agent</div></div>
        </div>
    </div>
</div>
</body>
</html>

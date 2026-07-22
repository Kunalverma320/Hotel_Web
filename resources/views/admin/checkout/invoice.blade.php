<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - {{ $booking->booking_number }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>@media print{.no-print{display:none!important}body{font-size:12px}}body{font-family:'Inter',sans-serif;color:#333}.inv-hdr{border-bottom:3px solid #0d6efd;padding-bottom:1rem}</style>
</head>
<body>
<div class="container py-4" style="max-width:800px;">
    <div class="no-print mb-3 text-end">
        <button onclick="window.print()" class="btn btn-primary btn-sm"><i class="bi bi-printer me-1"></i> Print</button>
    </div>
    <div class="inv-hdr d-flex justify-content-between align-items-start mb-4">
        <div>
            <h4 class="fw-bold text-primary mb-0">{{ $booking->hotel->name ?? config('app.name') }}</h4>
            <div class="small text-muted">{{ $booking->hotel->address ?? '' }}</div>
            <div class="small text-muted">{{ $booking->hotel->phone ?? '' }} | {{ $booking->hotel->email ?? '' }}</div>
        </div>
        <div class="text-end">
            <h3 class="fw-bold text-primary mb-0">INVOICE</h3>
            <div class="fw-semibold">{{ $booking->booking_number }}</div>
            <div class="small text-muted">{{ now()->format('M d, Y') }}</div>
        </div>
    </div>
    <div class="row mb-4">
        <div class="col-md-6">
            <h6 class="text-muted fw-bold mb-2">Guest</h6>
            <div class="fw-semibold">{{ $booking->guest->full_name ?? 'N/A' }}</div>
            <div class="small">{{ $booking->guest->email ?? '' }}</div>
            <div class="small">{{ $booking->guest->phone ?? '' }}</div>
        </div>
        <div class="col-md-6 text-end">
            <h6 class="text-muted fw-bold mb-2">Stay Details</h6>
            <div><strong>Check-in:</strong> {{ $booking->check_in_date->format('M d, Y') }}</div>
            <div><strong>Check-out:</strong> {{ $booking->check_out_date->format('M d, Y') }}</div>
            <div><strong>Room:</strong> {{ $checkIn->room->number ?? 'N/A' }}</div>
            <div><strong>Nights:</strong> {{ $booking->nights }}</div>
        </div>
    </div>
    <table class="table table-bordered mb-4">
        <thead class="table-light"><tr><th>Description</th><th class="text-center">Qty</th><th class="text-end">Rate</th><th class="text-end">Amount</th></tr></thead>
        <tbody>
            <tr>
                <td>{{ $booking->roomType->name ?? 'Room' }}</td>
                <td class="text-center">{{ $booking->nights }}</td>
                <td class="text-end">${{ number_format($booking->room_rate, 2) }}</td>
                <td class="text-end">${{ number_format($booking->room_rate * $booking->nights, 2) }}</td>
            </tr>
            @foreach($checkIn->charges as $charge)
            <tr>
                <td>{{ ucfirst(str_replace('_',' ',$charge->charge_type)) }} - {{ $charge->description }}</td>
                <td class="text-center">{{ $charge->quantity }}</td>
                <td class="text-end">${{ number_format($charge->unit_price, 2) }}</td>
                <td class="text-end">${{ number_format($charge->total_amount, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            @if($booking->tax_amount > 0)
            <tr><td colspan="3" class="text-end">GST (18%)</td><td class="text-end">${{ number_format($booking->tax_amount, 2) }}</td></tr>
            @endif
            @if(isset($checkOut) && $checkOut->late_checkout_fee > 0)
            <tr><td colspan="3" class="text-end">Late Check-out Fee</td><td class="text-end">${{ number_format($checkOut->late_checkout_fee, 2) }}</td></tr>
            @endif
            @if(isset($checkOut) && $checkOut->minibar_charges > 0)
            <tr><td colspan="3" class="text-end">Minibar</td><td class="text-end">${{ number_format($checkOut->minibar_charges, 2) }}</td></tr>
            @endif
            @if(isset($checkOut) && $checkOut->damage_charges > 0)
            <tr><td colspan="3" class="text-end">Damage</td><td class="text-end">${{ number_format($checkOut->damage_charges, 2) }}</td></tr>
            @endif
            @if($booking->discount_amount > 0)
            <tr><td colspan="3" class="text-end text-success">Discount</td><td class="text-end text-success">-${{ number_format($booking->discount_amount, 2) }}</td></tr>
            @endif
            <tr class="table-primary"><td colspan="3" class="text-end fw-bold fs-6">Total</td><td class="text-end fw-bold fs-6">${{ number_format($booking->total_amount, 2) }}</td></tr>
        </tfoot>
    </table>
    <div class="row mb-4">
        <div class="col-md-6">
            <h6 class="text-muted fw-bold mb-2">Payments</h6>
            @if($booking->payments->count())
            <table class="table table-sm table-bordered mb-0">
                <thead><tr><th>Date</th><th>Method</th><th>Amount</th></tr></thead>
                <tbody>
                    @foreach($booking->payments as $p)
                    <tr><td>{{ $p->processed_at?->format('M d, Y') ?? '-' }}</td><td>{{ ucfirst(str_replace('_',' ',$p->payment_method)) }}</td><td>${{ number_format($p->amount, 2) }}</td></tr>
                    @endforeach
                </tbody>
            </table>
            @else <p class="text-muted small">No payments</p> @endif
        </div>
        <div class="col-md-6 text-end">
            <div class="mb-2">Total Paid: <strong class="text-success">${{ number_format($booking->paid_amount, 2) }}</strong></div>
            <div class="fs-5 fw-bold text-danger">Balance Due: ${{ number_format($booking->balance, 2) }}</div>
        </div>
    </div>
    <div class="text-center text-muted small border-top pt-3">
        <p class="mb-0">Thank you for staying with us!</p>
        <p class="mb-0">{{ $booking->hotel->name ?? config('app.name') }}</p>
    </div>
</div>
</body>
</html>

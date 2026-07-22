<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - {{ $booking->booking_number }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @media print {
            .no-print { display: none !important; }
            body { font-size: 12px; }
        }
        body { font-family: 'Inter', sans-serif; color: #333; }
        .invoice-header { border-bottom: 3px solid #0d6efd; padding-bottom: 1rem; }
        .invoice-table th { background: #f8f9fa; }
    </style>
</head>
<body>
    <div class="container py-4" style="max-width:800px;">
        <div class="no-print mb-3 text-end">
            <button onclick="window.print()" class="btn btn-primary btn-sm"><i class="bi bi-printer me-1"></i> Print Invoice</button>
        </div>

        <div class="invoice-header d-flex justify-content-between align-items-start mb-4">
            <div>
                @if($booking->hotel && $booking->hotel->logo)
                    <img src="{{ asset('storage/' . $booking->hotel->logo) }}" alt="Logo" style="max-height:60px;">
                @else
                    <h4 class="fw-bold text-primary mb-0">{{ $booking->hotel->name ?? config('app.name') }}</h4>
                @endif
                <div class="small text-muted">{{ $booking->hotel->address ?? '' }}</div>
                <div class="small text-muted">{{ $booking->hotel->phone ?? '' }} | {{ $booking->hotel->email ?? '' }}</div>
            </div>
            <div class="text-end">
                <h3 class="fw-bold text-primary mb-0">INVOICE</h3>
                <div class="fw-semibold">{{ $booking->booking_number }}</div>
                <div class="small text-muted">Date: {{ now()->format('M d, Y') }}</div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-6">
                <h6 class="text-muted fw-bold mb-2">Bill To</h6>
                <div class="fw-semibold">{{ $booking->guest->full_name ?? 'N/A' }}</div>
                <div class="small">{{ $booking->guest->email ?? '' }}</div>
                <div class="small">{{ $booking->guest->phone ?? '' }}</div>
                <div class="small">{{ $booking->guest->address ?? '' }}</div>
            </div>
            <div class="col-md-6 text-end">
                <h6 class="text-muted fw-bold mb-2">Stay Details</h6>
                <div><strong>Check-in:</strong> {{ $booking->check_in_date->format('M d, Y') }}</div>
                <div><strong>Check-out:</strong> {{ $booking->check_out_date->format('M d, Y') }}</div>
                <div><strong>Nights:</strong> {{ $booking->nights }}</div>
                <div><strong>Room Type:</strong> {{ $booking->roomType->name ?? 'N/A' }}</div>
                @if($booking->bookingRooms->count())
                    <div><strong>Room(s):</strong> {{ $booking->bookingRooms->pluck('room_number')->implode(', ') }}</div>
                @endif
            </div>
        </div>

        <table class="table table-bordered invoice-table mb-4">
            <thead>
                <tr>
                    <th>Description</th>
                    <th class="text-center">Qty/Nights</th>
                    <th class="text-end">Rate</th>
                    <th class="text-end">Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $booking->roomType->name ?? 'Room' }}</td>
                    <td class="text-center">{{ $booking->nights }}</td>
                    <td class="text-end">${{ number_format($booking->room_rate, 2) }}</td>
                    <td class="text-end">${{ number_format($booking->room_rate * $booking->nights, 2) }}</td>
                </tr>
                @foreach($booking->charges as $charge)
                    <tr>
                        <td>{{ ucfirst(str_replace('_',' ',$charge->charge_type)) }} - {{ $charge->description }}</td>
                        <td class="text-center">{{ $charge->quantity }}</td>
                        <td class="text-end">${{ number_format($charge->unit_price, 2) }}</td>
                        <td class="text-end">${{ number_format($charge->total_amount, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" class="text-end fw-bold">Subtotal</td>
                    <td class="text-end">${{ number_format($booking->room_rate * $booking->nights + $booking->charges->sum('total_amount'), 2) }}</td>
                </tr>
                @if($booking->discount_amount > 0)
                    <tr>
                        <td colspan="3" class="text-end text-success">Discount</td>
                        <td class="text-end text-success">-${{ number_format($booking->discount_amount, 2) }}</td>
                    </tr>
                @endif
                @if($booking->tax_amount > 0)
                    <tr>
                        <td colspan="3" class="text-end">GST (18%)</td>
                        <td class="text-end">${{ number_format($booking->tax_amount, 2) }}</td>
                    </tr>
                @endif
                <tr class="table-primary">
                    <td colspan="3" class="text-end fw-bold fs-6">Total</td>
                    <td class="text-end fw-bold fs-6">${{ number_format($booking->total_amount, 2) }}</td>
                </tr>
            </tfoot>
        </table>

        <div class="row mb-4">
            <div class="col-md-6">
                <h6 class="text-muted fw-bold mb-2">Payments</h6>
                @if($booking->payments->count())
                    <table class="table table-sm table-bordered mb-0">
                        <thead><tr><th>Date</th><th>Method</th><th>Amount</th></tr></thead>
                        <tbody>
                            @foreach($booking->payments as $payment)
                                <tr>
                                    <td>{{ $payment->processed_at?->format('M d, Y') ?? '-' }}</td>
                                    <td>{{ ucfirst(str_replace('_',' ',$payment->payment_method)) }}</td>
                                    <td>${{ number_format($payment->amount, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="text-muted small">No payments recorded</p>
                @endif
            </div>
            <div class="col-md-6 text-end">
                <div class="mb-2">Total Paid: <strong class="text-success">${{ number_format($booking->paid_amount, 2) }}</strong></div>
                <div class="fs-5 fw-bold text-danger">Balance Due: ${{ number_format($booking->balance, 2) }}</div>
            </div>
        </div>

        <div class="text-center text-muted small border-top pt-3">
            <p class="mb-0">Thank you for staying with us!</p>
            <p class="mb-0">{{ $booking->hotel->name ?? config('app.name') }} | {{ $booking->hotel->phone ?? '' }} | {{ $booking->hotel->email ?? '' }}</p>
        </div>
    </div>
</body>
</html>

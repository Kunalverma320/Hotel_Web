@extends('admin.layouts.app')
@section('title', 'Booking ' . $booking->booking_number)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0">Booking {{ $booking->booking_number }}</h4>
        <small class="text-muted">Created {{ $booking->created_at->format('M d, Y h:i A') }}</small>
    </div>
    <div class="d-flex gap-2">
        @if($booking->status === 'pending')
            <form method="POST" action="{{ route('admin.bookings.confirm', $booking->id) }}">
                @csrf
                <button class="btn btn-success btn-sm"><i class="bi bi-check-circle me-1"></i> Confirm</button>
            </form>
        @endif
        @if(in_array($booking->status, ['confirmed', 'checked_in']))
            <a href="{{ route('admin.checkin.show', $booking->id) }}" class="btn btn-info btn-sm"><i class="bi bi-box-arrow-in-right me-1"></i> Check-in</a>
        @endif
        @if(in_array($booking->status, ['pending', 'confirmed']))
            <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#cancelModal"><i class="bi bi-x-circle me-1"></i> Cancel</button>
        @endif
        <a href="{{ route('admin.bookings.print-invoice', $booking->id) }}" class="btn btn-outline-secondary btn-sm" target="_blank"><i class="bi bi-printer me-1"></i> Invoice</a>
        @if(in_array($booking->status, ['pending']))
            <a href="{{ route('admin.bookings.edit', $booking->id) }}" class="btn btn-primary btn-sm"><i class="bi bi-pencil me-1"></i> Edit</a>
        @endif
        <a href="{{ route('admin.bookings.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i> Back</a>
    </div>
</div>

{{-- Status Timeline --}}
<div class="card mb-4">
    <div class="card-body">
        <div class="d-flex align-items-center justify-content-between">
            @php
                $statuses = ['pending', 'confirmed', 'checked_in', 'checked_out'];
                $currentIndex = array_search($booking->status, $statuses);
            @endphp
            @foreach($statuses as $index => $s)
                <div class="text-center flex-fill">
                    <div class="rounded-circle mx-auto mb-2 d-flex align-items-center justify-content-center {{ $index <= $currentIndex ? 'bg-primary' : 'bg-light' }}" style="width:40px;height:40px;">
                        <i class="bi bi-{{ $index <= $currentIndex ? 'check' : 'circle' }} {{ $index <= $currentIndex ? 'text-white' : 'text-muted' }}"></i>
                    </div>
                    <div class="small fw-semibold {{ $index <= $currentIndex ? 'text-primary' : 'text-muted' }}">{{ ucfirst(str_replace('_', ' ', $s)) }}</div>
                </div>
                @if(!$loop->last)
                    <div class="flex-fill border-top mx-2 {{ $index < $currentIndex ? 'border-primary' : '' }}"></div>
                @endif
            @endforeach
            @if($booking->status === 'cancelled')
                <div class="text-center flex-fill">
                    <div class="rounded-circle mx-auto mb-2 bg-danger d-flex align-items-center justify-content-center" style="width:40px;height:40px;">
                        <i class="bi bi-x text-white"></i>
                    </div>
                    <div class="small fw-semibold text-danger">Cancelled</div>
                </div>
            @endif
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        {{-- Booking Info --}}
        <div class="card mb-4">
            <div class="card-header"><h6 class="mb-0 fw-semibold">Booking Information</h6></div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-3"><label class="text-muted small">Status</label>
                        <div><span class="badge bg-{{ ['pending'=>'warning','confirmed'=>'info','checked_in'=>'success','checked_out'=>'primary','cancelled'=>'danger'][$booking->status] ?? 'secondary' }} fs-6">{{ ucfirst(str_replace('_',' ',$booking->status)) }}</span></div>
                    </div>
                    <div class="col-md-3"><label class="text-muted small">Hotel</label><div class="fw-semibold">{{ $booking->hotel->name ?? 'N/A' }}</div></div>
                    <div class="col-md-3"><label class="text-muted small">Room Type</label><div class="fw-semibold">{{ $booking->roomType->name ?? 'N/A' }}</div></div>
                    <div class="col-md-3"><label class="text-muted small">Source</label><div class="fw-semibold">{{ ucfirst(str_replace('_',' ',$booking->source ?? 'N/A')) }}</div></div>
                    <div class="col-md-3"><label class="text-muted small">Check-in</label><div class="fw-semibold">{{ $booking->check_in_date->format('M d, Y') }}</div></div>
                    <div class="col-md-3"><label class="text-muted small">Check-out</label><div class="fw-semibold">{{ $booking->check_out_date->format('M d, Y') }}</div></div>
                    <div class="col-md-3"><label class="text-muted small">Nights</label><div class="fw-semibold">{{ $booking->nights }}</div></div>
                    <div class="col-md-3"><label class="text-muted small">Guests</label><div class="fw-semibold">{{ $booking->adults }} Adults, {{ $booking->children ?? 0 }} Children</div></div>
                    @if($booking->special_requests)
                        <div class="col-md-12"><label class="text-muted small">Special Requests</label><div>{{ $booking->special_requests }}</div></div>
                    @endif
                    @if($booking->cancellation_reason)
                        <div class="col-md-12"><label class="text-muted small text-danger">Cancellation Reason</label><div class="text-danger">{{ $booking->cancellation_reason }}</div></div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Charges Breakdown --}}
        <div class="card mb-4">
            <div class="card-header"><h6 class="mb-0 fw-semibold">Charges & Payments</h6></div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead><tr><th>Type</th><th>Description</th><th>Amount</th></tr></thead>
                        <tbody>
                            <tr>
                                <td>Room</td>
                                <td>{{ $booking->roomType->name }} x {{ $booking->nights }} nights</td>
                                <td class="fw-semibold">${{ number_format($booking->room_rate * $booking->nights, 2) }}</td>
                            </tr>
                            @if($booking->tax_amount > 0)
                                <tr><td>Tax</td><td>GST 18%</td><td class="fw-semibold">${{ number_format($booking->tax_amount, 2) }}</td></tr>
                            @endif
                            @if($booking->discount_amount > 0)
                                <tr><td>Discount</td><td>-</td><td class="text-success fw-semibold">-${{ number_format($booking->discount_amount, 2) }}</td></tr>
                            @endif
                            @foreach($booking->charges as $charge)
                                <tr>
                                    <td>{{ ucfirst(str_replace('_',' ',$charge->charge_type)) }}</td>
                                    <td>{{ $charge->description }}</td>
                                    <td class="fw-semibold">${{ number_format($charge->total_amount, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="table-light">
                                <td colspan="2" class="fw-bold">Total</td>
                                <td class="fw-bold">${{ number_format($booking->total_amount, 2) }}</td>
                            </tr>
                            <tr>
                                <td colspan="2" class="text-success">Paid</td>
                                <td class="text-success fw-semibold">${{ number_format($booking->paid_amount, 2) }}</td>
                            </tr>
                            <tr class="table-warning">
                                <td colspan="2" class="fw-bold">Balance</td>
                                <td class="fw-bold">${{ number_format($booking->balance, 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        {{-- Payment History --}}
        @if($booking->payments->count())
            <div class="card mb-4">
                <div class="card-header"><h6 class="mb-0 fw-semibold">Payment History</h6></div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <thead><tr><th>Date</th><th>Method</th><th>Reference</th><th>Amount</th><th>Status</th></tr></thead>
                            <tbody>
                                @foreach($booking->payments as $payment)
                                    <tr>
                                        <td>{{ $payment->processed_at?->format('M d, Y h:i A') ?? '-' }}</td>
                                        <td>{{ ucfirst(str_replace('_',' ',$payment->payment_method)) }}</td>
                                        <td>{{ $payment->reference_number ?? '-' }}</td>
                                        <td class="fw-semibold">${{ number_format($payment->amount, 2) }}</td>
                                        <td><span class="badge bg-{{ $payment->payment_status == 'completed' ? 'success' : 'warning' }}">{{ ucfirst($payment->payment_status) }}</span></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <div class="col-lg-4">
        {{-- Guest Info --}}
        <div class="card mb-4">
            <div class="card-header"><h6 class="mb-0 fw-semibold">Guest Information</h6></div>
            <div class="card-body">
                @if($booking->guest)
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-3" style="width:50px;height:50px;font-size:1.2rem;">
                            {{ strtoupper(substr($booking->guest->first_name, 0, 1)) }}
                        </div>
                        <div>
                            <div class="fw-bold">{{ $booking->guest->full_name }}</div>
                            <div class="small text-muted">{{ $booking->guest->email ?? '' }} {{ $booking->guest->phone ?? '' }}</div>
                        </div>
                    </div>
                    <div class="small">
                        <div class="mb-1"><i class="bi bi-envelope me-2 text-muted"></i>{{ $booking->guest->email ?? 'N/A' }}</div>
                        <div class="mb-1"><i class="bi bi-phone me-2 text-muted"></i>{{ $booking->guest->phone ?? 'N/A' }}</div>
                        <div class="mb-1"><i class="bi bi-building me-2 text-muted"></i>{{ $booking->guest->company_name ?? 'N/A' }}</div>
                        <div class="mb-1"><i class="bi bi-globe me-2 text-muted"></i>{{ $booking->guest->nationality ?? 'N/A' }}</div>
                    </div>
                    <a href="{{ route('admin.guests.show', $booking->guest_id) }}" class="btn btn-outline-primary btn-sm w-100 mt-3"><i class="bi bi-person me-1"></i> View Full Profile</a>
                @else
                    <p class="text-muted mb-0">No guest information</p>
                @endif
            </div>
        </div>

        {{-- Room Details --}}
        @if($booking->bookingRooms->count())
            <div class="card mb-4">
                <div class="card-header"><h6 class="mb-0 fw-semibold">Allocated Rooms</h6></div>
                <div class="card-body">
                    @foreach($booking->bookingRooms as $br)
                        <div class="d-flex justify-content-between align-items-center {{ !$loop->last ? 'mb-2 pb-2 border-bottom' : '' }}">
                            <div>
                                <div class="fw-semibold">Room {{ $br->room_number ?? ($br->room->number ?? 'N/A') }}</div>
                                <div class="small text-muted">${{ number_format($br->rate_per_night, 2) }}/night x {{ $br->nights }} nights</div>
                            </div>
                            <span class="badge bg-{{ $br->status == 'active' ? 'success' : 'secondary' }}">{{ ucfirst($br->status) }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>

{{-- Cancel Modal --}}
<div class="modal fade" id="cancelModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.bookings.cancel', $booking->id) }}">
                @csrf @method('PATCH')
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Cancel Booking</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to cancel booking <strong>{{ $booking->booking_number }}</strong>?</p>
                    <div class="mb-3">
                        <label class="form-label">Cancellation Reason <span class="text-danger">*</span></label>
                        <textarea name="cancellation_reason" class="form-control" rows="3" required placeholder="Enter reason for cancellation..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger">Cancel Booking</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

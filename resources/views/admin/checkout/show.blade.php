@extends('admin.layouts.app')
@section('title', 'Check-out - ' . $booking->booking_number)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0">Check-out Process</h4>
        <small class="text-muted">Booking {{ $booking->booking_number }} | {{ $booking->guest->full_name ?? 'N/A' }}</small>
    </div>
    <div class="d-flex gap-2">
        @if($checkIn)
            <a href="{{ route('admin.checkout.generate-invoice', $checkIn->id) }}" class="btn btn-outline-primary btn-sm" target="_blank"><i class="bi bi-printer me-1"></i> Invoice</a>
        @endif
        <a href="{{ route('admin.checkout') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i> Back</a>
    </div>
</div>

@if($booking->status === 'checked_out' || $booking->checkOuts->where('status', 'completed')->count())
    <div class="alert alert-success">
        <i class="bi bi-check-circle me-1"></i> Guest has already been checked out.
    </div>
@endif

<div class="row g-4">
    <div class="col-lg-8">
        @if($checkIn && $booking->status !== 'checked_out' && !$booking->checkOuts->where('status', 'completed')->count())
            {{-- Charges Summary --}}
            <div class="card mb-4">
                <div class="card-header"><h6 class="mb-0 fw-semibold">Charges Summary</h6></div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead><tr><th>Type</th><th>Description</th><th>Qty</th><th>Rate</th><th>Amount</th><th class="no-print"></th></tr></thead>
                            <tbody>
                                <tr>
                                    <td>Room</td>
                                    <td>{{ $booking->roomType->name }} ({{ $checkIn->room->number }})</td>
                                    <td>{{ $booking->nights }}</td>
                                    <td>${{ number_format($booking->room_rate, 2) }}</td>
                                    <td class="fw-semibold">${{ number_format($booking->room_rate * $booking->nights, 2) }}</td>
                                    <td class="no-print"></td>
                                </tr>
                                @foreach($charges as $charge)
                                    <tr>
                                        <td>{{ ucfirst(str_replace('_',' ',$charge->charge_type)) }}</td>
                                        <td>{{ $charge->description }}</td>
                                        <td>{{ $charge->quantity }}</td>
                                        <td>${{ number_format($charge->unit_price, 2) }}</td>
                                        <td class="fw-semibold">${{ number_format($charge->total_amount, 2) }}</td>
                                        <td class="no-print">
                                            <form method="POST" action="{{ route('admin.checkout.remove-charge', $charge->id) }}" class="d-inline" onsubmit="return confirm('Remove this charge?')">
                                                @csrf @method('DELETE')
                                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="table-light">
                                    <td colspan="4" class="text-end fw-bold">Room + Charges</td>
                                    <td class="fw-bold">${{ number_format($totalCharges, 2) }}</td><td></td>
                                </tr>
                                <tr class="table-light">
                                    <td colspan="4" class="text-end fw-bold">Room Charges (Tax 18%)</td>
                                    <td class="fw-bold">${{ number_format($booking->tax_amount, 2) }}</td><td></td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="text-end text-success fw-bold">Paid</td>
                                    <td class="text-success fw-bold">${{ number_format($totalPaid, 2) }}</td><td></td>
                                </tr>
                                <tr class="{{ $balance > 0 ? 'table-warning' : 'table-success' }}">
                                    <td colspan="4" class="text-end fw-bold fs-5">{{ $balance > 0 ? 'Balance Due' : 'Fully Paid' }}</td>
                                    <td class="fw-bold fs-5">${{ number_format(abs($balance), 2) }}</td><td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Add Charge --}}
            <div class="card mb-4">
                <div class="card-header"><h6 class="mb-0 fw-semibold">Add Additional Charge</h6></div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.checkout.add-charge') }}">
                        @csrf
                        <input type="hidden" name="check_in_id" value="{{ $checkIn->id }}">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label">Type <span class="text-danger">*</span></label>
                                <select name="charge_type" class="form-select form-select-sm" required>
                                    @foreach(['minibar','restaurant','laundry','spa','damage','late_checkout','parking','telephone','other'] as $ct)
                                        <option value="{{ $ct }}">{{ ucfirst(str_replace('_',' ',$ct)) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Description <span class="text-danger">*</span></label>
                                <input type="text" name="description" class="form-control form-control-sm" required>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Qty <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" name="quantity" class="form-control form-control-sm" value="1" min="0.01" required>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Unit Price <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" name="unit_price" class="form-control form-control-sm" required min="0">
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="submit" class="btn btn-sm btn-primary w-100"><i class="bi bi-plus-lg me-1"></i> Add</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Process Check-out Form --}}
            <div class="card mb-4">
                <div class="card-header bg-warning"><h6 class="mb-0 fw-semibold">Process Check-out</h6></div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.checkout.process', $checkIn->id) }}">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Key Cards Returned</label>
                                <input type="number" name="key_cards_returned" class="form-control" value="{{ old('key_cards_returned', $checkIn->key_cards_issued ?? 2) }}" min="0">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Late Check-out Fee</label>
                                <input type="number" step="0.01" name="late_checkout_fee" class="form-control" value="{{ old('late_checkout_fee', 0) }}" min="0">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Minibar Charges</label>
                                <input type="number" step="0.01" name="minibar_charges" class="form-control" value="{{ old('minibar_charges', 0) }}" min="0">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Damage Charges</label>
                                <input type="number" step="0.01" name="damage_charges" class="form-control" value="{{ old('damage_charges', 0) }}" min="0">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Refund Amount</label>
                                <input type="number" step="0.01" name="refund_amount" class="form-control" value="{{ old('refund_amount', 0) }}" min="0">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Room Condition</label>
                                <select name="condition_notes" class="form-select">
                                    <option value="Good">Good</option>
                                    <option value="Fair">Fair</option>
                                    <option value="Poor">Poor</option>
                                    <option value="Damaged">Damaged</option>
                                </select>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Notes</label>
                                <textarea name="notes" class="form-control" rows="2">{{ old('notes') }}</textarea>
                            </div>
                        </div>
                        <div class="mt-4">
                            <button type="submit" class="btn btn-warning btn-lg" onclick="return confirm('Process check-out? This will finalize all charges.')">
                                <i class="bi bi-box-arrow-right me-1"></i> Complete Check-out
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif

        @if($booking->status === 'checked_out' || $booking->checkOuts->where('status', 'completed')->count())
            {{-- Check-out receipt --}}
            @php $checkOut = $booking->checkOuts->where('status', 'completed')->latest()->first(); @endphp
            @if($checkOut)
                <div class="card mb-4">
                    <div class="card-header bg-success text-white"><h6 class="mb-0 fw-semibold"><i class="bi bi-check-circle me-1"></i> Check-out Completed</h6></div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4"><label class="text-muted small">Checked out at</label><div class="fw-semibold">{{ $checkOut->check_out_time->format('M d, Y h:i A') }}</div></div>
                            <div class="col-md-4"><label class="text-muted small">Checked out by</label><div class="fw-semibold">{{ $checkOut->checkedOutBy->name ?? 'N/A' }}</div></div>
                            <div class="col-md-4"><label class="text-muted small">Final Charges</label><div class="fw-bold text-danger">${{ number_format($checkOut->final_charges, 2) }}</div></div>
                            <div class="col-md-4"><label class="text-muted small">Amount Paid</label><div class="fw-bold text-success">${{ number_format($checkOut->amount_paid, 2) }}</div></div>
                            <div class="col-md-4"><label class="text-muted small">Balance</label><div class="fw-bold">${{ number_format($checkOut->balance_due, 2) }}</div></div>
                            <div class="col-md-4"><label class="text-muted small">Refund</label><div class="fw-bold">${{ number_format($checkOut->refund_amount, 2) }}</div></div>
                            <div class="col-md-4"><label class="text-muted small">Key Cards Returned</label><div>{{ $checkOut->key_cards_returned }}</div></div>
                            <div class="col-md-4"><label class="text-muted small">Room Condition</label><div>{{ $checkOut->condition_notes ?? 'N/A' }}</div></div>
                        </div>
                    </div>
                </div>
            @endif
        @endif
    </div>

    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-header"><h6 class="mb-0 fw-semibold">Stay Summary</h6></div>
            <div class="card-body">
                <div class="mb-2"><span class="text-muted">Booking:</span> {{ $booking->booking_number }}</div>
                <div class="mb-2"><span class="text-muted">Guest:</span> {{ $booking->guest->full_name ?? 'N/A' }}</div>
                <div class="mb-2"><span class="text-muted">Room:</span> {{ $checkIn->room->number ?? 'N/A' }}</div>
                <div class="mb-2"><span class="text-muted">Check-in:</span> {{ $booking->check_in_date->format('M d, Y') }}</div>
                <div class="mb-2"><span class="text-muted">Check-out:</span> {{ $booking->check_out_date->format('M d, Y') }}</div>
                <div class="mb-2"><span class="text-muted">Nights:</span> {{ $booking->nights }}</div>
                <hr>
                <div class="mb-2"><span class="text-muted">Room Rate:</span> ${{ number_format($booking->room_rate, 2) }}/night</div>
                <div class="mb-2"><span class="text-muted">Room Total:</span> ${{ number_format($booking->room_rate * $booking->nights, 2) }}</div>
                <div class="mb-2"><span class="text-muted">Additional Charges:</span> ${{ number_format($totalCharges, 2) }}</div>
                <div class="mb-2"><span class="text-muted">Tax:</span> ${{ number_format($booking->tax_amount, 2) }}</div>
                <div class="mb-2"><span class="text-muted">Total Paid:</span> <span class="text-success">${{ number_format($totalPaid, 2) }}</span></div>
                <div class="fs-5 fw-bold {{ $balance > 0 ? 'text-danger' : 'text-success' }}">
                    {{ $balance > 0 ? 'Balance: $' . number_format($balance, 2) : 'Settled' }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

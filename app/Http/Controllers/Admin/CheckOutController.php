<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\CheckIn;
use App\Models\CheckOut;
use App\Models\Charge;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckOutController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with(['hotel', 'guest', 'roomType', 'checkIns.room'])
            ->where('status', 'checked_in')
            ->where('check_out_date', '<=', now()->addDay());

        if ($request->filled('hotel_id')) {
            $query->byHotel($request->hotel_id);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('booking_number', 'like', "%{$search}%")
                  ->orWhereHas('guest', function ($gq) use ($search) {
                      $gq->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%");
                  });
            });
        }

        $bookings = $query->orderBy('check_out_date')->paginate(20);

        return view('admin.checkout.index', compact('bookings'));
    }

    public function show($id)
    {
        $booking = Booking::with(['hotel', 'guest', 'roomType', 'checkIns', 'charges', 'payments'])
            ->findOrFail($id);

        $checkIn = $booking->checkIns()->where('status', 'active')->latest()->first();
        $charges = $checkIn ? $checkIn->charges : collect();
        $totalCharges = $charges->sum('total_amount');
        $totalPaid = $booking->payments()->completed()->sum('amount');
        $balance = ($booking->total_amount + $totalCharges) - $totalPaid;

        return view('admin.checkout.show', compact('booking', 'checkIn', 'charges', 'totalCharges', 'totalPaid', 'balance'));
    }

    public function processCheckOut($checkInId, Request $request)
    {
        $checkIn = CheckIn::with(['booking', 'room'])->findOrFail($checkInId);
        $booking = $checkIn->booking;

        $request->validate([
            'condition_notes' => 'nullable|string',
            'key_cards_returned' => 'nullable|integer|min:0',
            'damage_charges' => 'nullable|numeric|min:0',
            'minibar_charges' => 'nullable|numeric|min:0',
            'late_checkout_fee' => 'nullable|numeric|min:0',
            'refund_amount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $roomCharges = $checkIn->charges()->sum('total_amount');
            $totalCharges = $roomCharges
                + ($request->damage_charges ?? 0)
                + ($request->minibar_charges ?? 0)
                + ($request->late_checkout_fee ?? 0);

            $totalPaid = $booking->payments()->completed()->sum('amount');
            $balanceDue = ($booking->total_amount + $totalCharges) - $totalPaid;

            $checkOut = CheckOut::create([
                'check_in_id' => $checkIn->id,
                'booking_id' => $booking->id,
                'room_id' => $checkIn->room_id,
                'checked_out_by' => auth()->id(),
                'check_out_time' => now(),
                'final_charges' => $totalCharges,
                'amount_paid' => $totalPaid,
                'balance_due' => max(0, $balanceDue),
                'refund_amount' => $request->refund_amount ?? 0,
                'late_checkout_fee' => $request->late_checkout_fee ?? 0,
                'minibar_charges' => $request->minibar_charges ?? 0,
                'damage_charges' => $request->damage_charges ?? 0,
                'condition_notes' => $request->condition_notes,
                'key_cards_returned' => $request->key_cards_returned ?? 0,
                'status' => 'completed',
                'notes' => $request->notes,
            ]);

            $checkIn->update(['status' => 'completed']);
            $checkIn->room->update(['status' => 'dirty']);
            $booking->update([
                'status' => 'checked_out',
                'actual_check_out' => now(),
                'paid_amount' => $totalPaid,
            ]);

            DB::commit();
            return redirect()->route('admin.checkout.show', $booking->id)->with('success', 'Check-out processed successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to process check-out: ' . $e->getMessage());
        }
    }

    public function addCharge(Request $request)
    {
        $request->validate([
            'check_in_id' => 'required|exists:check_ins,id',
            'charge_type' => 'required|string',
            'description' => 'required|string|max:255',
            'quantity' => 'required|numeric|min:0.01',
            'unit_price' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $checkIn = CheckIn::with('booking')->findOrFail($request->check_in_id);
        $totalAmount = $request->quantity * $request->unit_price;

        Charge::create([
            'check_in_id' => $checkIn->id,
            'booking_id' => $checkIn->booking_id,
            'room_id' => $checkIn->room_id,
            'hotel_id' => $checkIn->hotel_id,
            'charge_type' => $request->charge_type,
            'description' => $request->description,
            'quantity' => $request->quantity,
            'unit_price' => $request->unit_price,
            'total_amount' => $totalAmount,
            'notes' => $request->notes,
            'charged_at' => now(),
            'status' => 'pending',
        ]);

        return redirect()->back()->with('success', 'Charge added successfully.');
    }

    public function removeCharge($chargeId)
    {
        $charge = Charge::findOrFail($chargeId);
        $charge->delete();

        return redirect()->back()->with('success', 'Charge removed successfully.');
    }

    public function generateInvoice($checkInId)
    {
        $checkIn = CheckIn::with(['booking.hotel', 'booking.guest', 'booking.roomType', 'room', 'charges', 'booking.payments'])
            ->findOrFail($checkInId);

        return view('admin.checkout.invoice', ['checkIn' => $checkIn, 'booking' => $checkIn->booking]);
    }
}

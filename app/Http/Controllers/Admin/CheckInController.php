<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\CheckIn;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckInController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with(['hotel', 'guest', 'roomType', 'checkIns.room'])
            ->whereIn('status', ['confirmed', 'checked_in'])
            ->where('check_in_date', '<=', now()->addDay());

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

        $bookings = $query->orderBy('check_in_date')->paginate(20);

        return view('admin.checkin.index', compact('bookings'));
    }

    public function show($id)
    {
        $booking = Booking::with(['hotel', 'guest', 'roomType', 'checkIns', 'payments'])
            ->findOrFail($id);

        $availableRooms = Room::where('room_type_id', $booking->room_type_id)
            ->where('status', '!=', 'out_of_order')
            ->whereDoesntHave('bookingRooms', function ($q) use ($booking) {
                $q->where('status', '!=', 'cancelled')
                  ->where('id', '!=', $booking->id)
                  ->where('check_in_date', '<', $booking->check_out_date)
                  ->where('check_out_date', '>', $booking->check_in_date);
            })
            ->get();

        return view('admin.checkin.show', compact('booking', 'availableRooms'));
    }

    public function processCheckIn($bookingId, Request $request)
    {
        $booking = Booking::with(['hotel'])->findOrFail($bookingId);

        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'key_cards_issued' => 'nullable|integer|min:1',
            'deposit_amount' => 'nullable|numeric|min:0',
            'deposit_type' => 'nullable|string',
            'id_verified' => 'boolean',
            'notes' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $room = Room::findOrFail($request->room_id);

            $checkIn = CheckIn::create([
                'booking_id' => $booking->id,
                'room_id' => $room->id,
                'guest_id' => $booking->guest_id,
                'hotel_id' => $booking->hotel_id,
                'checked_in_by' => auth()->id(),
                'check_in_time' => now(),
                'key_cards_issued' => $request->key_cards_issued ?? 2,
                'deposit_amount' => $request->deposit_amount ?? 0,
                'deposit_type' => $request->deposit_type,
                'id_verified' => $request->boolean('id_verified'),
                'id_verified_by' => $request->boolean('id_verified') ? auth()->id() : null,
                'notes' => $request->notes,
                'status' => 'active',
            ]);

            $room->update(['status' => 'occupied']);
            $booking->update([
                'status' => 'checked_in',
                'actual_check_in' => now(),
            ]);

            Booking::where('id', $booking->id)->first()->bookingRooms()->create([
                'room_id' => $room->id,
                'room_number' => $room->number,
                'check_in_date' => $booking->check_in_date,
                'check_out_date' => $booking->check_out_date,
                'rate_per_night' => $booking->room_rate,
                'nights' => $booking->nights,
                'total_amount' => $booking->total_amount,
                'status' => 'active',
            ]);

            DB::commit();
            return redirect()->route('admin.checkin.show', $booking->id)->with('success', 'Check-in processed successfully. Key cards issued: ' . ($request->key_cards_issued ?? 2));
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to process check-in: ' . $e->getMessage());
        }
    }

    public function printRegistrationCard($id)
    {
        $checkIn = CheckIn::with(['booking.hotel', 'booking.guest', 'booking.roomType', 'room'])
            ->findOrFail($id);

        return view('admin.checkin.registration-card', compact('checkIn'));
    }
}

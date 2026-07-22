<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingRoom;
use App\Models\Hotel;
use App\Models\Room;
use App\Models\RoomType;
use App\Models\Guest;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with(['hotel', 'guest', 'roomType', 'bookingRooms.room']);

        if ($request->filled('status')) {
            $query->byStatus($request->status);
        }
        if ($request->filled('hotel_id')) {
            $query->byHotel($request->hotel_id);
        }
        if ($request->filled('room_type_id')) {
            $query->where('room_type_id', $request->room_type_id);
        }
        if ($request->filled('date_from')) {
            $query->where('check_in_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('check_out_date', '<=', $request->date_to);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('booking_number', 'like', "%{$search}%")
                  ->orWhereHas('guest', function ($gq) use ($search) {
                      $gq->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                  });
            });
        }

        $bookings = $query->latest()->paginate(15);
        $hotels = Hotel::active()->orderBy('name')->get();
        $roomTypes = RoomType::active()->orderBy('name')->get();
        $statusCounts = Booking::select('status', DB::raw('count(*) as total'))->groupBy('status')->pluck('total', 'status');

        return view('admin.bookings.index', compact('bookings', 'hotels', 'roomTypes', 'statusCounts'));
    }

    public function create()
    {
        $hotels = Hotel::active()->orderBy('name')->get();
        $roomTypes = RoomType::active()->orderBy('name')->get();
        $guests = Guest::orderBy('first_name')->get();

        return view('admin.bookings.create', compact('hotels', 'roomTypes', 'guests'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'hotel_id' => 'required|exists:hotels,id',
            'guest_id' => 'nullable|exists:guests,id',
            'guest_first_name' => 'required_without:guest_id|string|max:255',
            'guest_last_name' => 'required_without:guest_id|string|max:255',
            'guest_email' => 'nullable|email|max:255',
            'guest_phone' => 'nullable|string|max:30',
            'room_type_id' => 'required|exists:room_types,id',
            'check_in_date' => 'required|date',
            'check_out_date' => 'required|date|after:check_in_date',
            'adults' => 'required|integer|min:1',
            'children' => 'nullable|integer|min:0',
            'special_requests' => 'nullable|string',
            'advance_amount' => 'nullable|numeric|min:0',
            'payment_method' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $guestId = $request->guest_id;
            if (!$guestId && $request->filled('guest_first_name')) {
                $guest = Guest::create([
                    'first_name' => $request->guest_first_name,
                    'last_name' => $request->guest_last_name,
                    'email' => $request->guest_email,
                    'phone' => $request->guest_phone,
                ]);
                $guestId = $guest->id;
            }

            $roomType = RoomType::findOrFail($request->room_type_id);
            $nights = \Carbon\Carbon::parse($request->check_in_date)->diffInDays($request->check_out_date);
            $rate = $roomType->base_price;
            $totalAmount = $rate * $nights;
            $taxAmount = $totalAmount * 0.18;

            $booking = Booking::create([
                'hotel_id' => $request->hotel_id,
                'booking_number' => 'BK' . strtoupper(Str::random(8)),
                'room_type_id' => $request->room_type_id,
                'guest_id' => $guestId,
                'source' => $request->get('source', 'admin'),
                'status' => 'pending',
                'check_in_date' => $request->check_in_date,
                'check_out_date' => $request->check_out_date,
                'adults' => $request->adults,
                'children' => $request->children ?? 0,
                'nights' => $nights,
                'room_rate' => $rate,
                'total_amount' => $totalAmount + $taxAmount,
                'tax_amount' => $taxAmount,
                'paid_amount' => $request->advance_amount ?? 0,
                'special_requests' => $request->special_requests,
            ]);

            if ($request->advance_amount > 0) {
                Payment::create([
                    'booking_id' => $booking->id,
                    'hotel_id' => $request->hotel_id,
                    'payment_number' => 'PAY' . strtoupper(Str::random(8)),
                    'payment_method' => $request->payment_method ?? 'cash',
                    'payment_status' => 'completed',
                    'amount' => $request->advance_amount,
                    'processed_by' => auth()->id(),
                    'processed_at' => now(),
                ]);
            }

            DB::commit();
            return redirect()->route('admin.bookings.show', $booking->id)->with('success', 'Booking created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Failed to create booking: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $booking = Booking::with(['hotel', 'guest', 'roomType', 'bookingRooms.room', 'payments', 'charges', 'checkIns', 'checkOuts'])
            ->findOrFail($id);

        return view('admin.bookings.show', compact('booking'));
    }

    public function edit($id)
    {
        $booking = Booking::findOrFail($id);
        $hotels = Hotel::active()->orderBy('name')->get();
        $roomTypes = RoomType::active()->orderBy('name')->get();
        $guests = Guest::orderBy('first_name')->get();

        return view('admin.bookings.edit', compact('booking', 'hotels', 'roomTypes', 'guests'));
    }

    public function update(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        $request->validate([
            'hotel_id' => 'required|exists:hotels,id',
            'room_type_id' => 'required|exists:room_types,id',
            'check_in_date' => 'required|date',
            'check_out_date' => 'required|date|after:check_in_date',
            'adults' => 'required|integer|min:1',
            'children' => 'nullable|integer|min:0',
            'special_requests' => 'nullable|string',
        ]);

        $nights = \Carbon\Carbon::parse($request->check_in_date)->diffInDays($request->check_out_date);
        $roomType = RoomType::findOrFail($request->room_type_id);
        $totalAmount = ($roomType->base_price * $nights) + ($roomType->base_price * $nights * 0.18);

        $booking->update([
            'hotel_id' => $request->hotel_id,
            'room_type_id' => $request->room_type_id,
            'check_in_date' => $request->check_in_date,
            'check_out_date' => $request->check_out_date,
            'adults' => $request->adults,
            'children' => $request->children ?? 0,
            'nights' => $nights,
            'room_rate' => $roomType->base_price,
            'total_amount' => $totalAmount,
            'special_requests' => $request->special_requests,
        ]);

        return redirect()->route('admin.bookings.show', $booking->id)->with('success', 'Booking updated successfully.');
    }

    public function cancel($id, Request $request)
    {
        $booking = Booking::findOrFail($id);

        $request->validate([
            'cancellation_reason' => 'required|string',
        ]);

        DB::beginTransaction();
        try {
            $booking->update([
                'status' => 'cancelled',
                'cancellation_reason' => $request->cancellation_reason,
                'cancelled_at' => now(),
            ]);

            $booking->bookingRooms()->update(['status' => 'cancelled']);

            DB::commit();
            return redirect()->route('admin.bookings.show', $booking->id)->with('success', 'Booking cancelled successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to cancel booking: ' . $e->getMessage());
        }
    }

    public function confirm($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->update([
            'status' => 'confirmed',
            'confirmed_at' => now(),
        ]);

        return redirect()->route('admin.bookings.show', $booking->id)->with('success', 'Booking confirmed successfully.');
    }

    public function noShow($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->update([
            'status' => 'no_show',
            'cancelled_at' => now(),
        ]);

        return redirect()->route('admin.bookings.show', $booking->id)->with('success', 'Booking marked as no-show.');
    }

    public function printInvoice($id)
    {
        $booking = Booking::with(['hotel', 'guest', 'roomType', 'bookingRooms.room', 'payments', 'charges'])
            ->findOrFail($id);

        return view('admin.bookings.invoice', compact('booking'));
    }

    public function getAvailableRoomTypes(Request $request)
    {
        $request->validate([
            'hotel_id' => 'required|exists:hotels,id',
            'check_in' => 'required|date',
            'check_out' => 'required|date|after:check_in',
        ]);

        $roomTypes = RoomType::where('hotel_id', $request->hotel_id)
            ->active()
            ->withCount(['rooms as total_rooms'])
            ->withCount(['rooms as available_rooms' => function ($q) use ($request) {
                $q->where('status', 'available')
                  ->whereDoesntHave('bookingRooms', function ($brq) use ($request) {
                      $brq->where('status', '!=', 'cancelled')
                          ->where('check_in_date', '<', $request->check_out)
                          ->where('check_out_date', '>', $request->check_in);
                  });
            }])
            ->get()
            ->filter(fn($rt) => $rt->available_rooms > 0);

        return response()->json($roomTypes);
    }

    public function getAvailableRooms(Request $request)
    {
        $request->validate([
            'room_type_id' => 'required|exists:room_types,id',
            'check_in' => 'required|date',
            'check_out' => 'required|date|after:check_in',
        ]);

        $roomType = RoomType::findOrFail($request->room_type_id);

        $rooms = Room::where('room_type_id', $request->room_type_id)
            ->where('status', '!=', 'out_of_order')
            ->whereDoesntHave('bookingRooms', function ($q) use ($request) {
                $q->where('status', '!=', 'cancelled')
                  ->where('check_in_date', '<', $request->check_out)
                  ->where('check_out_date', '>', $request->check_in);
            })
            ->get();

        return response()->json($rooms);
    }
}

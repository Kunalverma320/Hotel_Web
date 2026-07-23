<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Guest;
use App\Models\Hotel;
use App\Models\RoomType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CustomerBookingController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Find bookings associated with this user's email
        $bookings = Booking::with(['hotel', 'roomType'])
            ->whereHas('guest', function ($q) use ($user) {
                $q->where('email', $user->email);
            })
            ->latest()
            ->get();

        return view('auth.my_bookings', compact('bookings'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'hotel_name' => 'required|string',
            'room_type' => 'required|string',
            'guest_name' => 'required|string',
            'guest_email' => 'required|email',
            'check_in' => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after:check_in',
            'notes' => 'nullable|string',
        ]);

        // Find the hotel by name
        $hotel = Hotel::where('name', $request->hotel_name)->firstOrFail();

        // Find the room type under this hotel
        $roomType = RoomType::where('hotel_id', $hotel->id)
            ->where('name', $request->room_type)
            ->firstOrFail();

        // Separate guest name into first and last name
        $nameParts = explode(' ', trim($request->guest_name), 2);
        $firstName = $nameParts[0];
        $lastName = $nameParts[1] ?? 'Guest';

        // Retrieve or create guest CRM record
        $guest = Guest::firstOrCreate(
            ['email' => $request->guest_email],
            [
                'first_name' => $firstName,
                'last_name' => $lastName,
                'phone' => Auth::user()->phone ?? '',
                'status' => true,
            ]
        );

        $checkIn = \Carbon\Carbon::parse($request->check_in);
        $checkOut = \Carbon\Carbon::parse($request->check_out);
        $nights = $checkIn->diffInDays($checkOut);
        if ($nights <= 0) {
            $nights = 1;
        }

        // Room rate and calculations
        $roomRate = $roomType->base_rate;
        $totalAmount = $roomRate * $nights;
        $taxRate = 0.12; // 12% standard tax
        $taxAmount = $totalAmount * $taxRate;
        $netAmount = $totalAmount + $taxAmount;

        // Create booking in database
        $booking = Booking::create([
            'booking_number' => 'BK-' . strtoupper(Str::random(8)),
            'hotel_id' => $hotel->id,
            'room_type_id' => $roomType->id,
            'guest_id' => $guest->id,
            'booking_type' => 'online',
            'check_in_date' => $request->check_in,
            'check_out_date' => $request->check_out,
            'adults' => 1,
            'children' => 0,
            'total_nights' => $nights,
            'room_rate' => $roomRate,
            'total_amount' => $totalAmount,
            'tax_amount' => $taxAmount,
            'net_amount' => $netAmount,
            'payment_status' => 'unpaid',
            'status' => 'pending',
            'notes' => $request->notes,
            'source' => 'Website',
        ]);

        return response()->json([
            'success' => true,
            'booking_number' => $booking->booking_number,
        ]);
    }
}

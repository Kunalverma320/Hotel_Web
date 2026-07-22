<?php
namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Services\BookingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function __construct(private BookingService $bookingService) {}

    public function index(Request $request)
    {
        $bookings = Booking::where('guest_id', $request->user()->guest_id ?? null)
            ->with(['hotel', 'roomType'])
            ->latest()
            ->paginate(15);

        return response()->json($bookings);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'hotel_id' => 'required|exists:hotels,id',
            'room_type_id' => 'required|exists:room_types,id',
            'check_in_date' => 'required|date|after_or_equal:today',
            'check_out_date' => 'required|date|after:check_in_date',
            'adults' => 'required|integer|min:1',
            'children' => 'nullable|integer|min:0',
        ]);

        $booking = $this->bookingService->createBooking($request->all());

        return response()->json([
            'message' => 'Booking created successfully',
            'booking' => $booking,
        ], 201);
    }

    public function show(Booking $booking)
    {
        $booking->load(['hotel', 'roomType', 'guest', 'payments', 'checkIns', 'checkOuts']);
        return response()->json($booking);
    }

    public function availability(Request $request): JsonResponse
    {
        $request->validate([
            'hotel_id' => 'required|exists:hotels,id',
            'room_type_id' => 'required|exists:room_types,id',
            'check_in_date' => 'required|date',
            'check_out_date' => 'required|date|after:check_in_date',
        ]);

        $available = $this->bookingService->getAvailability(
            $request->hotel_id,
            $request->check_in_date,
            $request->check_out_date,
            $request->room_type_id
        );

        return response()->json(['available' => $available]);
    }
}

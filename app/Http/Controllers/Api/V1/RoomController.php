<?php
namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index(Request $request)
    {
        $rooms = Room::where('hotel_id', $request->hotel_id)
            ->where('is_active', true)
            ->when($request->room_type_id, fn($q, $id) => $q->where('room_type_id', $id))
            ->when($request->status, fn($q, $s) => $q->where('status', $s))
            ->with('roomType')
            ->get();

        return response()->json($rooms);
    }

    public function show(Room $room)
    {
        $room->load(['roomType', 'building', 'floor', 'roomImages', 'amenities']);
        return response()->json($room);
    }
}

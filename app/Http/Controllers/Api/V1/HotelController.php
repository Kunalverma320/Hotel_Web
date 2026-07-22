<?php
namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Http\Resources\HotelResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class HotelController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $hotels = Hotel::query()
            ->when($request->city, fn($q, $city) => $q->where('city', $city))
            ->when($request->star_rating, fn($q, $rating) => $q->where('star_rating', $rating))
            ->where('status', true)
            ->with('hotelImages')
            ->paginate($request->get('per_page', 15));

        return HotelResource::collection($hotels);
    }

    public function show(Hotel $hotel): HotelResource
    {
        $hotel->load(['hotelImages', 'amenities', 'hotelRules', 'nearbyPlaces', 'roomTypes.rooms']);
        return new HotelResource($hotel);
    }

    public function rooms(Hotel $hotel, Request $request): JsonResponse
    {
        $rooms = $hotel->rooms()
            ->where('is_active', true)
            ->when($request->room_type_id, fn($q, $id) => $q->where('room_type_id', $id))
            ->when($request->status, fn($q, $s) => $q->where('status', $s))
            ->with('roomType')
            ->get();

        return response()->json($rooms);
    }
}

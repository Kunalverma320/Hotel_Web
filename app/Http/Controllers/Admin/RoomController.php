<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\Hotel;
use App\Models\RoomType;
use App\Models\RoomCategory;
use App\Models\Building;
use App\Models\Floor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoomController extends Controller
{
    public function index(Request $request)
    {
        $query = Room::with(['hotel', 'roomType', 'building', 'floor']);

        if ($request->has('hotel_id')) {
            $selectedHotelId = $request->input('hotel_id');
            if (!empty($selectedHotelId)) {
                $query->byHotel($selectedHotelId);
            }
        } else {
            $selectedHotelId = session('current_hotel_id');
            if ($selectedHotelId) {
                $query->byHotel($selectedHotelId);
            }
        }

        if ($request->filled('type_id')) {
            $query->byRoomType($request->type_id);
        }
        if ($request->filled('floor_id')) {
            $query->byFloor($request->floor_id);
        }
        if ($request->filled('status')) {
            $query->byStatus($request->status);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('room_number', 'like', "%{$search}%")
                  ->orWhere('room_name', 'like', "%{$search}%");
            });
        }

        $rooms = $query->latest()->paginate(24);
        $hotels = Hotel::active()->orderBy('name')->get();
        $roomTypes = RoomType::active()->when($selectedHotelId, fn($q) => $q->byHotel($selectedHotelId))->orderBy('name')->get();
        $floors = Floor::active()->when($selectedHotelId, fn($q) => $q->byHotel($selectedHotelId))->orderBy('floor_number')->get();
        $view = $request->get('view', 'grid');

        return view('admin.rooms.index', compact('rooms', 'hotels', 'roomTypes', 'floors', 'view', 'selectedHotelId'));
    }

    public function create()
    {
        $hotels = Hotel::active()->orderBy('name')->get();
        $selectedHotelId = session('current_hotel_id') ?? $hotels->first()?->id;
        $roomTypes = RoomType::active()->when($selectedHotelId, fn($q) => $q->byHotel($selectedHotelId))->orderBy('name')->get();
        $roomCategories = RoomCategory::active()->orderBy('name')->get();
        $buildings = Building::active()->when($selectedHotelId, fn($q) => $q->byHotel($selectedHotelId))->orderBy('name')->get();
        $floors = Floor::active()->when($selectedHotelId, fn($q) => $q->byHotel($selectedHotelId))->orderBy('floor_number')->get();

        return view('admin.rooms.create', compact('hotels', 'roomTypes', 'roomCategories', 'buildings', 'floors', 'selectedHotelId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'hotel_id' => 'required|exists:hotels,id',
            'room_type_id' => 'required|exists:room_types,id',
            'building_id' => 'nullable|exists:buildings,id',
            'floor_id' => 'nullable|exists:floors,id',
            'number' => 'nullable|string|max:50',
            'room_number' => 'nullable|string|max:50',
            'status' => 'nullable|string',
            'notes' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $data = $request->only([
            'hotel_id', 'room_type_id', 'building_id', 'floor_id',
            'room_number', 'room_name', 'status', 'housekeeping_status',
            'maintenance_status', 'condition', 'notes', 'is_active',
        ]);
        if (empty($data['room_number'])) {
            $data['room_number'] = $request->input('number', '101');
        }

        $room = Room::create($data);
        if ($request->has('amenities')) {
            $room->amenities()->sync($request->amenities);
        }

        return redirect()->route('admin.rooms.index')->with('success', 'Room created successfully.');
    }

    public function show($id)
    {
        $room = Room::with(['hotel', 'roomType', 'building', 'floor', 'amenities', 'images', 'bookingRooms.booking', 'housekeepingAssignments', 'maintenanceRequests'])->findOrFail($id);

        return view('admin.rooms.show', compact('room'));
    }

    public function edit($id)
    {
        $room = Room::findOrFail($id);
        $hotels = Hotel::active()->orderBy('name')->get();
        $selectedHotelId = $room->hotel_id;
        $roomTypes = RoomType::active()->when($selectedHotelId, fn($q) => $q->byHotel($selectedHotelId))->orderBy('name')->get();
        $roomCategories = RoomCategory::active()->orderBy('name')->get();
        $buildings = Building::active()->when($selectedHotelId, fn($q) => $q->byHotel($selectedHotelId))->orderBy('name')->get();
        $floors = Floor::active()->when($selectedHotelId, fn($q) => $q->byHotel($selectedHotelId))->orderBy('floor_number')->get();

        return view('admin.rooms.edit', compact('room', 'hotels', 'roomTypes', 'roomCategories', 'buildings', 'floors'));
    }

    public function update(Request $request, $id)
    {
        $room = Room::findOrFail($id);

        $request->validate([
            'hotel_id' => 'required|exists:hotels,id',
            'room_type_id' => 'required|exists:room_types,id',
            'building_id' => 'nullable|exists:buildings,id',
            'floor_id' => 'nullable|exists:floors,id',
            'room_number' => 'required|string|max:50',
            'status' => 'required|string',
            'housekeeping_status' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $data = $request->only([
            'hotel_id', 'room_type_id', 'building_id', 'floor_id',
            'room_number', 'room_name', 'status', 'housekeeping_status',
            'maintenance_status', 'condition', 'notes', 'is_active',
        ]);

        $room->update($data);
        if ($request->has('amenities')) {
            $room->amenities()->sync($request->amenities);
        }

        return redirect()->route('admin.rooms.index')->with('success', 'Room updated successfully.');
    }

    public function destroy($id)
    {
        $room = Room::findOrFail($id);
        $room->delete();

        return redirect()->route('admin.rooms.index')->with('success', 'Room deleted successfully.');
    }

    public function updateStatus(Request $request, $id, $status = null)
    {
        $newStatus = $status ?? $request->input('status') ?? $request->route('status');

        if (!$newStatus || !in_array($newStatus, ['available', 'occupied', 'maintenance', 'out_of_order', 'reserved', 'dirty', 'clean', 'inspected'])) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Invalid or missing status parameter.'], 422);
            }
            return redirect()->back()->with('error', 'The status field is required.');
        }

        $room = Room::findOrFail($id);
        $room->update(['status' => $newStatus]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Room status updated to ' . $newStatus,
                'room_id' => $room->id,
                'status' => $newStatus,
            ]);
        }

        return redirect()->back()->with('success', 'Room status updated successfully.');
    }

    public function updateHousekeeping(Request $request, $id)
    {
        $request->validate(['housekeeping_status' => 'required|string']);
        $room = Room::findOrFail($id);
        $room->update(['housekeeping_status' => $request->housekeeping_status]);

        return redirect()->back()->with('success', 'Housekeeping status updated successfully.');
    }

    public function availability(Request $request)
    {
        $hotels = Hotel::active()->orderBy('name')->get();
        $hotelId = $request->get('hotel_id', $hotels->first()?->id);
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->copy()->addMonths(1)->format('Y-m-d'));

        $rooms = Room::with(['roomType', 'floor'])
            ->when($hotelId, fn($q) => $q->byHotel($hotelId))
            ->active()
            ->orderBy('room_number')
            ->get();

        return view('admin.rooms.availability', compact('rooms', 'hotels', 'hotelId', 'startDate', 'endDate'));
    }

    public function getAvailability(Request $request)
    {
        $request->validate([
            'hotel_id' => 'required|exists:hotels,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $rooms = Room::with('roomType')
            ->byHotel($request->hotel_id)
            ->active()
            ->orderBy('room_number')
            ->get();

        $bookings = \App\Models\Booking::where('hotel_id', $request->hotel_id)
            ->whereIn('status', ['confirmed', 'checked_in'])
            ->where('check_in_date', '<=', $request->end_date)
            ->where('check_out_date', '>=', $request->start_date)
            ->with('bookingRooms')
            ->get();

        $availability = $rooms->map(function ($room) use ($bookings, $request) {
            $roomBookings = $bookings->flatMap(function ($booking) use ($room) {
                return $booking->bookingRooms->where('room_id', $room->id)->map(function ($br) use ($booking) {
                    return [
                        'start' => $br->check_in_date->format('Y-m-d'),
                        'end' => $br->check_out_date->format('Y-m-d'),
                        'booking_number' => $booking->booking_number,
                        'guest' => $booking->guest->full_name ?? 'N/A',
                    ];
                });
            });

            return [
                'id' => $room->id,
                'number' => $room->number,
                'type' => $room->roomType->name ?? 'N/A',
                'status' => $room->status,
                'bookings' => $roomBookings->values(),
            ];
        });

        return response()->json($availability);
    }

    public function bulkUpdateStatus(Request $request)
    {
        $request->validate([
            'room_ids' => 'required|array',
            'room_ids.*' => 'exists:rooms,id',
            'status' => 'required|in:available,occupied,maintenance,out_of_order,reserved,dirty,clean,inspected',
        ]);

        Room::whereIn('id', $request->room_ids)->update(['status' => $request->status]);

        return redirect()->back()->with('success', count($request->room_ids) . ' room(s) status updated successfully.');
    }

    public function view3D(Request $request)
    {
        $hotels = Hotel::active()->orderBy('name')->get();
        $selectedHotelId = $request->get('hotel_id', session('current_hotel_id') ?? $hotels->first()?->id);

        $hotel = $selectedHotelId ? Hotel::with(['buildings', 'floors'])->find($selectedHotelId) : null;
        $rooms = Room::with(['roomType', 'floor', 'building'])
            ->when($selectedHotelId, fn($q) => $q->byHotel($selectedHotelId))
            ->orderBy('room_number')
            ->get();

        $roomTypes = RoomType::active()->when($selectedHotelId, fn($q) => $q->byHotel($selectedHotelId))->orderBy('name')->get();
        $floors = Floor::active()->when($selectedHotelId, fn($q) => $q->byHotel($selectedHotelId))->orderBy('floor_number')->get();
        $buildings = Building::active()->when($selectedHotelId, fn($q) => $q->byHotel($selectedHotelId))->orderBy('name')->get();

        return view('admin.rooms.view3d', compact('rooms', 'hotels', 'hotel', 'selectedHotelId', 'roomTypes', 'floors', 'buildings'));
    }
}

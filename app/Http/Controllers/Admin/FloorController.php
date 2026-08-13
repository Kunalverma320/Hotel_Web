<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Building;
use App\Models\Floor;
use App\Models\Hotel;
use Illuminate\Http\Request;

class FloorController extends Controller
{
    public function index(Request $request)
    {
        $query = Floor::with(['hotel', 'building'])->withCount('rooms');

        if ($request->filled('hotel_id')) {
            $selectedHotelId = $request->input('hotel_id');
            if ($selectedHotelId !== 'all') {
                $query->where('hotel_id', $selectedHotelId);
            }
        } else {
            $selectedHotelId = session('current_hotel_id');
            if ($selectedHotelId) {
                $query->where('hotel_id', $selectedHotelId);
            }
        }

        if ($request->filled('building_id')) {
            $query->where('building_id', $request->building_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('floor_number', 'like', "%{$search}%");
            });
        }

        $floors = $query->orderBy('floor_number')->paginate(15);
        $hotels = Hotel::active()->orderBy('name')->get();
        $buildings = Building::where(function ($q) use ($selectedHotelId) {
            if ($selectedHotelId && $selectedHotelId !== 'all') {
                $q->where('hotel_id', $selectedHotelId);
            }
        })->orderBy('name')->get();

        // Statistics
        $totalFloors = Floor::when($selectedHotelId && $selectedHotelId !== 'all', fn($q) => $q->where('hotel_id', $selectedHotelId))->count();
        $activeFloors = Floor::active()->when($selectedHotelId && $selectedHotelId !== 'all', fn($q) => $q->where('hotel_id', $selectedHotelId))->count();

        return view('admin.floors.index', compact('floors', 'hotels', 'buildings', 'selectedHotelId', 'totalFloors', 'activeFloors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'hotel_id' => 'required|exists:hotels,id',
            'building_id' => 'nullable|exists:buildings,id',
            'name' => 'required|string|max:255',
            'floor_number' => 'required|integer',
            'description' => 'nullable|string',
            'status' => 'nullable|boolean',
        ]);

        $validated['status'] = $request->has('status') ? (bool)$request->status : true;

        $floor = Floor::create($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Floor created successfully!',
                'floor' => $floor,
            ]);
        }

        return redirect()->route('admin.floors.index')->with('success', 'Floor created successfully!');
    }

    public function update(Request $request, Floor $floor)
    {
        $validated = $request->validate([
            'hotel_id' => 'required|exists:hotels,id',
            'building_id' => 'nullable|exists:buildings,id',
            'name' => 'required|string|max:255',
            'floor_number' => 'required|integer',
            'description' => 'nullable|string',
            'status' => 'nullable|boolean',
        ]);

        $validated['status'] = $request->has('status') ? (bool)$request->status : true;

        $floor->update($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Floor updated successfully!',
                'floor' => $floor,
            ]);
        }

        return redirect()->route('admin.floors.index')->with('success', 'Floor updated successfully!');
    }

    public function destroy(Floor $floor)
    {
        if ($floor->rooms()->count() > 0) {
            return redirect()->back()->with('error', 'Cannot delete floor as it has associated rooms.');
        }

        $floor->delete();

        return redirect()->route('admin.floors.index')->with('success', 'Floor deleted successfully!');
    }

    public function toggleStatus(Floor $floor)
    {
        $floor->status = !$floor->status;
        $floor->save();

        return redirect()->back()->with('success', 'Floor status updated successfully!');
    }

    public function getFloorsByHotel($hotelId)
    {
        $floors = Floor::active()
            ->where('hotel_id', $hotelId)
            ->orderBy('floor_number')
            ->get();

        return response()->json($floors);
    }
}

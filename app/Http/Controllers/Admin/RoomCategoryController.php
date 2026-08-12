<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RoomCategory;
use App\Models\Hotel;
use Illuminate\Http\Request;

class RoomCategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = RoomCategory::with(['hotel', 'roomTypes']);

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

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $roomCategories = $query->orderBy('sort_order')->latest()->paginate(15);
        $hotels = Hotel::active()->orderBy('name')->get();

        return view('admin.rooms.categories.index', compact('roomCategories', 'hotels', 'selectedHotelId'));
    }

    public function create()
    {
        $hotels = Hotel::active()->orderBy('name')->get();
        $selectedHotelId = session('current_hotel_id');

        return view('admin.rooms.categories.create', compact('hotels', 'selectedHotelId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'hotel_id' => 'required|exists:hotels,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $data = $request->only(['hotel_id', 'name', 'description', 'sort_order']);
        $data['slug'] = \Illuminate\Support\Str::slug($request->name);
        $statusInput = $request->input('status', $request->input('is_active', 1));
        $data['status'] = in_array($statusInput, ['active', '1', 1, true], true) ? 1 : 0;

        RoomCategory::create($data);

        return redirect()->route('admin.room-categories.index')->with('success', 'Room category created successfully.');
    }

    public function edit($id)
    {
        $roomCategory = RoomCategory::findOrFail($id);
        $hotels = Hotel::active()->orderBy('name')->get();
        $selectedHotelId = $roomCategory->hotel_id ?? session('current_hotel_id');

        return view('admin.rooms.categories.edit', compact('roomCategory', 'hotels', 'selectedHotelId'));
    }

    public function update(Request $request, $id)
    {
        $roomCategory = RoomCategory::findOrFail($id);

        $request->validate([
            'hotel_id' => 'required|exists:hotels,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $data = $request->only(['hotel_id', 'name', 'description', 'sort_order']);
        $data['slug'] = \Illuminate\Support\Str::slug($request->name);
        $statusInput = $request->input('status', $request->input('is_active', 1));
        $data['status'] = in_array($statusInput, ['active', '1', 1, true], true) ? 1 : 0;

        $roomCategory->update($data);

        return redirect()->route('admin.room-categories.index')->with('success', 'Room category updated successfully.');
    }

    public function destroy($id)
    {
        $roomCategory = RoomCategory::findOrFail($id);
        $roomCategory->delete();

        return redirect()->route('admin.room-categories.index')->with('success', 'Room category deleted successfully.');
    }
}

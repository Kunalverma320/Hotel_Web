<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RoomType;
use App\Models\Hotel;
use App\Models\RoomCategory;
use Illuminate\Http\Request;

class RoomTypeController extends Controller
{
    public function index(Request $request)
    {
        $query = RoomType::with(['hotel', 'roomCategory']);

        if ($request->filled('hotel_id')) {
            $query->byHotel($request->hotel_id);
        }
        if ($request->filled('category_id')) {
            $query->byCategory($request->category_id);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $roomTypes = $query->latest()->paginate(15);
        $hotels = Hotel::active()->orderBy('name')->get();
        $roomCategories = RoomCategory::active()->orderBy('name')->get();

        return view('admin.rooms.types.index', compact('roomTypes', 'hotels', 'roomCategories'));
    }

    public function create()
    {
        $hotels = Hotel::active()->orderBy('name')->get();
        $roomCategories = RoomCategory::active()->orderBy('name')->get();

        return view('admin.rooms.types.create', compact('hotels', 'roomCategories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'hotel_id' => 'required|exists:hotels,id',
            'room_category_id' => 'nullable|exists:room_categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'base_price' => 'required|numeric|min:0',
            'weekend_price' => 'nullable|numeric|min:0',
            'peak_price' => 'nullable|numeric|min:0',
            'max_adults' => 'required|integer|min:1',
            'max_children' => 'nullable|integer|min:0',
            'max_infants' => 'nullable|integer|min:0',
            'bed_type' => 'nullable|string|max:50',
            'bed_count' => 'nullable|integer|min:0',
            'room_size' => 'nullable|numeric|min:0',
            'room_size_unit' => 'nullable|in:sqft,sqm',
            'smoking_allowed' => 'boolean',
            'pet_allowed' => 'boolean',
            'is_active' => 'boolean',
        ]);

        $data = $request->only([
            'hotel_id', 'room_category_id', 'name', 'description',
            'base_price', 'weekend_price', 'peak_price',
            'max_adults', 'max_children', 'max_infants',
            'bed_type', 'bed_count', 'room_size', 'room_size_unit',
            'smoking_allowed', 'pet_allowed', 'is_active',
        ]);
        $data['slug'] = \Illuminate\Support\Str::slug($request->name);

        RoomType::create($data);

        return redirect()->route('admin.room-types.index')->with('success', 'Room type created successfully.');
    }

    public function show($id)
    {
        $roomType = RoomType::with(['hotel', 'roomCategory', 'rooms'])->findOrFail($id);

        return view('admin.rooms.types.show', compact('roomType'));
    }

    public function edit($id)
    {
        $roomType = RoomType::findOrFail($id);
        $hotels = Hotel::active()->orderBy('name')->get();
        $roomCategories = RoomCategory::active()->orderBy('name')->get();

        return view('admin.rooms.types.edit', compact('roomType', 'hotels', 'roomCategories'));
    }

    public function update(Request $request, $id)
    {
        $roomType = RoomType::findOrFail($id);

        $request->validate([
            'hotel_id' => 'required|exists:hotels,id',
            'room_category_id' => 'nullable|exists:room_categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'base_price' => 'required|numeric|min:0',
            'weekend_price' => 'nullable|numeric|min:0',
            'peak_price' => 'nullable|numeric|min:0',
            'max_adults' => 'required|integer|min:1',
            'max_children' => 'nullable|integer|min:0',
            'max_infants' => 'nullable|integer|min:0',
            'bed_type' => 'nullable|string|max:50',
            'bed_count' => 'nullable|integer|min:0',
            'room_size' => 'nullable|numeric|min:0',
            'room_size_unit' => 'nullable|in:sqft,sqm',
            'smoking_allowed' => 'boolean',
            'pet_allowed' => 'boolean',
            'is_active' => 'boolean',
        ]);

        $data = $request->only([
            'hotel_id', 'room_category_id', 'name', 'description',
            'base_price', 'weekend_price', 'peak_price',
            'max_adults', 'max_children', 'max_infants',
            'bed_type', 'bed_count', 'room_size', 'room_size_unit',
            'smoking_allowed', 'pet_allowed', 'is_active',
        ]);
        $data['slug'] = \Illuminate\Support\Str::slug($request->name);

        $roomType->update($data);

        return redirect()->route('admin.room-types.show', $roomType->id)->with('success', 'Room type updated successfully.');
    }

    public function destroy($id)
    {
        $roomType = RoomType::findOrFail($id);
        $roomType->delete();

        return redirect()->route('admin.room-types.index')->with('success', 'Room type deleted successfully.');
    }
}

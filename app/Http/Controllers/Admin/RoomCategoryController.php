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
        $query = RoomCategory::with(['hotel']);

        if ($request->filled('hotel_id')) {
            $query->byHotel($request->hotel_id);
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

        return view('admin.rooms.categories.index', compact('roomCategories', 'hotels'));
    }

    public function create()
    {
        $hotels = Hotel::active()->orderBy('name')->get();

        return view('admin.rooms.categories.create', compact('hotels'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'hotel_id' => 'required|exists:hotels,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $data = $request->only(['hotel_id', 'name', 'description', 'sort_order', 'is_active']);
        $data['slug'] = \Illuminate\Support\Str::slug($request->name);

        RoomCategory::create($data);

        return redirect()->route('admin.room-categories.index')->with('success', 'Room category created successfully.');
    }

    public function edit($id)
    {
        $roomCategory = RoomCategory::findOrFail($id);
        $hotels = Hotel::active()->orderBy('name')->get();

        return view('admin.rooms.categories.edit', compact('roomCategory', 'hotels'));
    }

    public function update(Request $request, $id)
    {
        $roomCategory = RoomCategory::findOrFail($id);

        $request->validate([
            'hotel_id' => 'required|exists:hotels,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $data = $request->only(['hotel_id', 'name', 'description', 'sort_order', 'is_active']);
        $data['slug'] = \Illuminate\Support\Str::slug($request->name);

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

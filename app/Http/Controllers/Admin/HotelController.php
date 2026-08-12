<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\HotelImage;
use App\Models\Company;
use App\Models\Branch;
use App\Models\Amenity;
use App\Models\Floor;
use App\Models\Building;
use App\Models\RoomType;
use App\Models\RoomCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class HotelController extends Controller
{
    public function index(Request $request)
    {
        $query = Hotel::with('company', 'branch');

        if ($request->filled('search')) {
            $query->where('name', 'like', "%{$request->search}%");
        }
        if ($request->filled('company_id')) {
            $query->where('company_id', $request->company_id);
        }
        if ($request->filled('branch_id')) {
            $query->where('branch_id', $request->branch_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $hotels = $query->latest()->paginate(15);
        $companies = Company::orderBy('name')->pluck('name', 'id');
        return view('admin.hotels.index', compact('hotels', 'companies'));
    }

    public function create()
    {
        $companies = Company::orderBy('name')->pluck('name', 'id');
        $branches = Branch::orderBy('name')->get();
        $amenities = Amenity::orderBy('name')->get();
        return view('admin.hotels.create', compact('companies', 'branches', 'amenities'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'company_id' => 'required|exists:companies,id',
            'branch_id' => 'nullable|exists:branches,id',
            'name' => 'required|string|max:255',
            'tagline' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'logo' => 'nullable|image|max:2048',
            'cover_image' => 'nullable|image|max:5120',
            'star_rating' => 'required|integer|min:1|max:5',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'zipcode' => 'nullable|string|max:20',
            'phone' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'check_in_time' => 'nullable|date_format:H:i',
            'check_out_time' => 'nullable|date_format:H:i',
            'cancellation_policy' => 'nullable|string',
            'status' => 'required',
        ]);
        $data = $request->except('logo', 'cover_image', 'amenities');

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($request->name) . '-' . time();
        }

        $data['status'] = in_array($request->status, ['active', '1', 1, true], true) ? 1 : 0;

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('hotels/logos', 'public');
        }
        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')->store('hotels/covers', 'public');
        }
        $hotel = Hotel::create($data);
        if ($request->has('amenities')) {
            $hotel->amenities()->sync($request->amenities);
        }
        return redirect()->route('admin.hotels.index')->with('success', 'Hotel created successfully.');
    }

    public function show($id)
    {
        $hotel = Hotel::with('company', 'branch', 'amenities', 'images', 'nearbyPlaces')->findOrFail($id);
        return view('admin.hotels.show', compact('hotel'));
    }

    public function edit($id)
    {
        $hotel = Hotel::findOrFail($id);
        $companies = Company::orderBy('name')->pluck('name', 'id');
        $branches = Branch::orderBy('name')->get();
        $amenities = Amenity::orderBy('name')->get();
        return view('admin.hotels.edit', compact('hotel', 'companies', 'branches', 'amenities'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'company_id' => 'required|exists:companies,id',
            'branch_id' => 'nullable|exists:branches,id',
            'name' => 'required|string|max:255',
            'tagline' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'logo' => 'nullable|image|max:2048',
            'cover_image' => 'nullable|image|max:5120',
            'star_rating' => 'required|integer|min:1|max:5',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'zipcode' => 'nullable|string|max:20',
            'phone' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'check_in_time' => 'nullable|date_format:H:i',
            'check_out_time' => 'nullable|date_format:H:i',
            'cancellation_policy' => 'nullable|string',
            'status' => 'required',
        ]);
        $hotel = Hotel::findOrFail($id);
        $data = $request->except('logo', 'cover_image', 'amenities');

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($request->name);
        }

        $data['status'] = in_array($request->status, ['active', '1', 1, true], true) ? 1 : 0;
        if ($request->hasFile('logo')) {
            if ($hotel->logo) {
                Storage::disk('public')->delete($hotel->logo);
            }
            $data['logo'] = $request->file('logo')->store('hotels/logos', 'public');
        }
        if ($request->hasFile('cover_image')) {
            if ($hotel->cover_image) {
                Storage::disk('public')->delete($hotel->cover_image);
            }
            $data['cover_image'] = $request->file('cover_image')->store('hotels/covers', 'public');
        }
        $hotel->update($data);
        if ($request->has('amenities')) {
            $hotel->amenities()->sync($request->amenities);
        } else {
            $hotel->amenities()->detach();
        }
        return redirect()->route('admin.hotels.index')->with('success', 'Hotel updated successfully.');
    }

    public function destroy($id)
    {
        $hotel = Hotel::findOrFail($id);
        if ($hotel->logo) {
            Storage::disk('public')->delete($hotel->logo);
        }
        if ($hotel->cover_image) {
            Storage::disk('public')->delete($hotel->cover_image);
        }
        $hotel->delete();
        return redirect()->route('admin.hotels.index')->with('success', 'Hotel deleted successfully.');
    }

    public function images($id)
    {
        $hotel = Hotel::with('images')->findOrFail($id);
        return view('admin.hotels.images', compact('hotel'));
    }

    public function uploadImage(Request $request, $hotelId)
    {
        $request->validate([
            'images' => 'required|array|max:10',
            'images.*' => 'image|max:5120',
        ]);
        $hotel = Hotel::findOrFail($hotelId);
        foreach ($request->file('images') as $index => $image) {
            $path = $image->store('hotels/' . $hotelId . '/gallery', 'public');
            $hotel->images()->create([
                'path' => $path,
                'caption' => $request->input("images.{$index}.caption", ''),
                'sort_order' => $hotel->images()->count() + 1,
            ]);
        }
        return redirect()->route('admin.hotels.images', $hotelId)->with('success', 'Images uploaded successfully.');
    }

    public function deleteImage($imageId)
    {
        $image = HotelImage::findOrFail($imageId);
        Storage::disk('public')->delete($image->path);
        $image->delete();
        return redirect()->back()->with('success', 'Image deleted successfully.');
    }

    public function amenities($id)
    {
        $hotel = Hotel::with('amenities')->findOrFail($id);
        $amenities = Amenity::orderBy('name')->get();
        return view('admin.hotels.amenities', compact('hotel', 'amenities'));
    }

    public function updateAmenities(Request $request, $hotelId)
    {
        $hotel = Hotel::findOrFail($hotelId);
        $hotel->amenities()->sync($request->amenities ?? []);
        return redirect()->back()->with('success', 'Amenities updated successfully.');
    }

    public function rules($id)
    {
        $hotel = Hotel::findOrFail($id);
        return view('admin.hotels.rules', compact('hotel'));
    }

    public function nearbyPlaces($id)
    {
        $hotel = Hotel::findOrFail($id);
        return view('admin.hotels.nearby-places', compact('hotel'));
    }

    public function policies($id)
    {
        $hotel = Hotel::findOrFail($id);
        return view('admin.hotels.policies', compact('hotel'));
    }

    public function updateStatus($id, $status)
    {
        if (!in_array($status, ['active', 'inactive', '1', '0'])) {
            return redirect()->back()->with('error', 'Invalid status.');
        }
        $statusVal = in_array($status, ['active', '1'], true) ? 1 : 0;
        Hotel::findOrFail($id)->update(['status' => $statusVal]);
        return redirect()->back()->with('success', 'Hotel status updated successfully.');
    }

    public function getOptions($hotelId)
    {
        if ($hotelId === 'all' || $hotelId === '0' || empty($hotelId)) {
            $floors = Floor::active()->orderBy('floor_number')->get();
            $buildings = Building::active()->orderBy('name')->get();
            $roomTypes = RoomType::active()->orderBy('name')->get();
            $categories = RoomCategory::active()->orderBy('name')->get();
        } else {
            $floors = Floor::active()->where('hotel_id', $hotelId)->orderBy('floor_number')->get();
            $buildings = Building::active()->where('hotel_id', $hotelId)->orderBy('name')->get();
            $roomTypes = RoomType::active()->where('hotel_id', $hotelId)->orderBy('name')->get();
            $categories = RoomCategory::active()->where('hotel_id', $hotelId)->orderBy('name')->get();
        }

        return response()->json([
            'floors' => $floors->map(fn($f) => ['id' => $f->id, 'name' => $f->name, 'number' => $f->floor_number ?? $f->number ?? 0]),
            'buildings' => $buildings->map(fn($b) => ['id' => $b->id, 'name' => $b->name]),
            'room_types' => $roomTypes->map(fn($t) => ['id' => $t->id, 'name' => $t->name, 'price' => number_format($t->base_price ?? $t->base_rate ?? 0, 2)]),
            'categories' => $categories->map(fn($c) => ['id' => $c->id, 'name' => $c->name]),
        ]);
    }
}

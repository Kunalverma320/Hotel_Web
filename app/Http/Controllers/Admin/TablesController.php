<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RestaurantTable;
use Illuminate\Http\Request;

class TablesController extends Controller
{
    public function index()
    {
        $tables = RestaurantTable::where('hotel_id', session('current_hotel_id'))->latest()->paginate(20);
        return view('admin.restaurant.tables', compact('tables'));
    }

    public function create()
    {
        return view('admin.restaurant.tables');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'table_number' => 'required|string|max:50',
            'capacity' => 'required|integer|min:1',
            'location' => 'nullable|string|max:50',
        ]);
        $validated['hotel_id'] = session('current_hotel_id');
        $validated['status'] = 'available';
        RestaurantTable::create($validated);
        return redirect()->route('admin.restaurant.tables.index')->with('success', 'Table created.');
    }

    public function edit(RestaurantTable $table)
    {
        return view('admin.restaurant.tables', compact('table'));
    }

    public function update(Request $request, RestaurantTable $table)
    {
        $validated = $request->validate([
            'table_number' => 'required|string|max:50',
            'capacity' => 'required|integer|min:1',
            'location' => 'nullable|string|max:50',
        ]);
        $table->update($validated);
        return redirect()->route('admin.restaurant.tables.index')->with('success', 'Table updated.');
    }

    public function destroy(RestaurantTable $table)
    {
        $table->delete();
        return redirect()->route('admin.restaurant.tables.index')->with('success', 'Table deleted.');
    }
}

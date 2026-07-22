<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FoodItem;
use Illuminate\Http\Request;

class MenuItemController extends Controller
{
    public function index()
    {
        $items = FoodItem::where('hotel_id', session('current_hotel_id'))->with('menuCategory')->latest()->paginate(20);
        return view('admin.restaurant.menu', compact('items'));
    }

    public function create()
    {
        $categories = \App\Models\MenuCategory::where('hotel_id', session('current_hotel_id'))->get();
        return view('admin.restaurant.menu', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'menu_category_id' => 'required|exists:menu_categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'is_veg' => 'nullable|boolean',
        ]);
        $validated['hotel_id'] = session('current_hotel_id');
        $validated['is_available'] = true;
        FoodItem::create($validated);
        return redirect()->route('admin.restaurant.menu-items.index')->with('success', 'Item created.');
    }

    public function edit(FoodItem $menuItem)
    {
        $categories = \App\Models\MenuCategory::where('hotel_id', session('current_hotel_id'))->get();
        return view('admin.restaurant.menu', ['item' => $menuItem, 'categories' => $categories]);
    }

    public function update(Request $request, FoodItem $menuItem)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
        ]);
        $menuItem->update($validated);
        return redirect()->route('admin.restaurant.menu-items.index')->with('success', 'Item updated.');
    }

    public function destroy(FoodItem $menuItem)
    {
        $menuItem->delete();
        return redirect()->route('admin.restaurant.menu-items.index')->with('success', 'Item deleted.');
    }
}

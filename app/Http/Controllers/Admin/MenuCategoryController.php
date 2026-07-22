<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MenuCategory;
use Illuminate\Http\Request;

class MenuCategoryController extends Controller
{
    public function index()
    {
        $categories = MenuCategory::where('hotel_id', session('current_hotel_id'))->with('foodItems')->latest()->paginate(20);
        return view('admin.restaurant.menu', compact('categories'));
    }

    public function create()
    {
        return view('admin.restaurant.menu');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);
        $validated['hotel_id'] = session('current_hotel_id');
        $validated['status'] = true;
        MenuCategory::create($validated);
        return redirect()->route('admin.restaurant.menu-categories.index')->with('success', 'Category created.');
    }

    public function edit(MenuCategory $menuCategory)
    {
        return view('admin.restaurant.menu', ['category' => $menuCategory]);
    }

    public function update(Request $request, MenuCategory $menuCategory)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);
        $menuCategory->update($validated);
        return redirect()->route('admin.restaurant.menu-categories.index')->with('success', 'Category updated.');
    }

    public function destroy(MenuCategory $menuCategory)
    {
        $menuCategory->delete();
        return redirect()->route('admin.restaurant.menu-categories.index')->with('success', 'Category deleted.');
    }
}

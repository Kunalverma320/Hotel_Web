<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RestaurantController extends Controller
{
    public function tables()
    {
        $tables = RestaurantTable::all();
        return view('admin.restaurant.tables', compact('tables'));
    }

    public function tableCreate()
    {
        return view('admin.restaurant.tables');
    }

    public function tableStore(Request $request)
    {
        $request->validate([
            'table_number' => 'required|string|unique:restaurant_tables',
            'capacity' => 'required|integer|min:1',
            'location' => 'nullable|string',
        ]);

        RestaurantTable::create($request->only('table_number', 'capacity', 'location'));

        return redirect()->route('admin.restaurant.tables')->with('success', 'Table created.');
    }

    public function tableEdit($id)
    {
        $table = RestaurantTable::findOrFail($id);
        $tables = RestaurantTable::all();
        return view('admin.restaurant.tables', compact('tables', 'table'));
    }

    public function tableUpdate($id, Request $request)
    {
        $table = RestaurantTable::findOrFail($id);
        $table->update($request->only('table_number', 'capacity', 'location'));

        return redirect()->route('admin.restaurant.tables')->with('success', 'Table updated.');
    }

    public function tableDestroy($id)
    {
        RestaurantTable::findOrFail($id)->delete();
        return redirect()->route('admin.restaurant.tables')->with('success', 'Table deleted.');
    }

    public function menuCategories()
    {
        $categories = MenuCategory::with('foodItems')->get();
        return view('admin.restaurant.menu', compact('categories'));
    }

    public function menuCategoryCreate()
    {
        return view('admin.restaurant.menu');
    }

    public function menuCategoryStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'sort_order' => 'nullable|integer',
        ]);

        MenuCategory::create($request->only('name', 'description', 'sort_order'));

        return redirect()->route('admin.restaurant.menu-categories')->with('success', 'Category created.');
    }

    public function menuCategoryEdit($id)
    {
        $category = MenuCategory::findOrFail($id);
        $categories = MenuCategory::with('foodItems')->get();
        return view('admin.restaurant.menu', compact('categories', 'category'));
    }

    public function menuCategoryUpdate($id, Request $request)
    {
        $category = MenuCategory::findOrFail($id);
        $category->update($request->only('name', 'description', 'sort_order'));

        return redirect()->route('admin.restaurant.menu-categories')->with('success', 'Category updated.');
    }

    public function menuCategoryDestroy($id)
    {
        MenuCategory::findOrFail($id)->delete();
        return redirect()->route('admin.restaurant.menu-categories')->with('success', 'Category deleted.');
    }

    public function foodItems()
    {
        $items = FoodItem::with('category')->latest()->paginate(20);
        $categories = MenuCategory::all();
        return view('admin.restaurant.menu', compact('items', 'categories'));
    }

    public function foodItemCreate()
    {
        $categories = MenuCategory::all();
        return view('admin.restaurant.menu', compact('categories'));
    }

    public function foodItemStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:menu_categories,id',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'is_available' => 'nullable|boolean',
        ]);

        FoodItem::create($request->only('name', 'category_id', 'price', 'description', 'is_available'));

        return redirect()->route('admin.restaurant.food-items')->with('success', 'Food item created.');
    }

    public function foodItemEdit($id)
    {
        $item = FoodItem::findOrFail($id);
        $categories = MenuCategory::all();
        return view('admin.restaurant.menu', compact('item', 'categories'));
    }

    public function foodItemUpdate($id, Request $request)
    {
        $item = FoodItem::findOrFail($id);
        $item->update($request->only('name', 'category_id', 'price', 'description', 'is_available'));

        return redirect()->route('admin.restaurant.food-items')->with('success', 'Food item updated.');
    }

    public function foodItemDestroy($id)
    {
        FoodItem::findOrFail($id)->delete();
        return redirect()->route('admin.restaurant.food-items')->with('success', 'Food item deleted.');
    }

    public function kitchenOrders()
    {
        $orders = KitchenOrder::with('items.foodItem', 'table')
            ->whereIn('status', ['pending', 'preparing', 'ready'])
            ->orderByRaw("FIELD(status, 'pending', 'preparing', 'ready')")
            ->latest()
            ->get();

        return view('admin.restaurant.kitchen', compact('orders'));
    }

    public function kitchenOrderShow($id)
    {
        $order = KitchenOrder::with('items.foodItem', 'table')->findOrFail($id);
        return response()->json($order);
    }

    public function updateOrderStatus($id, $status)
    {
        $order = KitchenOrder::findOrFail($id);
        $order->update(['status' => $status]);

        return redirect()->back()->with('success', 'Order status updated.');
    }

    public function pos()
    {
        $tables = RestaurantTable::all();
        $categories = MenuCategory::with('foodItems')->get();
        $activeOrders = KitchenOrder::whereNotIn('status', ['served', 'cancelled'])->with('table')->get();

        return view('admin.restaurant.pos', compact('tables', 'categories', 'activeOrders'));
    }

    public function reports()
    {
        $totalOrders = KitchenOrder::count();
        $totalRevenue = KitchenOrder::where('status', 'served')->sum('total_amount');
        $ordersByStatus = KitchenOrder::selectRaw('status, count(*) as total')->groupBy('status')->get();
        $topItems = FoodItem::join('kitchen_order_items', 'food_items.id', '=', 'kitchen_order_items.food_item_id')
            ->selectRaw('food_items.name, SUM(kitchen_order_items.quantity) as total_qty')
            ->groupBy('food_items.id', 'food_items.name')
            ->orderByDesc('total_qty')
            ->limit(10)
            ->get();
        $dailyRevenue = KitchenOrder::where('status', 'served')
            ->where('created_at', '>=', now()->subDays(30))
            ->selectRaw('DATE(created_at) as date, SUM(total_amount) as revenue')
            ->groupBy('date')
            ->get();

        return view('admin.restaurant.reports', compact(
            'totalOrders', 'totalRevenue', 'ordersByStatus', 'topItems', 'dailyRevenue'
        ));
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RoomServiceController extends Controller
{
    public function index(Request $request)
    {
        $query = RoomServiceOrder::with('room', 'items.foodItem');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->latest()->paginate(20);

        return view('admin.roomservice.index', compact('orders'));
    }

    public function create()
    {
        $rooms = Room::where('status', 'occupied')->get();
        $menuItems = FoodItem::where('is_available', true)->with('category')->get();

        return view('admin.roomservice.create', compact('rooms', 'menuItems'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'items' => 'required|array|min:1',
            'items.*.food_item_id' => 'required|exists:food_items,id',
            'items.*.quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string',
        ]);

        $total = 0;
        $order = RoomServiceOrder::create([
            'room_id' => $request->room_id,
            'guest_name' => $request->guest_name ?? '',
            'status' => 'pending',
            'notes' => $request->notes,
        ]);

        foreach ($request->items as $item) {
            $foodItem = FoodItem::find($item['food_item_id']);
            $subtotal = $foodItem->price * $item['quantity'];
            $total += $subtotal;

            $order->items()->create([
                'food_item_id' => $item['food_item_id'],
                'quantity' => $item['quantity'],
                'price' => $foodItem->price,
                'subtotal' => $subtotal,
            ]);
        }

        $order->update(['total_amount' => $total]);

        return redirect()->route('admin.roomservice.index')->with('success', 'Room service order created.');
    }

    public function show($id)
    {
        $order = RoomServiceOrder::with('room', 'items.foodItem')->findOrFail($id);
        return view('admin.roomservice.show', compact('order'));
    }

    public function updateStatus($id, $status)
    {
        $order = RoomServiceOrder::findOrFail($id);
        $order->update([
            'status' => $status,
            'completed_at' => $status === 'delivered' ? now() : null,
        ]);

        return redirect()->back()->with('success', 'Order status updated.');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LaundryController extends Controller
{
    public function index(Request $request)
    {
        $query = LaundryOrder::with('room', 'items');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->latest()->paginate(20);

        return view('admin.laundry.index', compact('orders'));
    }

    public function create()
    {
        $rooms = Room::where('status', 'occupied')->get();
        $laundryItems = LaundryItem::all();

        return view('admin.laundry.create', compact('rooms', 'laundryItems'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'items' => 'required|array|min:1',
            'items.*.laundry_item_id' => 'required|exists:laundry_items,id',
            'items.*.quantity' => 'required|integer|min:1',
            'priority' => 'nullable|in:normal,express,urgent',
            'notes' => 'nullable|string',
        ]);

        $total = 0;
        $order = LaundryOrder::create([
            'room_id' => $request->room_id,
            'guest_name' => $request->guest_name ?? '',
            'status' => 'received',
            'priority' => $request->priority ?? 'normal',
            'notes' => $request->notes,
        ]);

        foreach ($request->items as $item) {
            $laundryItem = LaundryItem::find($item['laundry_item_id']);
            $subtotal = $laundryItem->price * $item['quantity'];
            $total += $subtotal;

            $order->items()->create([
                'laundry_item_id' => $item['laundry_item_id'],
                'quantity' => $item['quantity'],
                'price' => $laundryItem->price,
                'subtotal' => $subtotal,
            ]);
        }

        $order->update(['total_amount' => $total]);

        return redirect()->route('admin.laundry.index')->with('success', 'Laundry order created.');
    }

    public function show($id)
    {
        $order = LaundryOrder::with('room', 'items.laundryItem')->findOrFail($id);
        return view('admin.laundry.show', compact('order'));
    }

    public function updateStatus($id, $status)
    {
        $order = LaundryOrder::findOrFail($id);
        $order->update([
            'status' => $status,
            'completed_at' => $status === 'delivered' ? now() : null,
        ]);

        return redirect()->back()->with('success', 'Order status updated.');
    }

    public function reports()
    {
        $totalOrders = LaundryOrder::count();
        $totalRevenue = LaundryOrder::where('status', 'delivered')->sum('total_amount');
        $ordersByStatus = LaundryOrder::selectRaw('status, count(*) as total')->groupBy('status')->get();
        $ordersByPriority = LaundryOrder::selectRaw('priority, count(*) as total')->groupBy('priority')->get();
        $dailyOrders = LaundryOrder::where('created_at', '>=', now()->subDays(30))
            ->selectRaw('DATE(created_at) as date, count(*) as total')
            ->groupBy('date')
            ->get();

        return view('admin.laundry.reports', compact(
            'totalOrders', 'totalRevenue', 'ordersByStatus', 'ordersByPriority', 'dailyOrders'
        ));
    }
}

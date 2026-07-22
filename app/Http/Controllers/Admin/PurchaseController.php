<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\PurchaseReturn;
use App\Models\InventoryItem;
use App\Models\Supplier;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    public function index(Request $request)
    {
        $query = PurchaseOrder::with('supplier');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $query->where('po_number', 'like', '%' . $request->search . '%');
        }

        $orders = $query->latest()->paginate(20);

        return view('admin.purchases.index', compact('orders'));
    }

    public function create()
    {
        $suppliers = Supplier::where('status', 'active')->orderBy('name')->get();
        $items = InventoryItem::where('status', 'active')->orderBy('name')->get();
        $poNumber = 'PO-' . date('Ymd') . '-' . str_pad(PurchaseOrder::whereDate('created_at', today())->count() + 1, 4, '0', STR_PAD_LEFT);

        return view('admin.purchases.create', compact('suppliers', 'items', 'poNumber'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_id'      => 'required|exists:suppliers,id',
            'po_number'        => 'required|string|unique:purchase_orders,po_number',
            'order_date'       => 'required|date',
            'expected_date'    => 'nullable|date',
            'notes'            => 'nullable|string',
            'items'            => 'required|array|min:1',
            'items.*.item_id'  => 'required|exists:inventory_items,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        $subtotal = 0;
        foreach ($validated['items'] as $item) {
            $subtotal += $item['quantity'] * $item['unit_price'];
        }

        $order = PurchaseOrder::create([
            'supplier_id'   => $validated['supplier_id'],
            'po_number'     => $validated['po_number'],
            'order_date'    => $validated['order_date'],
            'expected_date' => $validated['expected_date'] ?? null,
            'subtotal'      => $subtotal,
            'tax_amount'    => 0,
            'total_amount'  => $subtotal,
            'status'        => 'pending',
            'notes'         => $validated['notes'] ?? null,
            'created_by'    => auth()->id(),
        ]);

        foreach ($validated['items'] as $item) {
            PurchaseOrderItem::create([
                'purchase_order_id' => $order->id,
                'inventory_item_id' => $item['item_id'],
                'quantity'          => $item['quantity'],
                'unit_price'        => $item['unit_price'],
                'total_price'       => $item['quantity'] * $item['unit_price'],
            ]);
        }

        return redirect()->route('admin.purchases.show', $order->id)->with('success', 'Purchase order created successfully.');
    }

    public function show($id)
    {
        $order = PurchaseOrder::with(['supplier', 'items.inventoryItem', 'returns'])->findOrFail($id);
        return view('admin.purchases.show', compact('order'));
    }

    public function approve($id)
    {
        $order = PurchaseOrder::findOrFail($id);
        $order->update(['status' => 'approved']);

        return redirect()->route('admin.purchases.show', $id)->with('success', 'Purchase order approved.');
    }

    public function receive($id, Request $request)
    {
        $order = PurchaseOrder::findOrFail($id);

        $validated = $request->validate([
            'received_items'              => 'required|array',
            'received_items.*.item_id'    => 'required|exists:inventory_items,id',
            'received_items.*.quantity'   => 'required|integer|min:1',
        ]);

        foreach ($validated['received_items'] as $received) {
            $orderItem = $order->items()->where('inventory_item_id', $received['item_id'])->first();
            if ($orderItem) {
                $orderItem->update(['received_quantity' => $orderItem->received_quantity + $received['quantity']]);
                InventoryItem::where('id', $received['item_id'])->increment('stock_quantity', $received['quantity']);
            }
        }

        $allReceived = $order->items->every(function ($item) {
            return $item->received_quantity >= $item->quantity;
        });

        $order->update(['status' => $allReceived ? 'received' : 'partial']);

        return redirect()->route('admin.purchases.show', $id)->with('success', 'Items received successfully.');
    }

    public function cancel($id)
    {
        $order = PurchaseOrder::findOrFail($id);
        $order->update(['status' => 'cancelled']);

        return redirect()->route('admin.purchases.show', $id)->with('success', 'Purchase order cancelled.');
    }

    public function returns()
    {
        $returns = PurchaseReturn::with('purchaseOrder', 'inventoryItem')->latest()->paginate(20);
        return view('admin.purchases.returns', compact('returns'));
    }

    public function returnCreate()
    {
        $orders = PurchaseOrder::whereIn('status', ['received', 'partial'])->orderBy('po_number')->get();
        $items = InventoryItem::where('status', 'active')->orderBy('name')->get();
        return view('admin.purchases.return-create', compact('orders', 'items'));
    }

    public function returnStore(Request $request)
    {
        $validated = $request->validate([
            'purchase_order_id' => 'required|exists:purchase_orders,id',
            'inventory_item_id' => 'required|exists:inventory_items,id',
            'quantity'          => 'required|integer|min:1',
            'reason'            => 'required|string|max:255',
            'notes'             => 'nullable|string',
        ]);

        PurchaseReturn::create([
            'purchase_order_id' => $validated['purchase_order_id'],
            'inventory_item_id' => $validated['inventory_item_id'],
            'quantity'          => $validated['quantity'],
            'reason'            => $validated['reason'],
            'notes'             => $validated['notes'] ?? null,
            'status'            => 'pending',
            'created_by'        => auth()->id(),
        ]);

        InventoryItem::where('id', $validated['inventory_item_id'])->decrement('stock_quantity', $validated['quantity']);

        return redirect()->route('admin.purchases.returns')->with('success', 'Purchase return created successfully.');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InventoryItem;
use App\Models\InventoryCategory;
use App\Models\StockMovement;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        $query = InventoryItem::with('category');

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }
        if ($request->filled('low_stock')) {
            $query->whereColumn('stock_quantity', '<=', 'minimum_stock');
        }
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('sku', 'like', '%' . $request->search . '%')
                  ->orWhere('barcode', 'like', '%' . $request->search . '%');
            });
        }

        $items = $query->latest()->paginate(20);
        $categories = InventoryCategory::orderBy('name')->get();

        return view('admin.inventory.index', compact('items', 'categories'));
    }

    public function create()
    {
        $categories = InventoryCategory::orderBy('name')->get();
        $warehouses = Warehouse::orderBy('name')->get();
        return view('admin.inventory.create', compact('categories', 'warehouses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id'    => 'required|exists:inventory_categories,id',
            'name'           => 'required|string|max:255',
            'sku'            => 'required|string|unique:inventory_items,sku',
            'description'    => 'nullable|string',
            'unit'           => 'required|string|max:50',
            'cost_price'     => 'required|numeric|min:0',
            'selling_price'  => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'minimum_stock'  => 'required|integer|min:0',
            'maximum_stock'  => 'nullable|integer|min:0',
            'warehouse_id'   => 'nullable|exists:warehouses,id',
            'barcode'        => 'nullable|string|unique:inventory_items,barcode',
            'status'         => 'required|in:active,inactive',
        ]);

        InventoryItem::create($validated);

        return redirect()->route('admin.inventory.index')->with('success', 'Inventory item created successfully.');
    }

    public function show($id)
    {
        $item = InventoryItem::with('category', 'warehouse', 'stockMovements')->findOrFail($id);
        $stockMovements = $item->stockMovements()->latest()->paginate(20);
        return view('admin.inventory.show', compact('item', 'stockMovements'));
    }

    public function edit($id)
    {
        $item = InventoryItem::findOrFail($id);
        $categories = InventoryCategory::orderBy('name')->get();
        $warehouses = Warehouse::orderBy('name')->get();
        return view('admin.inventory.edit', compact('item', 'categories', 'warehouses'));
    }

    public function update(Request $request, $id)
    {
        $item = InventoryItem::findOrFail($id);

        $validated = $request->validate([
            'category_id'    => 'required|exists:inventory_categories,id',
            'name'           => 'required|string|max:255',
            'sku'            => 'required|string|unique:inventory_items,sku,' . $id,
            'description'    => 'nullable|string',
            'unit'           => 'required|string|max:50',
            'cost_price'     => 'required|numeric|min:0',
            'selling_price'  => 'required|numeric|min:0',
            'minimum_stock'  => 'required|integer|min:0',
            'maximum_stock'  => 'nullable|integer|min:0',
            'warehouse_id'   => 'nullable|exists:warehouses,id',
            'barcode'        => 'nullable|string|unique:inventory_items,barcode,' . $id,
            'status'         => 'required|in:active,inactive',
        ]);

        $item->update($validated);

        return redirect()->route('admin.inventory.show', $id)->with('success', 'Inventory item updated successfully.');
    }

    public function destroy($id)
    {
        $item = InventoryItem::findOrFail($id);
        $item->delete();

        return redirect()->route('admin.inventory.index')->with('success', 'Inventory item deleted successfully.');
    }

    public function categories()
    {
        $categories = InventoryCategory::with('parent', 'children')->whereNull('parent_id')->latest()->get();
        return view('admin.inventory.categories', compact('categories'));
    }

    public function categoryCreate()
    {
        $parentCategories = InventoryCategory::whereNull('parent_id')->orderBy('name')->get();
        return view('admin.inventory.category-form', ['category' => null, 'parentCategories' => $parentCategories]);
    }

    public function categoryStore(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'parent_id'   => 'nullable|exists:inventory_categories,id',
            'status'      => 'required|in:active,inactive',
        ]);

        InventoryCategory::create($validated);

        return redirect()->route('admin.inventory.categories')->with('success', 'Category created successfully.');
    }

    public function categoryEdit($id)
    {
        $category = InventoryCategory::findOrFail($id);
        $parentCategories = InventoryCategory::whereNull('parent_id')->where('id', '!=', $id)->orderBy('name')->get();
        return view('admin.inventory.category-form', compact('category', 'parentCategories'));
    }

    public function categoryUpdate(Request $request, $id)
    {
        $category = InventoryCategory::findOrFail($id);

        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'parent_id'   => 'nullable|exists:inventory_categories,id',
            'status'      => 'required|in:active,inactive',
        ]);

        $category->update($validated);

        return redirect()->route('admin.inventory.categories')->with('success', 'Category updated successfully.');
    }

    public function categoryDestroy($id)
    {
        $category = InventoryCategory::findOrFail($id);
        $category->delete();

        return redirect()->route('admin.inventory.categories')->with('success', 'Category deleted successfully.');
    }

    public function stockIn($id, Request $request)
    {
        $item = InventoryItem::findOrFail($id);

        $validated = $request->validate([
            'quantity'   => 'required|integer|min:1',
            'reference'  => 'nullable|string|max:255',
            'notes'      => 'nullable|string',
        ]);

        $item->increment('stock_quantity', $validated['quantity']);

        StockMovement::create([
            'inventory_item_id' => $id,
            'type'              => 'in',
            'quantity'          => $validated['quantity'],
            'reference'         => $validated['reference'] ?? null,
            'notes'             => $validated['notes'] ?? null,
            'performed_by'      => auth()->id(),
        ]);

        return redirect()->route('admin.inventory.show', $id)->with('success', 'Stock added successfully.');
    }

    public function stockOut($id, Request $request)
    {
        $item = InventoryItem::findOrFail($id);

        $validated = $request->validate([
            'quantity'  => 'required|integer|min:1',
            'reference' => 'nullable|string|max:255',
            'notes'     => 'nullable|string',
        ]);

        if ($item->stock_quantity < $validated['quantity']) {
            return back()->withErrors(['quantity' => 'Insufficient stock available.']);
        }

        $item->decrement('stock_quantity', $validated['quantity']);

        StockMovement::create([
            'inventory_item_id' => $id,
            'type'              => 'out',
            'quantity'          => $validated['quantity'],
            'reference'         => $validated['reference'] ?? null,
            'notes'             => $validated['notes'] ?? null,
            'performed_by'      => auth()->id(),
        ]);

        return redirect()->route('admin.inventory.show', $id)->with('success', 'Stock removed successfully.');
    }

    public function transfer(Request $request)
    {
        $validated = $request->validate([
            'from_item_id' => 'required|exists:inventory_items,id',
            'to_item_id'   => 'required|exists:inventory_items,id',
            'quantity'     => 'required|integer|min:1',
            'notes'        => 'nullable|string',
        ]);

        $fromItem = InventoryItem::findOrFail($validated['from_item_id']);
        $toItem = InventoryItem::findOrFail($validated['to_item_id']);

        if ($fromItem->stock_quantity < $validated['quantity']) {
            return back()->withErrors(['quantity' => 'Insufficient stock in source item.']);
        }

        $fromItem->decrement('stock_quantity', $validated['quantity']);
        $toItem->increment('stock_quantity', $validated['quantity']);

        StockMovement::create([
            'inventory_item_id' => $fromItem->id,
            'type'              => 'transfer_out',
            'quantity'          => $validated['quantity'],
            'reference'         => 'Transfer to ' . $toItem->name,
            'notes'             => $validated['notes'] ?? null,
            'performed_by'      => auth()->id(),
        ]);

        StockMovement::create([
            'inventory_item_id' => $toItem->id,
            'type'              => 'transfer_in',
            'quantity'          => $validated['quantity'],
            'reference'         => 'Transfer from ' . $fromItem->name,
            'notes'             => $validated['notes'] ?? null,
            'performed_by'      => auth()->id(),
        ]);

        return redirect()->route('admin.inventory.index')->with('success', 'Stock transferred successfully.');
    }

    public function adjust($id, Request $request)
    {
        $item = InventoryItem::findOrFail($id);

        $validated = $request->validate([
            'new_quantity' => 'required|integer|min:0',
            'reason'       => 'required|string|max:255',
            'notes'        => 'nullable|string',
        ]);

        $difference = $validated['new_quantity'] - $item->stock_quantity;

        $item->update(['stock_quantity' => $validated['new_quantity']]);

        StockMovement::create([
            'inventory_item_id' => $id,
            'type'              => 'adjustment',
            'quantity'          => abs($difference),
            'reference'         => $validated['reason'],
            'notes'             => $validated['notes'] ?? null,
            'performed_by'      => auth()->id(),
        ]);

        return redirect()->route('admin.inventory.show', $id)->with('success', 'Stock adjusted successfully.');
    }

    public function lowStock()
    {
        $items = InventoryItem::with('category')
            ->whereColumn('stock_quantity', '<=', 'minimum_stock')
            ->latest()
            ->paginate(20);

        return view('admin.inventory.low-stock', compact('items'));
    }

    public function reports()
    {
        $totalItems = InventoryItem::count();
        $totalValue = InventoryItem::sum(\DB::raw('stock_quantity * cost_price'));
        $lowStockCount = InventoryItem::whereColumn('stock_quantity', '<=', 'minimum_stock')->count();
        $categories = InventoryCategory::withCount('items')->get();

        return view('admin.inventory.reports', compact('totalItems', 'totalValue', 'lowStockCount', 'categories'));
    }
}

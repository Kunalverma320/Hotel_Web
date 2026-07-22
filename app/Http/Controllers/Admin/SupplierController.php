<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use App\Models\SupplierPayment;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index(Request $request)
    {
        $query = Supplier::query();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('contact_person', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $suppliers = $query->latest()->paginate(20);

        return view('admin.suppliers.index', compact('suppliers'));
    }

    public function create()
    {
        return view('admin.suppliers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'            => 'required|string|max:255',
            'contact_person'  => 'nullable|string|max:255',
            'email'           => 'nullable|email|max:255',
            'phone'           => 'nullable|string|max:50',
            'address'         => 'nullable|string',
            'city'            => 'nullable|string|max:100',
            'state'           => 'nullable|string|max:100',
            'zip_code'        => 'nullable|string|max:20',
            'country'         => 'nullable|string|max:100',
            'tax_number'      => 'nullable|string|max:50',
            'payment_terms'   => 'nullable|integer|min:0',
            'bank_name'       => 'nullable|string|max:255',
            'bank_account'    => 'nullable|string|max:255',
            'notes'           => 'nullable|string',
            'status'          => 'required|in:active,inactive',
        ]);

        Supplier::create($validated);

        return redirect()->route('admin.suppliers.index')->with('success', 'Supplier created successfully.');
    }

    public function show($id)
    {
        $supplier = Supplier::findOrFail($id);
        $payments = $supplier->payments()->latest()->paginate(20);
        $totalPaid = $supplier->payments()->sum('amount');
        $totalPurchases = $supplier->purchaseOrders()->sum('total_amount');
        $balance = $totalPurchases - $totalPaid;

        return view('admin.suppliers.show', compact('supplier', 'payments', 'totalPaid', 'totalPurchases', 'balance'));
    }

    public function edit($id)
    {
        $supplier = Supplier::findOrFail($id);
        return view('admin.suppliers.create', compact('supplier'));
    }

    public function update(Request $request, $id)
    {
        $supplier = Supplier::findOrFail($id);

        $validated = $request->validate([
            'name'            => 'required|string|max:255',
            'contact_person'  => 'nullable|string|max:255',
            'email'           => 'nullable|email|max:255',
            'phone'           => 'nullable|string|max:50',
            'address'         => 'nullable|string',
            'city'            => 'nullable|string|max:100',
            'state'           => 'nullable|string|max:100',
            'zip_code'        => 'nullable|string|max:20',
            'country'         => 'nullable|string|max:100',
            'tax_number'      => 'nullable|string|max:50',
            'payment_terms'   => 'nullable|integer|min:0',
            'bank_name'       => 'nullable|string|max:255',
            'bank_account'    => 'nullable|string|max:255',
            'notes'           => 'nullable|string',
            'status'          => 'required|in:active,inactive',
        ]);

        $supplier->update($validated);

        return redirect()->route('admin.suppliers.show', $id)->with('success', 'Supplier updated successfully.');
    }

    public function destroy($id)
    {
        $supplier = Supplier::findOrFail($id);
        $supplier->delete();

        return redirect()->route('admin.suppliers.index')->with('success', 'Supplier deleted successfully.');
    }

    public function payments($id)
    {
        $supplier = Supplier::findOrFail($id);
        $payments = $supplier->payments()->latest()->paginate(20);
        $totalPaid = $supplier->payments()->sum('amount');

        return view('admin.suppliers.payments', compact('supplier', 'payments', 'totalPaid'));
    }

    public function makePayment($id, Request $request)
    {
        $supplier = Supplier::findOrFail($id);

        $validated = $request->validate([
            'amount'         => 'required|numeric|min:0.01',
            'payment_date'   => 'required|date',
            'payment_method' => 'required|in:cash,bank_transfer,cheque,online',
            'reference'      => 'nullable|string|max:255',
            'notes'          => 'nullable|string',
        ]);

        SupplierPayment::create([
            'supplier_id'     => $id,
            'amount'          => $validated['amount'],
            'payment_date'    => $validated['payment_date'],
            'payment_method'  => $validated['payment_method'],
            'reference'       => $validated['reference'] ?? null,
            'notes'           => $validated['notes'] ?? null,
            'created_by'      => auth()->id(),
        ]);

        return redirect()->route('admin.suppliers.payments', $id)->with('success', 'Payment recorded successfully.');
    }

    public function ledger($id)
    {
        $supplier = Supplier::findOrFail($id);
        $purchases = $supplier->purchaseOrders()->orderBy('order_date')->get();
        $payments = $supplier->payments()->orderBy('payment_date')->get();

        $ledger = collect();
        $runningBalance = 0;

        foreach ($purchases as $purchase) {
            $runningBalance += $purchase->total_amount;
            $ledger->push([
                'date'        => $purchase->order_date,
                'type'        => 'Purchase',
                'reference'   => $purchase->po_number,
                'debit'       => $purchase->total_amount,
                'credit'      => 0,
                'balance'     => $runningBalance,
            ]);
        }

        foreach ($payments as $payment) {
            $runningBalance -= $payment->amount;
            $ledger->push([
                'date'        => $payment->payment_date,
                'type'        => 'Payment',
                'reference'   => $payment->reference ?? 'PAY-' . $payment->id,
                'debit'       => 0,
                'credit'      => $payment->amount,
                'balance'     => $runningBalance,
            ]);
        }

        $ledger = $ledger->sortBy('date')->values();

        return view('admin.suppliers.ledger', compact('supplier', 'ledger', 'runningBalance'));
    }

    public function updateStatus($id, $status)
    {
        $supplier = Supplier::findOrFail($id);

        if (!in_array($status, ['active', 'inactive'])) {
            return back()->with('error', 'Invalid status.');
        }

        $supplier->update(['status' => $status]);

        return redirect()->route('admin.suppliers.show', $id)->with('success', 'Supplier status updated.');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Company;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    public function index(Request $request)
    {
        $query = Branch::with('company');

        if ($request->filled('search')) {
            $query->where('name', 'like', "%{$request->search}%");
        }
        if ($request->filled('company_id')) {
            $query->where('company_id', $request->company_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $branches = $query->latest()->paginate(15);
        return view('admin.branches.index', compact('branches'));
    }

    public function create()
    {
        $companies = Company::orderBy('name')->pluck('name', 'id');
        return view('admin.branches.create', compact('companies'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'company_id' => 'required|exists:companies,id',
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:branches,code',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'zipcode' => 'nullable|string|max:20',
            'phone' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:255',
            'status' => 'required|in:active,inactive',
        ]);
        Branch::create($request->all());
        return redirect()->route('admin.branches.index')->with('success', 'Branch created successfully.');
    }

    public function show($id)
    {
        $branch = Branch::with('company')->findOrFail($id);
        return view('admin.branches.show', compact('branch'));
    }

    public function edit($id)
    {
        $branch = Branch::findOrFail($id);
        $companies = Company::orderBy('name')->pluck('name', 'id');
        return view('admin.branches.edit', compact('branch', 'companies'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'company_id' => 'required|exists:companies,id',
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:branches,code,' . $id,
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'zipcode' => 'nullable|string|max:20',
            'phone' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:255',
            'status' => 'required|in:active,inactive',
        ]);
        Branch::findOrFail($id)->update($request->all());
        return redirect()->route('admin.branches.index')->with('success', 'Branch updated successfully.');
    }

    public function destroy($id)
    {
        Branch::findOrFail($id)->delete();
        return redirect()->route('admin.branches.index')->with('success', 'Branch deleted successfully.');
    }

    public function updateStatus($id, $status)
    {
        if (!in_array($status, ['active', 'inactive'])) {
            return redirect()->back()->with('error', 'Invalid status.');
        }
        Branch::findOrFail($id)->update(['status' => $status]);
        return redirect()->back()->with('success', 'Branch status updated successfully.');
    }
}

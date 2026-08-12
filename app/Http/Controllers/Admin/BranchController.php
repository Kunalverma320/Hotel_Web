<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BranchController extends Controller
{
    public function index(Request $request)
    {
        $query = Branch::with('company');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%");
            });
        }
        if ($request->filled('company_id')) {
            $query->where('company_id', $request->company_id);
        }
        if ($request->filled('status')) {
            $statusVal = in_array($request->status, ['active', '1', 1, true], true) ? 1 : 0;
            $query->where('status', $statusVal);
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
            'status' => 'required',
        ]);

        $data = $request->only([
            'company_id', 'name', 'code', 'slug', 'address', 'city', 'state',
            'country', 'zipcode', 'phone', 'email', 'contact_person', 'branch_manager_id'
        ]);

        if ($request->filled('manager_id') && empty($data['branch_manager_id'])) {
            $data['branch_manager_id'] = $request->manager_id;
        }

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($request->name) . '-' . time();
        }

        $data['status'] = in_array($request->status, ['active', '1', 1, true], true) ? 1 : 0;

        Branch::create($data);
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
        $branch = Branch::findOrFail($id);

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
            'status' => 'required',
        ]);

        $data = $request->only([
            'company_id', 'name', 'code', 'slug', 'address', 'city', 'state',
            'country', 'zipcode', 'phone', 'email', 'contact_person', 'branch_manager_id'
        ]);

        if ($request->filled('manager_id') && empty($data['branch_manager_id'])) {
            $data['branch_manager_id'] = $request->manager_id;
        }

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($request->name);
        }

        $data['status'] = in_array($request->status, ['active', '1', 1, true], true) ? 1 : 0;

        $branch->update($data);
        return redirect()->route('admin.branches.index')->with('success', 'Branch updated successfully.');
    }

    public function destroy($id)
    {
        Branch::findOrFail($id)->delete();
        return redirect()->route('admin.branches.index')->with('success', 'Branch deleted successfully.');
    }

    public function updateStatus($id, $status)
    {
        if (!in_array($status, ['active', 'inactive', '1', '0'])) {
            return redirect()->back()->with('error', 'Invalid status.');
        }
        $statusVal = in_array($status, ['active', '1'], true) ? 1 : 0;
        Branch::findOrFail($id)->update(['status' => $statusVal]);
        return redirect()->back()->with('success', 'Branch status updated successfully.');
    }
}

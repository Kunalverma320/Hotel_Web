<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Designation;
use Illuminate\Http\Request;

class DesignationController extends Controller
{
    public function index(Request $request)
    {
        $query = Designation::withCount('employees');

        if ($request->filled('search')) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        $designations = $query->orderBy('name')->paginate(15);

        return view('admin.designations.index', compact('designations'));
    }

    public function create()
    {
        return view('admin.designations.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:designations,name',
            'description' => 'nullable|string',
            'status' => 'nullable|in:active,inactive',
        ]);

        $validated['status'] = $validated['status'] ?? 'active';

        Designation::create($validated);

        return redirect()->route('admin.designations.index')->with('success', 'Designation created successfully.');
    }

    public function edit($id)
    {
        $designation = Designation::findOrFail($id);
        return view('admin.designations.edit', compact('designation'));
    }

    public function update(Request $request, $id)
    {
        $designation = Designation::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:designations,name,' . $id,
            'description' => 'nullable|string',
            'status' => 'nullable|in:active,inactive',
        ]);

        $designation->update($validated);

        return redirect()->route('admin.designations.index')->with('success', 'Designation updated successfully.');
    }

    public function destroy($id)
    {
        $designation = Designation::findOrFail($id);

        if ($designation->employees()->count() > 0) {
            return redirect()->route('admin.designations.index')->with('error', 'Cannot delete designation with assigned employees.');
        }

        $designation->delete();

        return redirect()->route('admin.designations.index')->with('success', 'Designation deleted successfully.');
    }
}

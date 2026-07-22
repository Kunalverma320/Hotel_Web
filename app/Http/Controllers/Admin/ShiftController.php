<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Shift;
use App\Models\Employee;
use Illuminate\Http\Request;

class ShiftController extends Controller
{
    public function index(Request $request)
    {
        $shifts = Shift::withCount('employees')->latest()->paginate(15);
        $employees = Employee::where('status', 'active')->orderBy('first_name')->get();

        return view('admin.shifts.index', compact('shifts', 'employees'));
    }

    public function create()
    {
        return view('admin.shifts.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'description' => 'nullable|string|max:500',
            'status' => 'nullable|in:active,inactive',
        ]);

        $validated['status'] = $validated['status'] ?? 'active';

        Shift::create($validated);

        return redirect()->route('admin.shifts.index')->with('success', 'Shift created successfully.');
    }

    public function edit($id)
    {
        $shift = Shift::findOrFail($id);
        return view('admin.shifts.edit', compact('shift'));
    }

    public function update(Request $request, $id)
    {
        $shift = Shift::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:shifts,name,' . $id,
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'description' => 'nullable|string|max:500',
            'status' => 'nullable|in:active,inactive',
        ]);

        $shift->update($validated);

        return redirect()->route('admin.shifts.index')->with('success', 'Shift updated successfully.');
    }

    public function destroy($id)
    {
        $shift = Shift::findOrFail($id);

        if ($shift->employees()->count() > 0) {
            return redirect()->route('admin.shifts.index')->with('error', 'Cannot delete shift with assigned employees.');
        }

        $shift->delete();

        return redirect()->route('admin.shifts.index')->with('success', 'Shift deleted successfully.');
    }

    public function assignShift(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'shift_id' => 'required|exists:shifts,id',
        ]);

        $employee = Employee::findOrFail($validated['employee_id']);
        $employee->update(['shift_id' => $validated['shift_id']]);

        return redirect()->route('admin.shifts.index')->with('success', 'Shift assigned successfully.');
    }
}

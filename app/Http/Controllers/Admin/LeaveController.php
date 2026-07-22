<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Leave;
use App\Models\Employee;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LeaveController extends Controller
{
    public function index(Request $request)
    {
        $query = Leave::with('employee');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }

        $leaves = $query->latest()->paginate(15);
        $employees = Employee::where('status', 'active')->orderBy('first_name')->get();

        return view('admin.leaves.index', compact('leaves', 'employees'));
    }

    public function create()
    {
        $employees = Employee::where('status', 'active')->orderBy('first_name')->get();
        return view('admin.leaves.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'leave_type' => 'required|in:sick,casual,annual,maternity,paternity,unpaid,other',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|max:1000',
        ]);

        $start = Carbon::parse($validated['start_date']);
        $end = Carbon::parse($validated['end_date']);
        $validated['days'] = $start->diffInDays($end) + 1;
        $validated['status'] = 'pending';

        Leave::create($validated);

        return redirect()->route('admin.leaves.index')->with('success', 'Leave application submitted successfully.');
    }

    public function approve($id)
    {
        $leave = Leave::findOrFail($id);
        $leave->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => Carbon::now(),
        ]);

        return redirect()->back()->with('success', 'Leave approved successfully.');
    }

    public function reject($id)
    {
        $leave = Leave::findOrFail($id);
        $leave->update([
            'status' => 'rejected',
            'approved_by' => auth()->id(),
            'approved_at' => Carbon::now(),
        ]);

        return redirect()->back()->with('success', 'Leave rejected.');
    }

    public function balance($employeeId)
    {
        $employee = Employee::findOrFail($employeeId);

        $year = Carbon::now()->year;
        $leaves = Leave::where('employee_id', $employeeId)
            ->whereYear('start_date', $year)
            ->where('status', 'approved')
            ->get();

        $balance = [
            'annual' => ['total' => 15, 'used' => 0],
            'sick' => ['total' => 10, 'used' => 0],
            'casual' => ['total' => 10, 'used' => 0],
        ];

        foreach ($leaves as $leave) {
            if (isset($balance[$leave->leave_type])) {
                $balance[$leave->leave_type]['used'] += $leave->days;
            }
        }

        foreach ($balance as &$type) {
            $type['remaining'] = $type['total'] - $type['used'];
        }

        return response()->json(['employee' => $employee, 'balance' => $balance]);
    }

    public function calendar(Request $request)
    {
        $year = $request->year ?? Carbon::now()->year;
        $month = $request->month ?? Carbon::now()->month;

        $leaves = Leave::with('employee')
            ->whereMonth('start_date', $month)
            ->whereYear('start_date', $year)
            ->orWhereMonth('end_date', $month)
            ->whereYear('end_date', $year)
            ->get();

        return view('admin.leaves.calendar', compact('leaves', 'year', 'month'));
    }
}

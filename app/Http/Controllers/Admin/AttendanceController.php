<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Employee;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $query = Attendance::with('employee');

        if ($request->filled('date')) {
            $query->whereDate('date', $request->date);
        } else {
            $query->whereDate('date', Carbon::today());
        }

        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }

        $attendances = $query->latest('date')->paginate(25);
        $employees = Employee::where('status', 'active')->orderBy('first_name')->get();
        $today = Carbon::today()->format('Y-m-d');

        return view('admin.attendance.index', compact('attendances', 'employees', 'today'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'type' => 'required|in:clock_in,clock_out',
        ]);

        $today = Carbon::today()->toDateString();
        $now = Carbon::now();

        $attendance = Attendance::where('employee_id', $validated['employee_id'])
            ->whereDate('date', $today)
            ->first();

        if ($validated['type'] === 'clock_in') {
            if ($attendance && $attendance->clock_in) {
                return redirect()->back()->with('error', 'Employee has already clocked in today.');
            }

            if ($attendance) {
                $attendance->update(['clock_in' => $now]);
            } else {
                Attendance::create([
                    'employee_id' => $validated['employee_id'],
                    'date' => $today,
                    'clock_in' => $now,
                    'status' => 'present',
                ]);
            }

            return redirect()->back()->with('success', 'Clock in recorded successfully.');
        } else {
            if (!$attendance || !$attendance->clock_in) {
                return redirect()->back()->with('error', 'Employee has not clocked in today.');
            }

            if ($attendance->clock_out) {
                return redirect()->back()->with('error', 'Employee has already clocked out today.');
            }

            $clockIn = Carbon::parse($attendance->clock_in);
            $hoursWorked = $clockIn->diffInHours($now) + ($clockIn->diffInMinutes($now) % 60) / 60;

            $attendance->update([
                'clock_out' => $now,
                'hours_worked' => round($hoursWorked, 2),
            ]);

            return redirect()->back()->with('success', 'Clock out recorded successfully. Hours worked: ' . number_format($hoursWorked, 2));
        }
    }

    public function markAttendance(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'attendances' => 'required|array',
            'attendances.*.employee_id' => 'required|exists:employees,id',
            'attendances.*.status' => 'required|in:present,absent,half_day,late,leave,week_off,holiday',
        ]);

        foreach ($validated['attendances'] as $record) {
            Attendance::updateOrCreate(
                [
                    'employee_id' => $record['employee_id'],
                    'date' => $validated['date'],
                ],
                [
                    'status' => $record['status'],
                ]
            );
        }

        return redirect()->route('admin.attendance.index')->with('success', 'Attendance marked successfully.');
    }

    public function report(Request $request)
    {
        $startDate = $request->start_date ?? Carbon::now()->startOfMonth()->format('Y-m-d');
        $endDate = $request->end_date ?? Carbon::now()->endOfMonth()->format('Y-m-d');

        $query = Attendance::with('employee')
            ->whereBetween('date', [$startDate, $endDate]);

        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }

        $attendances = $query->orderBy('date', 'desc')->paginate(25);

        $summary = Attendance::whereBetween('date', [$startDate, $endDate])
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        $employees = Employee::where('status', 'active')->orderBy('first_name')->get();

        return view('admin.attendance.report', compact('attendances', 'summary', 'employees', 'startDate', 'endDate'));
    }

    public function employeeReport($employeeId)
    {
        $employee = Employee::findOrFail($employeeId);

        $attendances = Attendance::where('employee_id', $employeeId)
            ->whereMonth('date', Carbon::now()->month)
            ->whereYear('date', Carbon::now()->year)
            ->orderBy('date', 'desc')
            ->get();

        $summary = $attendances->groupBy('status')->map(fn($items) => $items->count());

        return view('admin.attendance.employee_report', compact('employee', 'attendances', 'summary'));
    }
}

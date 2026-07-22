<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payroll;
use App\Models\Employee;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PayrollController extends Controller
{
    public function index(Request $request)
    {
        $query = Payroll::with('employee');

        if ($request->filled('month') && $request->filled('year')) {
            $query->where('month', $request->month)->where('year', $request->year);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $payrolls = $query->latest('year')->latest('month')->paginate(20);
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        return view('admin.payroll.index', compact('payrolls', 'currentMonth', 'currentYear'));
    }

    public function generate(Request $request)
    {
        $validated = $request->validate([
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2020|max:2030',
            'employee_ids' => 'nullable|array',
            'employee_ids.*' => 'exists:employees,id',
        ]);

        $employees = Employee::where('status', 'active');

        if (!empty($validated['employee_ids'])) {
            $employees->whereIn('id', $validated['employee_ids']);
        }

        $employees = $employees->get();
        $generated = 0;

        foreach ($employees as $employee) {
            $exists = Payroll::where('employee_id', $employee->id)
                ->where('month', $validated['month'])
                ->where('year', $validated['year'])
                ->exists();

            if (!$exists) {
                $basicSalary = $employee->salary;
                $deductions = 0;
                $bonuses = 0;
                $netSalary = $basicSalary + $bonuses - $deductions;

                Payroll::create([
                    'employee_id' => $employee->id,
                    'month' => $validated['month'],
                    'year' => $validated['year'],
                    'basic_salary' => $basicSalary,
                    'deductions' => $deductions,
                    'bonuses' => $bonuses,
                    'net_salary' => $netSalary,
                    'status' => 'pending',
                ]);
                $generated++;
            }
        }

        return redirect()->route('admin.payroll.index')->with('success', "Payroll generated for {$generated} employees.");
    }

    public function show($id)
    {
        $payroll = Payroll::with('employee')->findOrFail($id);
        return view('admin.payroll.show', compact('payroll'));
    }

    public function markPaid($id)
    {
        $payroll = Payroll::findOrFail($id);
        $payroll->update([
            'status' => 'paid',
            'paid_at' => Carbon::now(),
        ]);

        return redirect()->back()->with('success', 'Payroll marked as paid.');
    }

    public function bulkGenerate(Request $request)
    {
        $validated = $request->validate([
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2020|max:2030',
        ]);

        $employees = Employee::where('status', 'active')->get();
        $generated = 0;

        foreach ($employees as $employee) {
            $exists = Payroll::where('employee_id', $employee->id)
                ->where('month', $validated['month'])
                ->where('year', $validated['year'])
                ->exists();

            if (!$exists) {
                $basicSalary = $employee->salary;
                $deductions = 0;
                $bonuses = 0;
                $netSalary = $basicSalary + $bonuses - $deductions;

                Payroll::create([
                    'employee_id' => $employee->id,
                    'month' => $validated['month'],
                    'year' => $validated['year'],
                    'basic_salary' => $basicSalary,
                    'deductions' => $deductions,
                    'bonuses' => $bonuses,
                    'net_salary' => $netSalary,
                    'status' => 'pending',
                ]);
                $generated++;
            }
        }

        return redirect()->route('admin.payroll.index')->with('success', "Bulk payroll generated for {$generated} employees.");
    }

    public function payslip($id)
    {
        $payroll = Payroll::with('employee')->findOrFail($id);
        return view('admin.payroll.payslip', compact('payroll'));
    }
}

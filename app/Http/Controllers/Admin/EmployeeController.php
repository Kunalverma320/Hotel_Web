<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Attendance;
use App\Models\Payroll;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $query = Employee::with(['department', 'designation']);

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }
        if ($request->filled('designation_id')) {
            $query->where('designation_id', $request->designation_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('employee_code', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $employees = $query->latest()->paginate(15);
        $departments = Department::orderBy('name')->get();
        $designations = Designation::orderBy('name')->get();

        return view('admin.employees.index', compact('employees', 'departments', 'designations'));
    }

    public function create()
    {
        $departments = Department::orderBy('name')->get();
        $designations = Designation::orderBy('name')->get();
        return view('admin.employees.create', compact('departments', 'designations'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email',
            'phone' => 'required|string|max:20',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:male,female,other',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'zip_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
            'department_id' => 'required|exists:departments,id',
            'designation_id' => 'required|exists:designations,id',
            'date_of_joining' => 'required|date',
            'employment_type' => 'required|in:full-time,part-time,contract,intern',
            'salary' => 'required|numeric|min:0',
            'bank_name' => 'nullable|string|max:255',
            'bank_account_number' => 'nullable|string|max:50',
            'bank_ifsc' => 'nullable|string|max:20',
            'photo' => 'nullable|image|max:2048',
            'documents.*' => 'nullable|file|max:5120',
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('employees/photos', 'public');
        }

        $validated['employee_code'] = 'EMP' . str_pad(Employee::max('id') + 1, 5, '0', STR_PAD_LEFT);
        $validated['password'] = bcrypt($request->email);

        if ($request->hasFile('documents')) {
            $validated['documents'] = $request->file('documents')->store('employees/documents', 'public');
        }

        Employee::create($validated);

        return redirect()->route('admin.employees.index')->with('success', 'Employee created successfully.');
    }

    public function show($id)
    {
        $employee = Employee::with(['department', 'designation'])->findOrFail($id);
        $attendances = Attendance::where('employee_id', $id)->latest()->limit(30)->get();
        $payrolls = Payroll::where('employee_id', $id)->latest()->limit(12)->get();

        return view('admin.employees.show', compact('employee', 'attendances', 'payrolls'));
    }

    public function edit($id)
    {
        $employee = Employee::findOrFail($id);
        $departments = Department::orderBy('name')->get();
        $designations = Designation::orderBy('name')->get();

        return view('admin.employees.edit', compact('employee', 'departments', 'designations'));
    }

    public function update(Request $request, $id)
    {
        $employee = Employee::findOrFail($id);

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email,' . $id,
            'phone' => 'required|string|max:20',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:male,female,other',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'zip_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
            'department_id' => 'required|exists:departments,id',
            'designation_id' => 'required|exists:designations,id',
            'date_of_joining' => 'required|date',
            'employment_type' => 'required|in:full-time,part-time,contract,intern',
            'salary' => 'required|numeric|min:0',
            'bank_name' => 'nullable|string|max:255',
            'bank_account_number' => 'nullable|string|max:50',
            'bank_ifsc' => 'nullable|string|max:20',
            'photo' => 'nullable|image|max:2048',
            'status' => 'nullable|in:active,inactive,terminated',
        ]);

        if ($request->hasFile('photo')) {
            if ($employee->photo) {
                Storage::disk('public')->delete($employee->photo);
            }
            $validated['photo'] = $request->file('photo')->store('employees/photos', 'public');
        }

        $employee->update($validated);

        return redirect()->route('admin.employees.index')->with('success', 'Employee updated successfully.');
    }

    public function destroy($id)
    {
        $employee = Employee::findOrFail($id);
        if ($employee->photo) {
            Storage::disk('public')->delete($employee->photo);
        }
        $employee->delete();

        return redirect()->route('admin.employees.index')->with('success', 'Employee deleted successfully.');
    }

    public function updateStatus($id, $status)
    {
        $employee = Employee::findOrFail($id);
        $employee->update(['status' => $status]);

        return response()->json(['success' => true, 'message' => 'Status updated successfully.']);
    }

    public function attendance($id)
    {
        $employee = Employee::findOrFail($id);
        $attendances = Attendance::where('employee_id', $id)
            ->latest()
            ->paginate(20);

        return view('admin.employees.attendance', compact('employee', 'attendances'));
    }

    public function payroll($id)
    {
        $employee = Employee::findOrFail($id);
        $payrolls = Payroll::where('employee_id', $id)
            ->latest()
            ->paginate(20);

        return view('admin.employees.payroll', compact('employee', 'payrolls'));
    }

    public function profile($id)
    {
        $employee = Employee::with(['department', 'designation'])->findOrFail($id);
        $attendances = Attendance::where('employee_id', $id)->latest()->limit(10)->get();
        $payrolls = Payroll::where('employee_id', $id)->latest()->limit(5)->get();

        return view('admin.employees.show', compact('employee', 'attendances', 'payrolls'));
    }
}

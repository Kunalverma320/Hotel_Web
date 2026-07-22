<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MaintenanceController extends Controller
{
    public function index(Request $request)
    {
        $query = MaintenanceRequest::with('assignee');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $requests = $query->latest()->paginate(20);
        $technicians = Staff::where('department', 'maintenance')->get();

        return view('admin.maintenance.index', compact('requests', 'technicians'));
    }

    public function create()
    {
        return view('admin.maintenance.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string',
            'priority' => 'required|in:low,medium,high,critical',
            'location' => 'required|string',
            'room_number' => 'nullable|string',
        ]);

        MaintenanceRequest::create($request->only(
            'title', 'description', 'category', 'priority', 'location', 'room_number'
        ));

        return redirect()->route('admin.maintenance.index')->with('success', 'Maintenance request created.');
    }

    public function show($id)
    {
        $request = MaintenanceRequest::with('assignee', 'timeline')->findOrFail($id);
        $technicians = Staff::where('department', 'maintenance')->get();

        return view('admin.maintenance.show', compact('request', 'technicians'));
    }

    public function edit($id)
    {
        $request = MaintenanceRequest::findOrFail($id);
        return view('admin.maintenance.create', compact('request'));
    }

    public function update($id, Request $request)
    {
        $maintenance = MaintenanceRequest::findOrFail($id);
        $maintenance->update($request->only(
            'title', 'description', 'category', 'priority', 'location', 'room_number'
        ));

        return redirect()->route('admin.maintenance.index')->with('success', 'Request updated.');
    }

    public function assign($id, Request $request)
    {
        $request->validate([
            'assigned_to' => 'required|exists:users,id',
        ]);

        $maintenance = MaintenanceRequest::findOrFail($id);
        $maintenance->update([
            'assigned_to' => $request->assigned_to,
            'status' => 'assigned',
        ]);

        return redirect()->back()->with('success', 'Technician assigned successfully.');
    }

    public function updateStatus($id, $status)
    {
        $maintenance = MaintenanceRequest::findOrFail($id);
        $maintenance->update([
            'status' => $status,
            'completed_at' => $status === 'completed' ? now() : null,
        ]);

        return redirect()->back()->with('success', 'Status updated successfully.');
    }

    public function reports()
    {
        $totalRequests = MaintenanceRequest::count();
        $completedRequests = MaintenanceRequest::where('status', 'completed')->count();
        $pendingRequests = MaintenanceRequest::where('status', 'pending')->count();
        $requestsByPriority = MaintenanceRequest::selectRaw('priority, count(*) as total')->groupBy('priority')->get();
        $requestsByCategory = MaintenanceRequest::selectRaw('category, count(*) as total')->groupBy('category')->get();
        $avgResolutionTime = MaintenanceRequest::where('status', 'completed')
            ->selectRaw('AVG(TIMESTAMPDIFF(HOUR, created_at, completed_at)) as avg_hours')
            ->value('avg_hours');

        return view('admin.maintenance.reports', compact(
            'totalRequests', 'completedRequests', 'pendingRequests',
            'requestsByPriority', 'requestsByCategory', 'avgResolutionTime'
        ));
    }
}

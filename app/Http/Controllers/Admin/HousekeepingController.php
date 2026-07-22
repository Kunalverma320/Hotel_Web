<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HousekeepingTask;
use App\Models\User;
use Illuminate\Http\Request;

class HousekeepingController extends Controller
{
    public function index(Request $request)
    {
        $query = HousekeepingTask::with('room', 'assignee');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('date')) {
            $query->whereDate('scheduled_date', $request->date);
        }
        if ($request->filled('assigned_to')) {
            $query->where('assigned_to', $request->assigned_to);
        }

        $tasks = $query->latest()->paginate(20);
        $staff = User::whereHas('employee', function ($q) {
            $q->where('department_id', 'housekeeping');
        })->get();

        return view('admin.housekeeping.index', compact('tasks', 'staff'));
    }

    public function assign(Request $request)
    {
        $request->validate([
            'task_id' => 'required|exists:housekeeping_tasks,id',
            'assigned_to' => 'required|exists:users,id',
        ]);

        $task = HousekeepingTask::findOrFail($request->task_id);
        $task->update([
            'assigned_to' => $request->assigned_to,
            'status' => 'assigned',
        ]);

        return redirect()->back()->with('success', 'Housekeeper assigned successfully.');
    }

    public function updateStatus($id, $status)
    {
        $task = HousekeepingTask::findOrFail($id);
        $task->update([
            'status' => $status,
            'completed_at' => $status === 'completed' ? now() : null,
        ]);

        return redirect()->back()->with('success', 'Status updated successfully.');
    }

    public function schedule()
    {
        $tasks = HousekeepingTask::with('room', 'assignee')
            ->whereBetween('scheduled_date', [now()->startOfWeek(), now()->endOfWeek()])
            ->get();

        return view('admin.housekeeping.schedule', compact('tasks'));
    }

    public function updateSchedule(Request $request)
    {
        $request->validate([
            'task_id' => 'required|exists:housekeeping_tasks,id',
            'scheduled_date' => 'required|date',
            'scheduled_time' => 'required',
        ]);

        $task = HousekeepingTask::findOrFail($request->task_id);
        $task->update($request->only('scheduled_date', 'scheduled_time'));

        return redirect()->back()->with('success', 'Schedule updated successfully.');
    }

    public function reports()
    {
        $totalTasks = HousekeepingTask::count();
        $completedTasks = HousekeepingTask::where('status', 'completed')->count();
        $pendingTasks = HousekeepingTask::where('status', 'pending')->count();
        $inProgressTasks = HousekeepingTask::where('status', 'in_progress')->count();
        $tasksByType = HousekeepingTask::selectRaw('type, count(*) as total')->groupBy('type')->get();
        $dailyStats = HousekeepingTask::where('created_at', '>=', now()->subDays(30))
            ->selectRaw('DATE(created_at) as date, count(*) as total')
            ->groupBy('date')
            ->get();

        return view('admin.housekeeping.reports', compact(
            'totalTasks', 'completedTasks', 'pendingTasks', 'inProgressTasks', 'tasksByType', 'dailyStats'
        ));
    }
}

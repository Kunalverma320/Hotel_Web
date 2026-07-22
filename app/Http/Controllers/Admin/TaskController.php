<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\TaskComment;
use App\Models\Employee;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $query = Task::with(['assignee', 'creator']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }
        if ($request->filled('assigned_to')) {
            $query->where('assigned_to', $request->assigned_to);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $tasks = $query->latest()->paginate(20);
        $employees = Employee::where('status', 'active')->orderBy('first_name')->get();

        return view('admin.tasks.index', compact('tasks', 'employees'));
    }

    public function create()
    {
        $employees = Employee::where('status', 'active')->orderBy('first_name')->get();
        return view('admin.tasks.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'assigned_to' => 'nullable|exists:employees,id',
            'priority' => 'required|in:low,medium,high,urgent',
            'status' => 'nullable|in:todo,in_progress,done,cancelled',
            'due_date' => 'nullable|date|after_or_equal:today',
        ]);

        $validated['created_by'] = auth()->id();
        $validated['status'] = $validated['status'] ?? 'todo';

        Task::create($validated);

        return redirect()->route('admin.tasks.index')->with('success', 'Task created successfully.');
    }

    public function show($id)
    {
        $task = Task::with(['assignee', 'creator', 'comments.user'])->findOrFail($id);
        return view('admin.tasks.show', compact('task'));
    }

    public function edit($id)
    {
        $task = Task::findOrFail($id);
        $employees = Employee::where('status', 'active')->orderBy('first_name')->get();
        return view('admin.tasks.edit', compact('task', 'employees'));
    }

    public function update(Request $request, $id)
    {
        $task = Task::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'assigned_to' => 'nullable|exists:employees,id',
            'priority' => 'required|in:low,medium,high,urgent',
            'status' => 'required|in:todo,in_progress,done,cancelled',
            'due_date' => 'nullable|date',
        ]);

        $task->update($validated);

        return redirect()->route('admin.tasks.index')->with('success', 'Task updated successfully.');
    }

    public function destroy($id)
    {
        $task = Task::findOrFail($id);
        $task->delete();

        return redirect()->route('admin.tasks.index')->with('success', 'Task deleted successfully.');
    }

    public function updateStatus($id, $status)
    {
        $task = Task::findOrFail($id);
        $task->update(['status' => $status]);

        return response()->json(['success' => true, 'message' => 'Task status updated.']);
    }

    public function addComment(Request $request, $taskId)
    {
        $validated = $request->validate([
            'comment' => 'required|string|max:2000',
        ]);

        TaskComment::create([
            'task_id' => $taskId,
            'user_id' => auth()->id(),
            'comment' => $validated['comment'],
        ]);

        return redirect()->back()->with('success', 'Comment added.');
    }

    public function myTasks()
    {
        $tasks = Task::with(['assignee', 'creator'])
            ->where('assigned_to', auth()->id())
            ->latest()
            ->paginate(20);

        return view('admin.tasks.index', compact('tasks'));
    }

    public function board()
    {
        $tasks = Task::with(['assignee', 'creator'])->latest()->get();

        $todo = $tasks->where('status', 'todo');
        $inProgress = $tasks->where('status', 'in_progress');
        $done = $tasks->where('status', 'done');
        $cancelled = $tasks->where('status', 'cancelled');

        return view('admin.tasks.board', compact('todo', 'inProgress', 'done', 'cancelled'));
    }
}

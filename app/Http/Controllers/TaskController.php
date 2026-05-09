<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use App\Models\TaskActivity;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    // Show all tasks
    public function index(Request $request)
    {
        $query = Task::with(['project', 'assignedUser', 'creator']);

        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        $tasks    = $query->orderBy('due_date')->paginate(20);
        $projects = Project::orderBy('name')->get();

        return view('tasks.index', compact('tasks', 'projects'));
    }

    // Show create form
    public function create()
    {
        $projects = Project::whereIn('status', ['pending', 'in_progress'])->get();
        $users    = User::where('is_active', true)->get();
        return view('tasks.create', compact('projects', 'users'));
    }

    // Store task
    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id'       => ['required', 'exists:projects,id'],
            'title'            => ['required', 'string', 'max:255'],
            'description'      => ['nullable', 'string'],
            'status'           => ['required', 'in:pending,in_progress,on_hold,completed,cancelled'],
            'priority'         => ['required', 'in:low,medium,high'],
            'due_date'         => ['required', 'date'],
            'assigned_user_id' => ['nullable', 'exists:users,id'],
        ]);

        $validated['created_by'] = Auth::id();

        $task = Task::create($validated);

        // Log activity
        TaskActivity::create([
            'task_id'       => $task->id,
            'user_id'       => Auth::id(),
            'activity_type' => 'created',
            'description'   => 'Task "' . $task->title . '" was created.',
            'activity_date' => now(),
        ]);

        return redirect()->route('tasks.show', $task)
                         ->with('success', 'Task created successfully.');
    }

    // Show task
    public function show(Task $task)
    {
        $task->load(['project', 'assignedUser', 'creator', 'comments.user', 'activities.user']);
        return view('tasks.show', compact('task'));
    }

    // Show edit form
    public function edit(Task $task)
    {
        $projects = Project::whereIn('status', ['pending', 'in_progress'])->get();
        $users    = User::where('is_active', true)->get();
        return view('tasks.edit', compact('task', 'projects', 'users'));
    }

    // Update task
    public function update(Request $request, Task $task)
    {
        $validated = $request->validate([
            'project_id'       => ['required', 'exists:projects,id'],
            'title'            => ['required', 'string', 'max:255'],
            'description'      => ['nullable', 'string'],
            'status'           => ['required', 'in:pending,in_progress,on_hold,completed,cancelled'],
            'priority'         => ['required', 'in:low,medium,high'],
            'due_date'         => ['required', 'date'],
            'assigned_user_id' => ['nullable', 'exists:users,id'],
        ]);

        $oldStatus = $task->status;
        $task->update($validated);

        // Log status change
        if ($oldStatus !== $validated['status']) {
            TaskActivity::create([
                'task_id'       => $task->id,
                'user_id'       => Auth::id(),
                'activity_type' => 'status_changed',
                'description'   => 'Status changed from "' . $oldStatus . '" to "' . $validated['status'] . '".',
                'activity_date' => now(),
            ]);
        }

        return redirect()->route('tasks.show', $task)
                         ->with('success', 'Task updated successfully.');
    }

    // Update status only
    public function updateStatus(Request $request, Task $task)
    {
        $request->validate([
            'status' => ['required', 'in:pending,in_progress,on_hold,completed,cancelled'],
        ]);

        $oldStatus = $task->status;
        $task->update(['status' => $request->status]);

        TaskActivity::create([
            'task_id'       => $task->id,
            'user_id'       => Auth::id(),
            'activity_type' => 'status_changed',
            'description'   => 'Status changed from "' . $oldStatus . '" to "' . $request->status . '".',
            'activity_date' => now(),
        ]);

        return back()->with('success', 'Task status updated.');
    }

    // Delete task
    public function destroy(Task $task)
    {
        $task->delete();
        return redirect()->route('tasks.index')
                         ->with('success', 'Task deleted.');
    }
}
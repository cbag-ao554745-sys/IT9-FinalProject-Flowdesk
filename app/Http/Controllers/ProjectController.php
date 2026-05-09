<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    // Show all projects
    public function index(Request $request)
    {
        $query = Project::with(['manager', 'tasks']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        $projects = $query->latest()->paginate(12);

        return view('projects.index', compact('projects'));
    }

    // Show create form
    public function create()
    {
        $managers = User::whereIn('role', ['admin','project_manager'])
                        ->where('is_active', true)
                        ->get();
        return view('projects.create', compact('managers'));
    }

    // Store project
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'start_date'  => ['required', 'date'],
            'due_date'    => ['required', 'date', 'after_or_equal:start_date'],
            'status'      => ['required', 'in:pending,in_progress,on_hold,completed,cancelled'],
            'priority'    => ['required', 'in:low,medium,high'],
            'manager_id'  => ['required', 'exists:users,id'],
        ]);

        Project::create($validated);

        return redirect()->route('projects.index')
                         ->with('success', 'Project created successfully.');
    }

    // Show project
    public function show(Project $project)
    {
        $project->load(['manager', 'tasks.assignedUser']);
        $tasksByStatus = $project->tasks->groupBy('status');
        return view('projects.show', compact('project', 'tasksByStatus'));
    }

    // Show edit form
    public function edit(Project $project)
    {
        $managers = User::whereIn('role', ['admin','project_manager'])
                        ->where('is_active', true)
                        ->get();
        return view('projects.edit', compact('project', 'managers'));
    }

    // Update project
    public function update(Request $request, Project $project)
    {
        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'start_date'  => ['required', 'date'],
            'due_date'    => ['required', 'date', 'after_or_equal:start_date'],
            'status'      => ['required', 'in:pending,in_progress,on_hold,completed,cancelled'],
            'priority'    => ['required', 'in:low,medium,high'],
            'manager_id'  => ['required', 'exists:users,id'],
        ]);

        $project->update($validated);

        return redirect()->route('projects.show', $project)
                         ->with('success', 'Project updated successfully.');
    }

    // Delete project
    public function destroy(Project $project)
    {
        $project->delete();
        return redirect()->route('projects.index')
                         ->with('success', 'Project deleted.');
    }
}
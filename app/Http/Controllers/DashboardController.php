<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use App\Models\TaskActivity;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Stats
        $stats = [
            'total_projects'  => Project::count(),
            'active_tasks'    => Task::whereNotIn('status', ['completed', 'cancelled'])->count(),
            'completed_tasks' => Task::where('status', 'completed')->count(),
            'overdue_tasks'   => Task::overdue()->count(),
        ];

        // My tasks
        $myTasks = Task::with(['project'])
            ->forUser($user->id)
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->orderBy('due_date')
            ->take(5)
            ->get();

        // Projects
        $projects = Project::with('tasks')
            ->whereIn('status', ['in_progress', 'pending'])
            ->take(6)
            ->get();

        // Recent activity
        $recentActivities = TaskActivity::with(['user', 'task'])
            ->latest('activity_date')
            ->take(8)
            ->get();

        // Team workload
        $teamWorkload = User::where('is_active', true)
            ->withCount([
                'assignedTasks as total_tasks',
                'assignedTasks as in_progress_tasks' => fn ($q) =>
                    $q->where('status', 'in_progress'),
            ])
            ->orderByDesc('total_tasks')
            ->get();

        return view('dashboard', compact(
            'stats',
            'myTasks',
            'projects',
            'recentActivities',
            'teamWorkload',
        ));
    }
}
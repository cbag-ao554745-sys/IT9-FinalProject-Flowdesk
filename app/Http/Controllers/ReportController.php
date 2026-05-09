<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // KPI summary
        $summary = [
            'total_tasks'     => Task::count(),
            'completed_tasks' => Task::where('status', 'completed')->count(),
            'pending_tasks'   => Task::where('status', 'pending')->count(),
            'overdue_tasks'   => Task::overdue()->count(),
        ];

        // Task status breakdown
        $completionReport = Task::select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status');

        // Overdue tasks
        $overdueTasks = Task::with(['project', 'assignedUser'])
            ->overdue()
            ->orderBy('due_date')
            ->get();

        // Project progress
        $projectProgress = Project::with('tasks')
            ->whereIn('status', ['pending', 'in_progress'])
            ->get()
            ->map(function ($project) {
                return [
                    'project'   => $project,
                    'total'     => $project->tasks->count(),
                    'completed' => $project->tasks->where('status', 'completed')->count(),
                    'percent'   => $project->progressPercent(),
                ];
            });

        // User productivity
        $userProductivity = User::withCount([
            'assignedTasks as total_assigned',
            'assignedTasks as completed_tasks' => fn ($q) =>
                $q->where('status', 'completed'),
            'assignedTasks as overdue_tasks' => fn ($q) =>
                $q->overdue(),
        ])
        ->get()
        ->map(function ($u) {
            $rate = $u->total_assigned > 0
                ? round(($u->completed_tasks / $u->total_assigned) * 100)
                : 0;
            return array_merge($u->toArray(), ['on_time_rate' => $rate]);
        });

        return view('reports.index', compact(
            'summary',
            'completionReport',
            'overdueTasks',
            'projectProgress',
            'userProductivity',
        ));
    }
}
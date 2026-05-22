<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;   // ← This is the important import
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        $data = $this->getReportData();
        return view('reports.index', $data);
    }

    public function pdf()
    {
        $data = $this->getReportData();

        $pdf = Pdf::loadView('reports.pdf', $data);
        
        $pdf->setPaper('A4', 'portrait');
        
        return $pdf->download('FlowDesk_Report_' . now()->format('Y-m-d_His') . '.pdf');
    }

    private function getReportData()
    {
        $summary = [
            'total_tasks'     => Task::count(),
            'completed_tasks' => Task::where('status', 'completed')->count(),
            'pending_tasks'   => Task::where('status', 'pending')->count(),
            'overdue_tasks'   => Task::overdue()->count(),
        ];

        $completionReport = Task::select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status');

        $overdueTasks = Task::with(['project', 'assignedUser'])
            ->overdue()
            ->orderBy('due_date')
            ->get();

        $projectProgress = Project::with('tasks')
            ->whereIn('status', ['pending', 'in_progress'])
            ->get()
            ->map(function ($project) {
                return [
                    'project'   => $project,
                    'total'     => $project->tasks->count(),
                    'completed' => $project->tasks->where('status', 'completed')->count(),
                    'percent'   => $project->progressPercent() ?? 0,
                ];
            });

        $userProductivity = User::withCount([
            'assignedTasks as total_assigned',
            'assignedTasks as completed_tasks' => fn($q) => $q->where('status', 'completed'),
            'assignedTasks as overdue_tasks' => fn($q) => $q->overdue(),
        ])->get()
        ->map(function ($u) {
            $rate = $u->total_assigned > 0 
                ? round(($u->completed_tasks / $u->total_assigned) * 100) 
                : 0;
            return array_merge($u->toArray(), ['on_time_rate' => $rate]);
        });

        return compact('summary', 'completionReport', 'overdueTasks', 'projectProgress', 'userProductivity');
    }
}
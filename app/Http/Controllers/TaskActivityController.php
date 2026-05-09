<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskActivity;
use Illuminate\Http\Request;

class TaskActivityController extends Controller
{
    // Show activity log for a task
    public function index(Task $task)
    {
        $activities = TaskActivity::with('user')
            ->where('task_id', $task->id)
            ->orderByDesc('activity_date')
            ->paginate(20);

        return view('tasks.activities', compact('task', 'activities'));
    }
}
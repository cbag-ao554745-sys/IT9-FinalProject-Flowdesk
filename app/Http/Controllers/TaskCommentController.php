<?php

namespace App\Http\Controllers;

use App\Models\TaskActivity;
use App\Models\TaskComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskCommentController extends Controller
{
    // Store comment
    public function store(Request $request)
    {
        $validated = $request->validate([
            'task_id' => ['required', 'exists:tasks,id'],
            'comment' => ['required', 'string', 'max:2000'],
        ]);

        $validated['user_id'] = Auth::id();

        TaskComment::create($validated);

        // Log activity
        TaskActivity::create([
            'task_id'       => $validated['task_id'],
            'user_id'       => Auth::id(),
            'activity_type' => 'commented',
            'description'   => Auth::user()->name . ' added a comment.',
            'activity_date' => now(),
        ]);

        return redirect()->back()
                         ->with('success', 'Comment added.');
    }

    // Delete comment
    public function destroy(TaskComment $taskComment)
    {
        // Only comment author can delete
        if (Auth::id() !== $taskComment->user_id) {
            abort(403, 'Unauthorized action.');
        }

        $taskComment->delete();

        return redirect()->back()
                         ->with('success', 'Comment deleted.');
    }
}
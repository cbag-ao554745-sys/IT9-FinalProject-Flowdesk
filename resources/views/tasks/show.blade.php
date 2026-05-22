@extends('layouts.app')

@section('title', $task->title)
@section('page-title', 'Task Detail')

@section('content')

{{-- Header --}}
<div class="flex items-center justify-between mb-6">
    <div>
        <a href="{{ route('tasks.index') }}" class="text-sm text-gray-400 hover:text-white mb-1 inline-flex items-center gap-1">
            ← Back to Tasks
        </a>
        <h1 class="text-xl font-bold text-white mt-1">{{ $task->title }}</h1>
    </div>
    @if(auth()->user()->role !== 'team_member')
    <div class="flex gap-2">
        <a href="{{ route('tasks.edit', $task) }}"
           class="px-4 py-2 bg-indigo-600 text-white text-sm font-semibold rounded-lg hover:bg-indigo-700">
            Edit Task
        </a>
        <form action="{{ route('tasks.destroy', $task) }}" method="POST"
              onsubmit="return confirm('Delete this task?')">
            @csrf @method('DELETE')
            <button class="px-4 py-2 bg-red-600 text-white text-sm font-semibold rounded-lg hover:bg-red-700">
                Delete
            </button>
        </form>
    </div>
    @endif
</div>

{{-- Main Grid --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Left: Task Details --}}
    <div class="lg:col-span-2 space-y-6">

        {{-- Task Info Card --}}
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-6">
            <h2 class="text-sm font-semibold text-gray-400 uppercase tracking-wider mb-4">Task Details</h2>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-xs text-gray-500 mb-1">Project</p>
                    <p class="text-sm text-white font-medium">{{ $task->project->name ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 mb-1">Status</p>
                    <span class="text-xs font-medium px-2 py-1 rounded-full
                        {{ $task->status === 'completed' ? 'bg-green-900 text-green-300' :
                           ($task->status === 'in_progress' ? 'bg-blue-900 text-blue-300' :
                           ($task->status === 'on_hold' ? 'bg-amber-900 text-amber-300' :
                           ($task->status === 'cancelled' ? 'bg-red-900 text-red-300' :
                           'bg-gray-800 text-gray-400'))) }}">
                        {{ ucwords(str_replace('_', ' ', $task->status)) }}
                    </span>
                </div>
                <div>
                    <p class="text-xs text-gray-500 mb-1">Priority</p>
                    <span class="text-xs font-medium px-2 py-1 rounded-full
                        {{ $task->priority === 'high' ? 'bg-red-900 text-red-300' :
                           ($task->priority === 'medium' ? 'bg-amber-900 text-amber-300' :
                           'bg-green-900 text-green-300') }}">
                        {{ ucfirst($task->priority) }}
                    </span>
                </div>
                <div>
                    <p class="text-xs text-gray-500 mb-1">Due Date</p>
                    <p class="text-sm {{ $task->isOverdue() ? 'text-red-400 font-medium' : 'text-white' }}">
                        {{ $task->isOverdue() ? '⚠ ' : '' }}{{ $task->due_date->format('M d, Y') }}
                    </p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 mb-1">Assigned To</p>
                    @if($task->assignedUser)
                    <div class="flex items-center gap-2">
                        <div class="w-6 h-6 rounded bg-indigo-600 flex items-center justify-center text-xs text-white font-bold">
                            {{ strtoupper(substr($task->assignedUser->name, 0, 2)) }}
                        </div>
                        <span class="text-sm text-white">{{ $task->assignedUser->name }}</span>
                    </div>
                    @else
                    <p class="text-sm text-gray-500">Unassigned</p>
                    @endif
                </div>
                <div>
                    <p class="text-xs text-gray-500 mb-1">Created By</p>
                    <div class="flex items-center gap-2">
                        <div class="w-6 h-6 rounded bg-gray-600 flex items-center justify-center text-xs text-white font-bold">
                            {{ strtoupper(substr($task->creator->name ?? 'N', 0, 2)) }}
                        </div>
                        <span class="text-sm text-white">{{ $task->creator->name ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>

            @if($task->description)
            <div class="mt-4 pt-4 border-t border-gray-800">
                <p class="text-xs text-gray-500 mb-2">Description</p>
                <p class="text-sm text-gray-300 leading-relaxed">{{ $task->description }}</p>
            </div>
            @endif
        </div>

        {{-- Comments Card --}}
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-6">
            <h2 class="text-sm font-semibold text-gray-400 uppercase tracking-wider mb-4">
                Comments <span class="text-gray-600">({{ $task->comments->count() }})</span>
            </h2>

            @forelse($task->comments as $comment)
            <div class="flex gap-3 mb-4">
                <div class="w-8 h-8 rounded bg-indigo-600 flex items-center justify-center text-xs text-white font-bold flex-shrink-0">
                    {{ strtoupper(substr($comment->user->name, 0, 2)) }}
                </div>
                <div class="flex-1 bg-gray-800 rounded-lg p-3">
                    <div class="flex items-center justify-between mb-1">
                        <span class="text-xs font-semibold text-white">{{ $comment->user->name }}</span>
                        <span class="text-xs text-gray-500">{{ $comment->created_at->diffForHumans() }}</span>
                    </div>
                    <p class="text-sm text-gray-300">{{ $comment->content }}</p>
                </div>
            </div>
            @empty
            <p class="text-sm text-gray-500 text-center py-4">No comments yet.</p>
            @endforelse
        </div>

    </div>

    {{-- Right: Activity Log --}}
    <div class="lg:col-span-1">
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-6">
            <h2 class="text-sm font-semibold text-gray-400 uppercase tracking-wider mb-4">Activity Log</h2>

            @forelse($task->activities as $activity)
            <div class="flex gap-3 mb-4">
                <div class="flex flex-col items-center">
                    <div class="w-2 h-2 rounded-full bg-indigo-500 mt-1.5 flex-shrink-0"></div>
                    @if(!$loop->last)
                    <div class="w-px flex-1 bg-gray-800 mt-1"></div>
                    @endif
                </div>
                <div class="pb-4">
                    <p class="text-xs text-gray-300">
                        <span class="font-semibold text-white">{{ $activity->user->name }}</span>
                        — {{ $activity->description }}
                    </p>
                    <p class="text-xs text-gray-500 mt-1">{{ $activity->activity_date }}</p>
                </div>
            </div>
            @empty
            <p class="text-sm text-gray-500 text-center py-4">No activity yet.</p>
            @endforelse
        </div>
    </div>

</div>

@endsection
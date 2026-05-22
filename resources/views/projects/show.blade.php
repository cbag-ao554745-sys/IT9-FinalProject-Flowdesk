@extends('layouts.app')

@section('title', $project->name)
@section('page-title', $project->name)

@section('content')

{{-- Header --}}
<div class="flex items-start justify-between mb-6">
    <div class="flex items-center gap-3">
        <a href="{{ route('projects.index') }}" class="text-gray-400 hover:text-white">← Back</a>
        <div>
            <h1 class="text-xl font-bold text-white">{{ $project->name }}</h1>
            <p class="text-sm text-gray-400 mt-1">Managed by {{ $project->manager->name }}</p>
        </div>
    </div>
    <div class="flex items-center gap-2">
        <span class="text-xs font-medium px-2 py-1 rounded-full
            {{ $project->status === 'completed' ? 'bg-green-900 text-green-300' :
               ($project->status === 'in_progress' ? 'bg-blue-900 text-blue-300' :
               'bg-gray-800 text-gray-400') }}">
            {{ ucwords(str_replace('_',' ',$project->status)) }}
        </span>
        @if(auth()->user()->role !== 'team_member')
        <a href="{{ route('projects.edit', $project) }}"
           class="px-3 py-1.5 bg-gray-800 text-gray-300 text-xs font-medium rounded-lg hover:bg-gray-700">
            Edit
        </a>
        @endif
    </div>
</div>

{{-- Stats --}}
<div class="grid grid-cols-4 gap-4 mb-6">
    <div class="bg-gray-900 border border-gray-800 rounded-xl p-4 text-center">
        <p class="text-2xl font-bold text-white">{{ $project->tasks->count() }}</p>
        <p class="text-xs text-gray-400 mt-1">Total Tasks</p>
    </div>
    <div class="bg-gray-900 border border-gray-800 rounded-xl p-4 text-center">
        <p class="text-2xl font-bold text-blue-400">{{ $tasksByStatus->get('in_progress', collect())->count() }}</p>
        <p class="text-xs text-gray-400 mt-1">In Progress</p>
    </div>
    <div class="bg-gray-900 border border-gray-800 rounded-xl p-4 text-center">
        <p class="text-2xl font-bold text-green-400">{{ $tasksByStatus->get('completed', collect())->count() }}</p>
        <p class="text-xs text-gray-400 mt-1">Completed</p>
    </div>
    <div class="bg-gray-900 border border-gray-800 rounded-xl p-4 text-center">
        <p class="text-2xl font-bold text-white">{{ $project->progressPercent() }}%</p>
        <p class="text-xs text-gray-400 mt-1">Progress</p>
    </div>
</div>

{{-- Description --}}
<div class="bg-gray-900 border border-gray-800 rounded-xl p-5 mb-5">
    <h2 class="text-sm font-semibold text-white mb-2">Description</h2>
    <p class="text-sm text-gray-400 leading-relaxed">
        {{ $project->description ?? 'No description provided.' }}
    </p>
    <div class="mt-4 pt-4 border-t border-gray-800 grid grid-cols-2 gap-4 text-xs text-gray-500">
        <div><span class="text-gray-400">Start:</span> {{ $project->start_date->format('M d, Y') }}</div>
        <div><span class="text-gray-400">Due:</span> {{ $project->due_date->format('M d, Y') }}</div>
    </div>
</div>

{{-- Tasks --}}
<div class="bg-gray-900 border border-gray-800 rounded-xl p-5">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-sm font-semibold text-white">Tasks</h2>
        @if(auth()->user()->role !== 'team_member')
        <a href="{{ route('tasks.create') }}?project_id={{ $project->id }}"
           class="px-3 py-1.5 bg-indigo-600 text-white text-xs font-semibold rounded-lg hover:bg-indigo-700">
            + Add Task
        </a>
        @endif
    </div>

    @foreach(['pending','in_progress','on_hold','completed','cancelled'] as $status)
    @php $statusTasks = $tasksByStatus->get($status, collect()); @endphp
    @if($statusTasks->count())
    <div class="mb-4">
        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">
            {{ ucwords(str_replace('_',' ',$status)) }}
            ({{ $statusTasks->count() }})
        </p>
        <div class="space-y-1">
            @foreach($statusTasks as $task)
            <div class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-gray-800">
                <a href="{{ route('tasks.show', $task) }}"
                   class="flex-1 text-sm text-gray-300 hover:text-white font-medium">
                    {{ $task->title }}
                </a>
                <span class="text-xs font-medium px-2 py-0.5 rounded-full
                    {{ $task->priority === 'high' ? 'bg-red-900 text-red-300' :
                       ($task->priority === 'medium' ? 'bg-amber-900 text-amber-300' :
                       'bg-green-900 text-green-300') }}">
                    {{ ucfirst($task->priority) }}
                </span>
                @if($task->assignedUser)
                <div class="w-6 h-6 rounded bg-indigo-600 flex items-center justify-center text-xs text-white font-bold"
                     title="{{ $task->assignedUser->name }}">
                    {{ strtoupper(substr($task->assignedUser->name, 0, 2)) }}
                </div>
                @endif
                <span class="text-xs text-gray-500">{{ $task->due_date->format('M d') }}</span>
            </div>
            @endforeach
        </div>
    </div>
    @endif
    @endforeach
</div>
@endsection
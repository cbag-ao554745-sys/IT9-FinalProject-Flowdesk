@extends('layouts.app')

@section('title', 'Tasks')
@section('page-title', 'All Tasks')

@section('content')

<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-xl font-bold text-white">Tasks</h1>
        <p class="text-sm text-gray-400 mt-1">{{ $tasks->total() }} total tasks</p>
    </div>
    @if(auth()->user()->role !== 'team_member')
    <a href="{{ route('tasks.create') }}"
       class="flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white text-sm font-semibold rounded-lg hover:bg-indigo-700">
        + New Task
    </a>
    @endif
</div>

{{-- Filters --}}
<div class="flex gap-3 mb-5 flex-wrap">
    <form method="GET" action="{{ route('tasks.index') }}" class="flex gap-3 flex-wrap">
        <select name="project_id" onchange="this.form.submit()"
                class="bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 text-sm text-gray-300 outline-none">
            <option value="">All Projects</option>
            @foreach($projects as $proj)
            <option value="{{ $proj->id }}" {{ request('project_id') == $proj->id ? 'selected' : '' }}>
                {{ $proj->name }}
            </option>
            @endforeach
        </select>
        <select name="status" onchange="this.form.submit()"
                class="bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 text-sm text-gray-300 outline-none">
            <option value="">All Statuses</option>
            @foreach(['pending','in_progress','on_hold','completed','cancelled'] as $s)
            <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>
                {{ ucwords(str_replace('_',' ',$s)) }}
            </option>
            @endforeach
        </select>
        <select name="priority" onchange="this.form.submit()"
                class="bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 text-sm text-gray-300 outline-none">
            <option value="">All Priorities</option>
            @foreach(['high','medium','low'] as $p)
            <option value="{{ $p }}" {{ request('priority') === $p ? 'selected' : '' }}>
                {{ ucfirst($p) }}
            </option>
            @endforeach
        </select>
        <a href="{{ route('tasks.index') }}"
           class="px-3 py-2 bg-gray-800 text-gray-400 text-sm rounded-lg hover:bg-gray-700">
            Clear
        </a>
    </form>
</div>

{{-- Table --}}
<div class="bg-gray-900 border border-gray-800 rounded-xl overflow-hidden">
    <table class="w-full">
        <thead>
            <tr class="bg-gray-800 border-b border-gray-700">
                <th class="text-left px-4 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Task</th>
                <th class="text-left px-4 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Project</th>
                <th class="text-left px-4 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Assignee</th>
                <th class="text-left px-4 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Status</th>
                <th class="text-left px-4 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Priority</th>
                <th class="text-left px-4 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Due Date</th>
                <th class="px-4 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-800">
            @forelse($tasks as $task)
            <tr class="hover:bg-gray-800 transition-colors">
                <td class="px-4 py-3">
                    <a href="{{ route('tasks.show', $task) }}"
                       class="text-sm font-medium text-gray-300 hover:text-white
                       {{ $task->status === 'completed' ? 'line-through text-gray-500' : '' }}">
                        {{ $task->title }}
                    </a>
                </td>
                <td class="px-4 py-3">
                    <span class="text-xs text-gray-500">{{ $task->project->name }}</span>
                </td>
                <td class="px-4 py-3">
                    @if($task->assignedUser)
                    <div class="flex items-center gap-2">
                        <div class="w-6 h-6 rounded bg-indigo-600 flex items-center justify-center text-xs text-white font-bold">
                            {{ strtoupper(substr($task->assignedUser->name, 0, 2)) }}
                        </div>
                        <span class="text-xs text-gray-400">{{ $task->assignedUser->name }}</span>
                    </div>
                    @else
                    <span class="text-xs text-gray-600">Unassigned</span>
                    @endif
                </td>
                <td class="px-4 py-3">
                    <span class="text-xs font-medium px-2 py-1 rounded-full
                        {{ $task->status === 'completed' ? 'bg-green-900 text-green-300' :
                           ($task->status === 'in_progress' ? 'bg-blue-900 text-blue-300' :
                           ($task->status === 'on_hold' ? 'bg-amber-900 text-amber-300' :
                           ($task->status === 'cancelled' ? 'bg-red-900 text-red-300' :
                           'bg-gray-800 text-gray-400'))) }}">
                        {{ ucwords(str_replace('_',' ',$task->status)) }}
                    </span>
                </td>
                <td class="px-4 py-3">
                    <span class="text-xs font-medium px-2 py-1 rounded-full
                        {{ $task->priority === 'high' ? 'bg-red-900 text-red-300' :
                           ($task->priority === 'medium' ? 'bg-amber-900 text-amber-300' :
                           'bg-green-900 text-green-300') }}">
                        {{ ucfirst($task->priority) }}
                    </span>
                </td>
                <td class="px-4 py-3">
                    <span class="text-xs {{ $task->isOverdue() ? 'text-red-400 font-medium' : 'text-gray-500' }}">
                        {{ $task->isOverdue() ? '⚠ ' : '' }}{{ $task->due_date->format('M d, Y') }}
                    </span>
                </td>
                <td class="px-4 py-3">
                    <div class="flex items-center gap-2">
                        <a href="{{ route('tasks.show', $task) }}"
                           class="text-xs text-blue-400 hover:text-blue-300">View</a>
                        @if(auth()->user()->role !== 'team_member')
                        <a href="{{ route('tasks.edit', $task) }}"
                           class="text-xs text-gray-400 hover:text-gray-300">Edit</a>
                        <form action="{{ route('tasks.destroy', $task) }}" method="POST"
                              onsubmit="return confirm('Delete this task?')">
                            @csrf @method('DELETE')
                            <button class="text-xs text-red-500 hover:text-red-400">Delete</button>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="px-4 py-12 text-center text-sm text-gray-500">
                    No tasks found.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">{{ $tasks->withQueryString()->links() }}</div>

@endsection
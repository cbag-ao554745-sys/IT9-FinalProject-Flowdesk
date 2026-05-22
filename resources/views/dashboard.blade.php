@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')

{{-- Greeting --}}
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-xl font-bold text-white">
            Good {{ now()->hour < 12 ? 'Morning' : (now()->hour < 18 ? 'Afternoon' : 'Evening') }}
            {{ explode(' ', auth()->user()->Admin)[0] }} 👋
        </h1>
        <p class="text-sm text-gray-400 mt-1">{{ now()->format('l, F j, Y') }}</p>
    </div>
    <a href="{{ route('projects.create') }}"
       class="flex items-center gap-2 px-4 py-2 bg-indigo-800 text-white-300 text-sm font-medium rounded-lg hover:bg-gray-700">
        + New Project
    </a>
</div>

{{-- Stats --}}
<div class="grid grid-cols-4 gap-4 mb-6">
    <div class="bg-gray-900 border border-gray-800 rounded-xl p-5 border-t-2 border-t-indigo-500">
        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Total Projects</p>
        <p class="text-3xl font-bold text-indigo-400">{{ $stats['total_projects'] }}</p>
        <p class="text-xs text-gray-500 mt-1">Active projects</p>
    </div>
    <div class="bg-gray-900 border border-gray-800 rounded-xl p-5 border-t-2 border-t-green-500">
        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Completed</p>
        <p class="text-3xl font-bold text-green-400">{{ $stats['completed_tasks'] }}</p>
        <p class="text-xs text-gray-500 mt-1">Tasks done</p>
    </div>
    <div class="bg-gray-900 border border-gray-800 rounded-xl p-5 border-t-2 border-t-amber-500">
        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">In Progress</p>
        <p class="text-3xl font-bold text-amber-400">{{ $stats['active_tasks'] }}</p>
        <p class="text-xs text-gray-500 mt-1">Active right now</p>
    </div>
    <div class="bg-gray-900 border border-gray-800 rounded-xl p-5 border-t-2 border-t-red-500">
        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Overdue</p>
        <p class="text-3xl font-bold text-red-400">{{ $stats['overdue_tasks'] }}</p>
        <p class="text-xs text-gray-500 mt-1">Need attention</p>
    </div>
</div>

{{-- Grid --}}
<div class="grid grid-cols-3 gap-5">

    {{-- Left Column --}}
    <div class="col-span-2 space-y-5">

        {{-- My Tasks --}}
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-5">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-sm font-semibold text-white">My Tasks</h2>
                <a href="{{ route('tasks.index') }}" class="text-xs text-indigo-400 hover:underline">See all</a>
            </div>
            @forelse($myTasks as $task)
            <div class="flex items-center gap-3 py-2.5 border-b border-gray-800 last:border-0">
                <div class="w-4 h-4 rounded border-2 border-gray-600 flex-shrink-0"></div>
                <a href="{{ route('tasks.show', $task) }}"
                   class="flex-1 text-sm text-gray-300 hover:text-white">
                    {{ $task->title }}
                </a>
                <span class="text-xs px-2 py-0.5 rounded-full font-medium
                    {{ $task->priority === 'high' ? 'bg-red-900 text-red-300' :
                       ($task->priority === 'medium' ? 'bg-amber-900 text-amber-300' : 'bg-green-900 text-green-300') }}">
                    {{ ucfirst($task->priority) }}
                </span>
                <span class="text-xs text-gray-500">{{ $task->due_date->format('M d') }}</span>
            </div>
            @empty
            <p class="text-sm text-gray-500 text-center py-4">No tasks assigned to you. </p>
            @endforelse
        </div>

        {{-- Project Progress --}}
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-5">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-sm font-semibold text-white">Project Progress</h2>
                <a href="{{ route('projects.index') }}" class="text-xs text-indigo-400 hover:underline">All projects</a>
            </div>
            <div class="space-y-4">
                @foreach($projects as $project)
                @php $pct = $project->progressPercent(); @endphp
                <div>
                    <div class="flex justify-between mb-1">
                        <a href="{{ route('projects.show', $project) }}"
                           class="text-sm text-gray-300 hover:text-white font-medium">
                            {{ $project->name }}
                        </a>
                        <span class="text-xs text-gray-500">{{ $pct }}%</span>
                    </div>
                    <div class="h-1.5 bg-gray-800 rounded-full overflow-hidden">
                        <div class="h-full rounded-full
                            {{ $pct >= 80 ? 'bg-green-500' : ($pct >= 50 ? 'bg-indigo-500' : 'bg-amber-500') }}"
                             style="width: {{ $pct }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Right Column --}}
    <div class="space-y-5">

        {{-- Recent Activity --}}
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-5">
            <h2 class="text-sm font-semibold text-white mb-4">Recent Activity</h2>
            <div class="space-y-3">
                @forelse($recentActivities as $activity)
                <div class="flex gap-2.5">
                    <div class="w-2 h-2 rounded-full mt-1.5 flex-shrink-0
                        {{ $activity->activity_type === 'completed' ? 'bg-green-500' :
                           ($activity->activity_type === 'created' ? 'bg-indigo-500' : 'bg-amber-500') }}">
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 leading-relaxed">
                            <span class="text-white font-medium">{{ $activity->user->name }}</span>
                            — {{ $activity->description }}
                        </p>
                        <p class="text-xs text-gray-600 mt-0.5">
                            {{ $activity->activity_date->diffForHumans() }}
                        </p>
                    </div>
                </div>
                @empty
                <p class="text-xs text-gray-500">No recent activity.</p>
                @endforelse
            </div>
        </div>

        {{-- Team Workload --}}
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-5">
            <h2 class="text-sm font-semibold text-white mb-4">Team Workload</h2>
            <div class="space-y-3">
                @foreach($teamWorkload as $member)
                @php $pct = $member->total_tasks > 0 ? min(100, ($member->total_tasks / 15) * 100) : 0; @endphp
                <div class="flex items-center gap-2.5">
                    <div class="w-7 h-7 rounded-lg bg-indigo-600 flex items-center justify-center text-xs font-bold text-white flex-shrink-0">
                        {{ strtoupper(substr($member->name, 0, 2)) }}
                    </div>
                    <div class="flex-1">
                        <div class="flex justify-between mb-1">
                            <span class="text-xs text-gray-300 font-medium">{{ $member->name }}</span>
                            <span class="text-xs text-gray-500">{{ $member->total_tasks }} tasks</span>
                        </div>
                        <div class="h-1 bg-gray-800 rounded-full overflow-hidden">
                            <div class="h-full rounded-full bg-indigo-500" style="width: {{ $pct }}%"></div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

@endsection
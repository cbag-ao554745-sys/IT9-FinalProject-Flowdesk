@extends('layouts.app')

@section('title', 'Reports')
@section('page-title', 'Reports & Analytics')

@section('content')

<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-xl font-bold text-white">Reports & Analytics</h1>
        <p class="text-sm text-gray-400 mt-1">Team productivity and project performance</p>
    </div>
</div>

{{-- KPI Summary --}}
<div class="grid grid-cols-4 gap-4 mb-6">
    <div class="bg-gray-900 border border-gray-800 rounded-xl p-5 border-t-2 border-t-green-500">
        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Total Tasks</p>
        <p class="text-3xl font-bold text-white">{{ $summary['total_tasks'] }}</p>
    </div>
    <div class="bg-gray-900 border border-gray-800 rounded-xl p-5 border-t-2 border-t-indigo-500">
        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Completed</p>
        <p class="text-3xl font-bold text-green-400">{{ $summary['completed_tasks'] }}</p>
    </div>
    <div class="bg-gray-900 border border-gray-800 rounded-xl p-5 border-t-2 border-t-amber-500">
        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Pending</p>
        <p class="text-3xl font-bold text-amber-400">{{ $summary['pending_tasks'] }}</p>
    </div>
    <div class="bg-gray-900 border border-gray-800 rounded-xl p-5 border-t-2 border-t-red-500">
        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Overdue</p>
        <p class="text-3xl font-bold text-red-400">{{ $summary['overdue_tasks'] }}</p>
    </div>
</div>

{{-- Status Breakdown --}}
<div class="grid grid-cols-2 gap-5 mb-5">
    <div class="bg-gray-900 border border-gray-800 rounded-xl p-5">
        <h2 class="text-sm font-semibold text-white mb-4">Task Status Breakdown</h2>
        @php
            $statusColors = [
                'pending'     => 'bg-gray-500',
                'in_progress' => 'bg-blue-500',
                'on_hold'     => 'bg-amber-500',
                'completed'   => 'bg-green-500',
                'cancelled'   => 'bg-red-500',
            ];
            $total = $completionReport->sum();
        @endphp
        <div class="space-y-3">
            @foreach($statusColors as $status => $color)
            @php
                $count = $completionReport->get($status, 0);
                $pct = $total > 0 ? round(($count / $total) * 100) : 0;
            @endphp
            <div>
                <div class="flex justify-between text-xs mb-1">
                    <span class="text-gray-400 capitalize">{{ ucwords(str_replace('_',' ',$status)) }}</span>
                    <span class="text-gray-300 font-medium">{{ $count }} ({{ $pct }}%)</span>
                </div>
                <div class="h-2 bg-gray-800 rounded-full overflow-hidden">
                    <div class="{{ $color }} h-full rounded-full" style="width: {{ $pct }}%"></div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Project Progress --}}
    <div class="bg-gray-900 border border-gray-800 rounded-xl p-5">
        <h2 class="text-sm font-semibold text-white mb-4">Project Progress</h2>
        <div class="space-y-3">
            @forelse($projectProgress as $item)
            <div>
                <div class="flex justify-between text-xs mb-1">
                    <a href="{{ route('projects.show', $item['project']) }}"
                       class="text-gray-300 font-medium hover:text-indigo-400 truncate max-w-xs">
                        {{ $item['project']->name }}
                    </a>
                    <span class="text-gray-500">{{ $item['completed'] }}/{{ $item['total'] }}</span>
                </div>
                <div class="h-2 bg-gray-800 rounded-full overflow-hidden">
                    <div class="h-full rounded-full
                        {{ $item['percent'] >= 80 ? 'bg-green-500' : ($item['percent'] >= 50 ? 'bg-indigo-500' : 'bg-amber-500') }}"
                         style="width: {{ $item['percent'] }}%"></div>
                </div>
            </div>
            @empty
            <p class="text-sm text-gray-500">No active projects.</p>
            @endforelse
        </div>
    </div>
</div>

{{-- Overdue Tasks --}}
@if($overdueTasks->count())
<div class="bg-gray-900 border border-gray-800 rounded-xl p-5 mb-5">
    <h2 class="text-sm font-semibold text-white mb-4">
        Overdue Tasks
        <span class="text-red-400 font-normal text-xs ml-2">{{ $overdueTasks->count() }} tasks need attention</span>
    </h2>
    <div class="overflow-hidden rounded-lg border border-red-900">
        <table class="w-full">
            <thead>
                <tr class="bg-red-950 border-b border-red-900">
                    <th class="text-left px-4 py-2 text-xs font-semibold text-red-400 uppercase">Task</th>
                    <th class="text-left px-4 py-2 text-xs font-semibold text-red-400 uppercase">Project</th>
                    <th class="text-left px-4 py-2 text-xs font-semibold text-red-400 uppercase">Assignee</th>
                    <th class="text-left px-4 py-2 text-xs font-semibold text-red-400 uppercase">Due Date</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-red-900">
                @foreach($overdueTasks as $task)
                <tr class="hover:bg-red-950">
                    <td class="px-4 py-2.5">
                        <a href="{{ route('tasks.show', $task) }}"
                           class="text-sm font-medium text-gray-300 hover:text-white">
                            {{ $task->title }}
                        </a>
                    </td>
                    <td class="px-4 py-2.5 text-xs text-gray-500">{{ $task->project->name }}</td>
                    <td class="px-4 py-2.5 text-xs text-gray-400">{{ $task->assignedUser?->name ?? '—' }}</td>
                    <td class="px-4 py-2.5 text-xs text-red-400 font-medium">{{ $task->due_date->format('M d, Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

{{-- User Productivity --}}
<div class="bg-gray-900 border border-gray-800 rounded-xl p-5">
    <h2 class="text-sm font-semibold text-white mb-4">User Productivity Summary</h2>
    <table class="w-full">
        <thead>
            <tr class="border-b border-gray-800">
                <th class="text-left px-4 py-2.5 text-xs font-semibold text-gray-400 uppercase">Member</th>
                <th class="text-left px-4 py-2.5 text-xs font-semibold text-gray-400 uppercase">Role</th>
                <th class="text-center px-4 py-2.5 text-xs font-semibold text-gray-400 uppercase">Assigned</th>
                <th class="text-center px-4 py-2.5 text-xs font-semibold text-gray-400 uppercase">Completed</th>
                <th class="text-center px-4 py-2.5 text-xs font-semibold text-gray-400 uppercase">Overdue</th>
                <th class="text-left px-4 py-2.5 text-xs font-semibold text-gray-400 uppercase">On-Time Rate</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-800">
            @foreach($userProductivity as $member)
            <tr class="hover:bg-gray-800">
                <td class="px-4 py-3">
                    <div class="flex items-center gap-2.5">
                        <div class="w-7 h-7 rounded-lg bg-indigo-600 flex items-center justify-center text-xs font-bold text-white">
                            {{ strtoupper(substr($member['name'], 0, 2)) }}
                        </div>
                        <span class="text-sm font-medium text-gray-300">{{ $member['name'] }}</span>
                    </div>
                </td>
                <td class="px-4 py-3">
                    <span class="text-xs px-2 py-1 rounded-full
                        {{ $member['role'] === 'admin' ? 'bg-purple-900 text-purple-300' :
                           ($member['role'] === 'project_manager' ? 'bg-blue-900 text-blue-300' :
                           'bg-green-900 text-green-300') }}">
                        {{ ucwords(str_replace('_',' ',$member['role'])) }}
                    </span>
                </td>
                <td class="px-4 py-3 text-center text-sm text-gray-400">{{ $member['total_assigned'] }}</td>
                <td class="px-4 py-3 text-center text-sm text-green-400 font-medium">{{ $member['completed_tasks'] }}</td>
                <td class="px-4 py-3 text-center text-sm {{ $member['overdue_tasks'] > 0 ? 'text-red-400' : 'text-gray-500' }} font-medium">
                    {{ $member['overdue_tasks'] }}
                </td>
                <td class="px-4 py-3">
                    <div class="flex items-center gap-2">
                        <div class="flex-1 h-1.5 bg-gray-800 rounded-full overflow-hidden">
                            <div class="h-full rounded-full
                                {{ $member['on_time_rate'] >= 80 ? 'bg-green-500' :
                                   ($member['on_time_rate'] >= 60 ? 'bg-amber-500' : 'bg-red-500') }}"
                                 style="width: {{ $member['on_time_rate'] }}%"></div>
                        </div>
                        <span class="text-xs text-gray-400 w-8">{{ $member['on_time_rate'] }}%</span>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endsection
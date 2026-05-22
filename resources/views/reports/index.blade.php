@extends('layouts.app')

@section('title', 'Reports')
@section('page-title', 'Reports & Analytics')

@section('page-actions')
    <a href="{{ route('reports.pdf') }}" 
       class="flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl transition-colors">
        <i class="fa-solid fa-download"></i>
        Export PDF
    </a>
@endsection

@section('content')

<div class="space-y-8">

    <!-- Page Header -->
    <div>
        <h1 class="text-3xl font-semibold text-white">Reports & Analytics</h1>
        <p class="text-gray-400 mt-1">Team productivity and project performance</p>
    </div>

    <!-- KPI Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-[#1F2937] border border-gray-700 rounded-2xl p-6 border-t-4 border-t-green-500">
            <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Total Tasks</p>
            <p class="text-4xl font-bold text-white mt-3">{{ $summary['total_tasks'] ?? 0 }}</p>
        </div>
        <div class="bg-[#1F2937] border border-gray-700 rounded-2xl p-6 border-t-4 border-t-indigo-500">
            <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Completed</p>
            <p class="text-4xl font-bold text-green-400 mt-3">{{ $summary['completed_tasks'] ?? 0 }}</p>
        </div>
        <div class="bg-[#1F2937] border border-gray-700 rounded-2xl p-6 border-t-4 border-t-amber-500">
            <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Pending</p>
            <p class="text-4xl font-bold text-amber-400 mt-3">{{ $summary['pending_tasks'] ?? 0 }}</p>
        </div>
        <div class="bg-[#1F2937] border border-gray-700 rounded-2xl p-6 border-t-4 border-t-red-500">
            <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Overdue</p>
            <p class="text-4xl font-bold text-red-400 mt-3">{{ $summary['overdue_tasks'] ?? 0 }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- Task Status Breakdown -->
        <div class="bg-[#1F2937] border border-gray-700 rounded-2xl p-6">
            <h2 class="text-lg font-semibold text-white mb-5">Task Status Breakdown</h2>
            @php
                $total = $completionReport->sum();
            @endphp
            <div class="space-y-5">
                @foreach(['pending', 'in_progress', 'on_hold', 'completed', 'cancelled'] as $status)
                    @php
                        $count = $completionReport->get($status, 0);
                        $pct = $total > 0 ? round(($count / $total) * 100) : 0;
                        $color = match($status) {
                            'completed' => 'bg-green-500',
                            'in_progress' => 'bg-blue-500',
                            'pending' => 'bg-gray-500',
                            'on_hold' => 'bg-amber-500',
                            'cancelled' => 'bg-red-500',
                            default => 'bg-gray-500'
                        };
                    @endphp
                    <div>
                        <div class="flex justify-between text-sm mb-2">
                            <span class="capitalize text-gray-300">{{ str_replace('_', ' ', $status) }}</span>
                            <span class="text-gray-400">{{ $count }} ({{ $pct }}%)</span>
                        </div>
                        <div class="h-2.5 bg-gray-800 rounded-full overflow-hidden">
                            <div class="{{ $color }} h-full rounded-full transition-all" style="width: {{ $pct }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Project Progress -->
        <div class="bg-[#1F2937] border border-gray-700 rounded-2xl p-6">
            <h2 class="text-lg font-semibold text-white mb-5">Project Progress</h2>
            <div class="space-y-5">
                @forelse($projectProgress as $item)
                <div>
                    <div class="flex justify-between text-sm mb-2">
                        <a href="{{ route('projects.show', $item['project']) }}" 
                           class="text-gray-300 hover:text-white">{{ $item['project']->name }}</a>
                        <span class="text-gray-400">{{ $item['completed'] }}/{{ $item['total'] }}</span>
                    </div>
                    <div class="h-2.5 bg-gray-800 rounded-full overflow-hidden">
                        <div class="h-full rounded-full 
                            {{ $item['percent'] >= 80 ? 'bg-green-500' : ($item['percent'] >= 50 ? 'bg-indigo-500' : 'bg-amber-500') }}"
                             style="width: {{ $item['percent'] }}%"></div>
                    </div>
                </div>
                @empty
                <p class="text-gray-500 py-8 text-center">No active projects found.</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Overdue Tasks -->
    @if($overdueTasks->count())
    <div class="bg-[#1F2937] border border-red-900/50 rounded-2xl p-6">
        <h2 class="text-lg font-semibold text-white mb-4 flex items-center gap-2">
            Overdue Tasks 
            <span class="text-red-400 text-sm font-normal">({{ $overdueTasks->count() }} tasks need attention)</span>
        </h2>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-700">
                        <th class="text-left py-3 px-4 text-xs font-medium text-red-400">TASK</th>
                        <th class="text-left py-3 px-4 text-xs font-medium text-red-400">PROJECT</th>
                        <th class="text-left py-3 px-4 text-xs font-medium text-red-400">ASSIGNEE</th>
                        <th class="text-left py-3 px-4 text-xs font-medium text-red-400">DUE DATE</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700">
                    @foreach($overdueTasks as $task)
                    <tr class="hover:bg-gray-800/50">
                        <td class="py-3 px-4 font-medium text-white">{{ $task->title }}</td>
                        <td class="py-3 px-4 text-gray-400">{{ $task->project->name ?? '—' }}</td>
                        <td class="py-3 px-4 text-gray-400">{{ $task->assignedUser?->name ?? 'Unassigned' }}</td>
                        <td class="py-3 px-4 text-red-400">{{ $task->due_date->format('M d, Y') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <!-- User Productivity -->
    <div class="bg-[#1F2937] border border-gray-700 rounded-2xl p-6">
        <h2 class="text-lg font-semibold text-white mb-5">User Productivity Summary</h2>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-700">
                        <th class="text-left py-4 px-4 text-xs font-medium text-gray-400">MEMBER</th>
                        <th class="text-left py-4 px-4 text-xs font-medium text-gray-400">ROLE</th>
                        <th class="text-center py-4 px-4 text-xs font-medium text-gray-400">ASSIGNED</th>
                        <th class="text-center py-4 px-4 text-xs font-medium text-gray-400">COMPLETED</th>
                        <th class="text-center py-4 px-4 text-xs font-medium text-gray-400">OVERDUE</th>
                        <th class="text-center py-4 px-4 text-xs font-medium text-gray-400">ON-TIME RATE</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700">
                    @foreach($userProductivity as $member)
                    <tr class="hover:bg-gray-800/50">
                        <td class="py-4 px-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center text-white text-sm font-bold">
                                    {{ strtoupper(substr($member['name'], 0, 2)) }}
                                </div>
                                <span class="font-medium">{{ $member['name'] }}</span>
                            </div>
                        </td>
                        <td class="py-4 px-4">
                            <span class="text-xs px-3 py-1 rounded-full bg-gray-800">
                                {{ ucwords(str_replace('_', ' ', $member['role'] ?? '')) }}
                            </span>
                        </td>
                        <td class="py-4 px-4 text-center">{{ $member['total_assigned'] }}</td>
                        <td class="py-4 px-4 text-center text-green-400">{{ $member['completed_tasks'] }}</td>
                        <td class="py-4 px-4 text-center {{ $member['overdue_tasks'] > 0 ? 'text-red-400' : 'text-gray-400' }}">
                            {{ $member['overdue_tasks'] }}
                        </td>
                        <td class="py-4 px-4">
                            <div class="flex items-center gap-3">
                                <div class="flex-1 h-2 bg-gray-800 rounded-full">
                                    <div class="h-2 rounded-full bg-green-500" 
                                         style="width: {{ $member['on_time_rate'] }}%"></div>
                                </div>
                                <span class="text-sm w-10 text-right">{{ $member['on_time_rate'] }}%</span>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>

@endsection
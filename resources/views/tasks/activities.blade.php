@extends('layouts.app')

@section('title', 'Activity Log')
@section('page-title', 'Activity Log')

@section('content')

<div class="flex items-center gap-3 mb-6">
    <a href="{{ route('tasks.show', $task) }}" class="text-gray-400 hover:text-white">← Back</a>
    <div>
        <h1 class="text-xl font-bold text-white">Activity Log</h1>
        <p class="text-sm text-gray-400 mt-1">{{ $task->title }}</p>
    </div>
</div>

<div class="max-w-2xl bg-gray-900 border border-gray-800 rounded-xl p-6">
    <div class="space-y-4">
        @forelse($activities as $activity)
        <div class="flex gap-3">
            <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0
                {{ $activity->activity_type === 'completed' ? 'bg-green-900' :
                   ($activity->activity_type === 'created' ? 'bg-indigo-900' :
                   ($activity->activity_type === 'reassigned' ? 'bg-purple-900' :
                   'bg-amber-900')) }}">
                <div class="w-2 h-2 rounded-full
                    {{ $activity->activity_type === 'completed' ? 'bg-green-400' :
                       ($activity->activity_type === 'created' ? 'bg-indigo-400' :
                       ($activity->activity_type === 'reassigned' ? 'bg-purple-400' :
                       'bg-amber-400')) }}">
                </div>
            </div>
            <div class="flex-1 pb-4 border-b border-gray-800 last:border-0">
                <div class="flex items-center justify-between mb-1">
                    <span class="text-xs font-semibold text-white">{{ $activity->user->name }}</span>
                    <span class="text-xs text-gray-500 font-mono">{{ $activity->activity_date->format('M d, Y · g:i A') }}</span>
                </div>
                <p class="text-sm text-gray-400">{{ $activity->description }}</p>
                <span class="inline-block mt-1 text-xs px-2 py-0.5 rounded-full bg-gray-800 text-gray-500">
                    {{ str_replace('_', ' ', $activity->activity_type) }}
                </span>
            </div>
        </div>
        @empty
        <p class="text-sm text-gray-500 text-center py-8">No activity recorded yet.</p>
        @endforelse
    </div>
    <div class="mt-4">{{ $activities->links() }}</div>
</div>

@endsection
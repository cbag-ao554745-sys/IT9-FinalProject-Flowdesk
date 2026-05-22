@extends('layouts.app')

@section('title', 'Projects')
@section('page-title', 'Projects')

@section('content')

<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-xl font-bold text-white">Projects</h1>
        <p class="text-sm text-gray-400 mt-1">{{ $projects->total() }} total projects</p>
    </div>
    @if(auth()->user()->role !== 'team_member')
    <a href="{{ route('projects.create') }}"
       class="flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white text-sm font-semibold rounded-lg hover:bg-indigo-700">
        + New Project
    </a>
    @endif
</div>

{{-- Filters --}}
<div class="flex gap-3 mb-5">
    <form method="GET" action="{{ route('projects.index') }}" class="flex gap-3">
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
    </form>
</div>

{{-- Projects Grid --}}
<div class="grid grid-cols-3 gap-4 mb-6">
    @forelse($projects as $project)
    @php $pct = $project->progressPercent(); @endphp
    <div class="bg-gray-900 border border-gray-800 rounded-xl p-5 hover:border-gray-700 transition-colors">

        {{-- Status & Priority --}}
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-medium px-2 py-1 rounded-full
                {{ $project->status === 'completed' ? 'bg-green-900 text-green-300' :
                   ($project->status === 'in_progress' ? 'bg-blue-900 text-blue-300' :
                   ($project->status === 'on_hold' ? 'bg-amber-900 text-amber-300' :
                   ($project->status === 'cancelled' ? 'bg-red-900 text-red-300' :
                   'bg-gray-800 text-gray-400'))) }}">
                {{ ucwords(str_replace('_',' ',$project->status)) }}
            </span>
            <span class="text-xs font-medium px-2 py-1 rounded-full
                {{ $project->priority === 'high' ? 'bg-red-900 text-red-300' :
                   ($project->priority === 'medium' ? 'bg-amber-900 text-amber-300' :
                   'bg-green-900 text-green-300') }}">
                {{ ucfirst($project->priority) }}
            </span>
        </div>

        {{-- Name --}}
        <a href="{{ route('projects.show', $project) }}"
           class="block text-sm font-bold text-white hover:text-indigo-400 mb-2">
            {{ $project->name }}
        </a>

        {{-- Meta --}}
        <p class="text-xs text-gray-500 mb-3">
            Due {{ $project->due_date->format('M d, Y') }}
            · {{ $project->tasks->count() }} tasks
        </p>

        {{-- Progress --}}
        <div class="flex justify-between mb-1">
            <span class="text-xs text-gray-500">Progress</span>
            <span class="text-xs text-gray-500">{{ $pct }}%</span>
        </div>
        <div class="h-1.5 bg-gray-800 rounded-full overflow-hidden mb-4">
            <div class="h-full rounded-full
                {{ $pct >= 80 ? 'bg-green-500' : ($pct >= 50 ? 'bg-indigo-500' : 'bg-amber-500') }}"
                 style="width: {{ $pct }}%"></div>
        </div>

        {{-- Actions --}}
        @if(auth()->user()->role !== 'team_member')
        <div class="flex gap-2 pt-3 border-t border-gray-800">
            <a href="{{ route('projects.edit', $project) }}"
               class="text-xs text-gray-400 hover:text-indigo-400">Edit</a>
            <form action="{{ route('projects.destroy', $project) }}" method="POST"
                  onsubmit="return confirm('Delete this project?')" class="ml-auto">
                @csrf @method('DELETE')
                <button class="text-xs text-red-500 hover:text-red-400">Delete</button>
            </form>
        </div>
        @endif
    </div>
    @empty
    <div class="col-span-3 text-center py-16 text-gray-500">
        <p class="text-sm">No projects found.</p>
    </div>
    @endforelse
</div>

{{ $projects->withQueryString()->links() }}

@endsection
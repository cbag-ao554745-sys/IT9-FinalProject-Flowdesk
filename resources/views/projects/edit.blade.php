@extends('layouts.app')

@section('title', 'Edit Project')
@section('page-title', 'Edit Project')

@section('content')

<div class="max-w-2xl">

    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('projects.show', $project) }}" class="text-gray-400 hover:text-white">← Back</a>
        <h1 class="text-xl font-bold text-white">Edit: {{ $project->name }}</h1>
    </div>

    <div class="bg-gray-900 border border-gray-800 rounded-xl p-6">
        <form action="{{ route('projects.update', $project) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            {{-- Name --}}
            <div>
                <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1.5">
                    Project Name *
                </label>
                <input type="text" name="name" value="{{ old('name', $project->name) }}"
                       class="w-full bg-gray-800 border border-gray-700 rounded-lg px-3 py-2.5 text-sm text-white outline-none focus:border-indigo-500">
                @error('name')<p class="mt-1 text-xs text-red-400">{{ $message }}</p>@enderror
            </div>

            {{-- Description --}}
            <div>
                <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1.5">
                    Description
                </label>
                <textarea name="description" rows="3"
                          class="w-full bg-gray-800 border border-gray-700 rounded-lg px-3 py-2.5 text-sm text-white outline-none focus:border-indigo-500 resize-none">{{ old('description', $project->description) }}</textarea>
            </div>

            {{-- Dates --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1.5">
                        Start Date *
                    </label>
                    <input type="date" name="start_date"
                           value="{{ old('start_date', $project->start_date->format('Y-m-d')) }}"
                           class="w-full bg-gray-800 border border-gray-700 rounded-lg px-3 py-2.5 text-sm text-white outline-none focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1.5">
                        Due Date *
                    </label>
                    <input type="date" name="due_date"
                           value="{{ old('due_date', $project->due_date->format('Y-m-d')) }}"
                           class="w-full bg-gray-800 border border-gray-700 rounded-lg px-3 py-2.5 text-sm text-white outline-none focus:border-indigo-500">
                </div>
            </div>

            {{-- Status & Priority --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1.5">
                        Status *
                    </label>
                    <select name="status"
                            class="w-full bg-gray-800 border border-gray-700 rounded-lg px-3 py-2.5 text-sm text-white outline-none focus:border-indigo-500">
                        @foreach(['pending','in_progress','on_hold','completed','cancelled'] as $s)
                        <option value="{{ $s }}" {{ old('status', $project->status) === $s ? 'selected' : '' }}>
                            {{ ucwords(str_replace('_',' ',$s)) }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1.5">
                        Priority *
                    </label>
                    <select name="priority"
                            class="w-full bg-gray-800 border border-gray-700 rounded-lg px-3 py-2.5 text-sm text-white outline-none focus:border-indigo-500">
                        @foreach(['high','medium','low'] as $p)
                        <option value="{{ $p }}" {{ old('priority', $project->priority) === $p ? 'selected' : '' }}>
                            {{ ucfirst($p) }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Manager --}}
            <div>
                <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1.5">
                    Project Manager *
                </label>
                <select name="manager_id"
                        class="w-full bg-gray-800 border border-gray-700 rounded-lg px-3 py-2.5 text-sm text-white outline-none focus:border-indigo-500">
                    @foreach($managers as $manager)
                    <option value="{{ $manager->id }}" {{ old('manager_id', $project->manager_id) == $manager->id ? 'selected' : '' }}>
                        {{ $manager->name }}
                    </option>
                    @endforeach
                </select>
            </div>

            {{-- Actions --}}
            <div class="flex items-center gap-3 pt-2 border-t border-gray-800">
                <button type="submit"
                        class="px-4 py-2 bg-indigo-600 text-white text-sm font-semibold rounded-lg hover:bg-indigo-700">
                    Save Changes
                </button>
                <a href="{{ route('projects.show', $project) }}"
                   class="px-4 py-2 bg-gray-800 text-gray-300 text-sm font-medium rounded-lg hover:bg-gray-700">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>


@extends('layouts.app')

@section('title', 'Create Project')
@section('page-title', 'Create Project')

@section('page-actions')
    <a href="{{ route('tasks.create') }}" 
       class="flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl transition-colors">
        + New Task
    </a>
@endsection

@section('content')

<div class="max-w-2xl mx-auto pt-4">

    <!-- Header -->
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('projects.index') }}" 
           class="flex items-center gap-2 text-gray-400 hover:text-white transition-colors">
            ← Back
        </a>
        <h1 class="text-2xl font-semibold text-white">New Project</h1>
    </div>

    <!-- Compact Form Card -->
    <div class="bg-[#1F2937] border border-gray-700 rounded-2xl p-6">
        <form action="{{ route('projects.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Project Name -->
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-1.5">
                    PROJECT NAME <span class="text-red-500">*</span>
                </label>
                <input type="text" name="name" value="{{ old('name') }}"
                       placeholder="e.g. Website Redesign"
                       class="w-full bg-[#374151] border border-gray-600 rounded-xl px-4 py-3 text-sm text-white placeholder-gray-500 focus:border-indigo-500 focus:outline-none transition-all @error('name') border-red-500 @enderror">
                @error('name')<p class="mt-1 text-xs text-red-400">{{ $message }}</p>@enderror
            </div>

            <!-- Description -->
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-1.5">DESCRIPTION</label>
                <textarea name="description" rows="3" 
                          placeholder="Project details..."
                          class="w-full bg-[#374151] border border-gray-600 rounded-xl px-4 py-3 text-sm text-white placeholder-gray-500 focus:border-indigo-500 focus:outline-none resize-y transition-all @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                @error('description')<p class="mt-1 text-xs text-red-400">{{ $message }}</p>@enderror
            </div>

            <!-- Dates -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1.5">
                        START DATE <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="start_date" value="{{ old('start_date') }}"
                           class="w-full bg-[#374151] border border-gray-600 rounded-xl px-4 py-3 text-sm text-white focus:border-indigo-500 focus:outline-none @error('start_date') border-red-500 @enderror">
                    @error('start_date')<p class="mt-1 text-xs text-red-400">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1.5">
                        DUE DATE <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="due_date" value="{{ old('due_date') }}"
                           class="w-full bg-[#374151] border border-gray-600 rounded-xl px-4 py-3 text-sm text-white focus:border-indigo-500 focus:outline-none @error('due_date') border-red-500 @enderror">
                    @error('due_date')<p class="mt-1 text-xs text-red-400">{{ $message }}</p>@enderror
                </div>
            </div>

            <!-- Status & Priority -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1.5">
                        STATUS <span class="text-red-500">*</span>
                    </label>
                    <select name="status"
                            class="w-full bg-[#374151] border border-gray-600 rounded-xl px-4 py-3 text-sm text-white focus:border-indigo-500 focus:outline-none @error('status') border-red-500 @enderror">
                        <option value="pending" {{ old('status', 'pending') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="in_progress" {{ old('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="on_hold" {{ old('status') == 'on_hold' ? 'selected' : '' }}>On Hold</option>
                        <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                    @error('status')<p class="mt-1 text-xs text-red-400">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1.5">
                        PRIORITY <span class="text-red-500">*</span>
                    </label>
                    <select name="priority"
                            class="w-full bg-[#374151] border border-gray-600 rounded-xl px-4 py-3 text-sm text-white focus:border-indigo-500 focus:outline-none @error('priority') border-red-500 @enderror">
                        <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low</option>
                        <option value="medium" {{ old('priority', 'medium') == 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High</option>
                    </select>
                    @error('priority')<p class="mt-1 text-xs text-red-400">{{ $message }}</p>@enderror
                </div>
            </div>

            <!-- Project Manager -->
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-1.5">
                    PROJECT MANAGER <span class="text-red-500">*</span>
                </label>
                <select name="manager_id"
                        class="w-full bg-[#374151] border border-gray-600 rounded-xl px-4 py-3 text-sm text-white focus:border-indigo-500 focus:outline-none @error('manager_id') border-red-500 @enderror">
                    <option value="">Select manager...</option>
                    @foreach($managers as $manager)
                        <option value="{{ $manager->id }}" {{ old('manager_id') == $manager->id ? 'selected' : '' }}>
                            {{ $manager->name }}
                        </option>
                    @endforeach
                </select>
                @error('manager_id')<p class="mt-1 text-xs text-red-400">{{ $message }}</p>@enderror
            </div>

            <!-- Buttons -->
            <div class="flex gap-3 pt-4">
                <button type="submit"
                        class="flex-1 bg-indigo-600 hover:bg-indigo-700 py-3 rounded-xl text-white font-semibold text-sm transition-all">
                    Create Project
                </button>
                <a href="{{ route('projects.index') }}"
                   class="flex-1 py-3 border border-gray-600 hover:bg-gray-800 rounded-xl font-medium text-center text-sm transition-all">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

@endsection
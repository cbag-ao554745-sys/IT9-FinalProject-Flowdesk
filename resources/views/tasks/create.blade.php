@extends('layouts.app')

@section('title', 'Create New Task')
@section('page-title', 'Create New Task')

@section('content')

<div class="max-w-2xl mx-auto pt-6 pb-12">

    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-semibold text-white">Create New Task</h1>
            <p class="text-gray-400 mt-1">Fill in the details below</p>
        </div>
        <a href="{{ route('tasks.index') }}" 
           class="flex items-center gap-2 text-gray-600 hover:text-white transition-colors">
            ← Back to Tasks
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-[#1F2937] border border-gray-700 rounded-3xl p-7 shadow-2xl">
        <form action="{{ route('tasks.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- Project -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-400 mb-2">
                        Project <span class="text-red-500">*</span>
                    </label>
                    <select name="project_id" required
                            class="w-full bg-[#334155] border border-gray-600 rounded-2xl px-5 py-3.5 text-white focus:border-violet-500 focus:outline-none transition-all">
                        <option value="">Select a Project</option>
                        @foreach($projects as $project)
                            <option value="{{ $project->id }}">{{ $project->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Task Title -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-400 mb-2">
                        Task Title <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="title" required
                           class="w-full bg-[#334155] border border-gray-600 rounded-2xl px-5 py-3.5 text-white focus:border-violet-500 focus:outline-none transition-all"
                           placeholder="Implement MFA with TOTP">
                </div>

                <!-- Description -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-400 mb-2">Description</label>
                    <textarea name="description" rows="4"
                              class="w-full bg-[#334155] border border-gray-600 rounded-3xl px-5 py-3.5 text-white focus:border-violet-500 focus:outline-none resize-y transition-all"
                              placeholder="Detailed description of the task..."></textarea>
                </div>

                <!-- Status & Priority -->
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">
                        Status <span class="text-red-500">*</span>
                    </label>
                    <select name="status" required
                            class="w-full bg-[#334155] border border-gray-600 rounded-2xl px-5 py-3.5 text-white focus:border-violet-500 focus:outline-none">
                        <option value="pending">Pending</option>
                        <option value="in_progress">In Progress</option>
                        <option value="on_hold">On Hold</option>
                        <option value="completed">Completed</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">
                        Priority <span class="text-red-500">*</span>
                    </label>
                    <select name="priority" required
                            class="w-full bg-[#334155] border border-gray-600 rounded-2xl px-5 py-3.5 text-white focus:border-violet-500 focus:outline-none">
                        <option value="low">Low</option>
                        <option value="medium" selected>Medium</option>
                        <option value="high">High</option>
                    </select>
                </div>

                <!-- Due Date -->
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">
                        Due Date <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="due_date" required
                           class="w-full bg-[#334155] border border-gray-600 rounded-2xl px-5 py-3.5 text-white focus:border-violet-500 focus:outline-none">
                </div>

                <!-- Assigned To -->
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">Assign To</label>
                    <select name="assigned_user_id"
                            class="w-full bg-[#334155] border border-gray-600 rounded-2xl px-5 py-3.5 text-white focus:border-violet-500 focus:outline-none">
                        <option value="">Unassigned</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-4 pt-6">
                <button type="submit"
                        class="flex-1 bg-violet-600 hover:bg-violet-700 py-4 rounded-2xl text-white font-semibold flex items-center justify-center gap-2 transition-all">
                    <i class="fa-solid fa-plus"></i>
                    Create Task
                </button>
                
                <a href="{{ route('tasks.index') }}" 
                   class="flex-1 py-4 border border-gray-600 hover:bg-gray-800 rounded-2xl font-medium text-center transition-all">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

@endsection
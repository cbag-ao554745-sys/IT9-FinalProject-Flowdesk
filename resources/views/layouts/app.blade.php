<!DOCTYPE html>
<header class="h-14 bg-gray-900 border-b border-gray-800 flex items-center px-6 gap-4">
    <h1 class="text-sm font-semibold text-white">@yield('page-title', 'Dashboard')</h1>
    
    <!-- This line must exist -->
    <div class="ml-auto flex items-center gap-3">
        @yield('page-actions')
    </div>
</header>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title', 'FlowDesk') — FlowDesk</title>
<script src="https://cdn.tailwindcss.com"></script>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<style>
    body { font-family: 'Segoe UI', sans-serif; }
    ::-webkit-scrollbar { width: 4px; }
    ::-webkit-scrollbar-thumb { background: #4f46e5; border-radius: 2px; }
</style>
</head>
<body class="bg-gray-950 text-gray-100 min-h-screen">

<div class="flex min-h-screen">

    {{-- Sidebar --}}
    <aside class="fixed h-screen left-0 top-0 w-56 bg-gray-900 border-r border-gray-800 flex flex-col flex-shrink-0">

        {{-- Logo --}}
        <div class="px-5 py-4 border-b border-gray-800">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg bg-indigo-600 flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M2 3h5v4H2zm7 0h5v4H9zM2 9h4v4H2zm6 1h2v1H8zm0 2h5v1H8zm0-4h3v1H8z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-bold text-white">FlowDesk</p>
                    <p class="text-xs text-indigo-400">Task Management</p>
                </div>
            </div>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 px-3 py-4 space-y-1">
            <a href="{{ route('dashboard') }}"
               class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm
               {{ request()->routeIs('dashboard') ? 'bg-indigo-600 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M1 1h6v6H1zm8 0h6v6H9zM1 9h6v6H1zm8 2h2v1H9zm0 2h4v1H9zm0-4h3v1H9z"/>
                </svg>
                Dashboard
            </a>

            <a href="{{ route('projects.index') }}"
               class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm
               {{ request()->routeIs('projects.*') ? 'bg-indigo-600 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M2 2h5a1 1 0 011 1v1h4a1 1 0 011 1v8a1 1 0 01-1 1H3a1 1 0 01-1-1V3a1 1 0 011-1zm1 4v7h9V6H3zm0-1V3h4v1H3z"/>
                </svg>
                Projects
            </a>

            <a href="{{ route('tasks.index') }}"
               class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm
               {{ request()->routeIs('tasks.*') ? 'bg-indigo-600 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M2 3h12v1H2zm0 3h12v1H2zm0 3h8v1H2zm0 3h6v1H2z"/>
                </svg>
                Tasks
            </a>

            <a href="{{ route('reports.index') }}"
               class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm
               {{ request()->routeIs('reports.*') ? 'bg-indigo-600 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M2 12V4l3 4 3-3 3 4 3-5v8H2z"/>
                </svg>
                Reports
            </a>
        </nav>

        {{-- User --}}
        <div class="px-3 py-4 border-t border-gray-800">
            <div class="flex items-center gap-2 px-2 py-2">
                <div class="w-8 h-8 rounded-lg bg-indigo-600 flex items-center justify-center text-xs font-bold text-white">
                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-medium text-white truncate">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-gray-500 capitalize">{{ str_replace('_', ' ', auth()->user()->role) }}</p>
                </div>
            </div>
            <form action="{{ route('logout') }}" method="POST" class="mt-2">
                @csrf
                <button type="submit"
                        class="w-full text-left flex items-center gap-3 px-3 py-2 rounded-lg text-sm text-red-400 hover:bg-gray-800">
                    Sign Out
                </button>
            </form>
        </div>
    </aside>

    {{-- Main --}}
    <div class="ml-56 flex-1 flex flex-col min-w-0">

        {{-- Topbar --}}
        <header class="h-14 bg-gray-900 border-b border-gray-800 flex items-center px-6 gap-4">
            <h1 class="text-sm font-semibold text-white">@yield('page-title', 'Dashboard')</h1>
            <div class="ml-auto flex items-center gap-3">
                @if(auth()->user()->role !== 'team_member')
                <a href="{{ route('tasks.create') }}"
                   class="flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white text-xs font-semibold rounded-lg hover:bg-indigo-700">
                    + New Task
                </a>
                @endif
            </div>
        </header>

        {{-- Flash Messages --}}
        @if(session('success'))
        <div class="mx-6 mt-4 bg-green-900 border border-green-700 text-green-300 text-sm rounded-lg px-4 py-3">
            {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="mx-6 mt-4 bg-red-900 border border-red-700 text-red-300 text-sm rounded-lg px-4 py-3">
            {{ session('error') }}
        </div>
        @endif

        {{-- Content --}}
        <main class="flex-1 p-6 overflow-y-auto">
            @yield('content')
        </main>
    </div>
</div>

</body>
</html>
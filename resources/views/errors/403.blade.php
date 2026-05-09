<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>403 — Forbidden | FlowDesk</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-950 min-h-screen flex items-center justify-center">
<div class="text-center">
    <div class="w-16 h-16 rounded-2xl bg-red-900 flex items-center justify-center mx-auto mb-5">
        <svg class="w-8 h-8 text-red-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/>
        </svg>
    </div>
    <p class="text-xs font-semibold text-red-400 uppercase tracking-widest mb-2">403 — Forbidden</p>
    <h1 class="text-2xl font-bold text-white mb-2">Access Denied</h1>
    <p class="text-sm text-gray-400 mb-6">You don't have permission to access this page.</p>
    <a href="{{ route('dashboard') }}" class="px-4 py-2 bg-indigo-600 text-white text-sm font-semibold rounded-lg hover:bg-indigo-700">
        Go to Dashboard
    </a>
</div>
</body>
</html>
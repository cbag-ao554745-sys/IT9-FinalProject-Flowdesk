<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>404 — Not Found | FlowDesk</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-950 min-h-screen flex items-center justify-center">
<div class="text-center">
    <div class="w-16 h-16 rounded-2xl bg-indigo-900 flex items-center justify-center mx-auto mb-5">
        <svg class="w-8 h-8 text-indigo-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 15.803 7.5 7.5 0 0015.803 15.803z"/>
        </svg>
    </div>
    <p class="text-xs font-semibold text-indigo-400 uppercase tracking-widest mb-2">404 — Not Found</p>
    <h1 class="text-2xl font-bold text-white mb-2">Page Not Found</h1>
    <p class="text-sm text-gray-400 mb-6">The page you're looking for doesn't exist.</p>
    <a href="{{ route('dashboard') }}" class="px-4 py-2 bg-indigo-600 text-white text-sm font-semibold rounded-lg hover:bg-indigo-700">
        Back to Dashboard
    </a>
</div>
</body>
</html>
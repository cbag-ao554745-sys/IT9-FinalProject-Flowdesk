<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sign In — FlowDesk</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-950 min-h-screen flex items-center justify-center px-4">

<div class="w-full max-w-sm">

    {{-- Logo --}}
    <div class="text-center mb-8">
        <div class="inline-flex items-center gap-3 mb-3">
            <div class="w-10 h-10 rounded-xl bg-indigo-600 flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M2 3h5v4H2zm7 0h5v4H9zM2 9h4v4H2zm6 1h2v1H8zm0 2h5v1H8zm0-4h3v1H8z"/>
                </svg>
            </div>
            <span class="text-2xl font-bold text-white">FlowDesk</span>
        </div>
        <p class="text-sm text-gray-400">Sign in to your account</p>
    </div>

    {{-- Card --}}
    <div class="bg-gray-900 rounded-2xl border border-gray-800 p-8">

        {{-- Errors --}}
        @if($errors->any())
        <div class="mb-4 bg-red-900 border border-red-700 rounded-lg px-4 py-3">
            @foreach($errors->all() as $error)
                <p class="text-sm text-red-300">{{ $error }}</p>
            @endforeach
        </div>
        @endif

        <form action="{{ route('login.post') }}" method="POST" class="space-y-5">
            @csrf

            {{-- Email --}}
            <div>
                <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1.5">
                    Email Address
                </label>
                <input type="email" name="email"
                       value="{{ old('email') }}"
                       required autofocus
                       placeholder="you@flowdesk.com"
                       class="w-full bg-gray-800 border border-gray-700 rounded-lg px-3 py-2.5 text-sm text-white placeholder-gray-500 outline-none focus:border-indigo-500">
            </div>

            {{-- Password --}}
            <div>
                <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1.5">
                    Password
                </label>
                <input type="password" name="password"
                       required
                       placeholder="••••••••"
                       class="w-full bg-gray-800 border border-gray-700 rounded-lg px-3 py-2.5 text-sm text-white placeholder-gray-500 outline-none focus:border-indigo-500">
            </div>

            {{-- Remember --}}
            <div class="flex items-center gap-2">
                <input type="checkbox" name="remember" id="remember"
                       class="rounded border-gray-600 text-indigo-600">
                <label for="remember" class="text-sm text-gray-400">Remember me</label>
            </div>

            {{-- Submit --}}
            <button type="submit"
                    class="w-full py-2.5 bg-indigo-600 text-white text-sm font-semibold rounded-lg hover:bg-indigo-700 transition-colors">
                Sign In
            </button>
        </form>
    </div>

    {{-- Demo Credentials --}}
    <div class="mt-6 bg-gray-900 rounded-xl border border-gray-800 p-4">
        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Demo Accounts</p>
        <div class="space-y-1 text-xs text-gray-400">
            <div class="flex justify-between">
                <span class="text-gray-500">Admin</span>
                <span>admin@flowdesk.com / Admin@12345</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Manager</span>
                <span>alex.mendoza@flowdesk.com / Manager@12345</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Member</span>
                <span>sara.kim@flowdesk.com / Member@12345</span>
            </div>
        </div>
    </div>
</div>

</body>
</html>
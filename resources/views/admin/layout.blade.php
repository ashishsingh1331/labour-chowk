<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Admin' }} · Labour Chowk</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gray-50 text-gray-900">
<a class="sr-only focus:not-sr-only focus:absolute focus:top-2 focus:left-2 bg-white border px-3 py-2 rounded"
   href="#main">
    Skip to content
</a>

<header class="border-b bg-white">
    <div class="mx-auto max-w-3xl px-4 py-3 flex items-center justify-between gap-3">
        <div class="min-w-0">
            <div class="text-sm font-semibold truncate">Labour Chowk · Admin</div>
            <div class="text-xs text-gray-600 truncate">{{ $subtitle ?? '' }}</div>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('home') }}"
               class="text-sm font-medium text-gray-700 hover:text-gray-900 underline">
                View Site
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="text-sm font-medium text-gray-700 hover:text-gray-900 underline">
                    Logout
                </button>
            </form>
        </div>
    </div>

    <nav class="mx-auto max-w-3xl px-4 pb-3">
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.labourers.index') }}"
               class="inline-flex items-center rounded-full px-3 py-1 text-sm border
               {{ request()->routeIs('admin.labourers.*') ? 'bg-gray-900 text-white border-gray-900' : 'bg-white text-gray-800 border-gray-300' }}">
                Labourers
            </a>
            <a href="{{ route('admin.availability.today') }}"
               class="inline-flex items-center rounded-full px-3 py-1 text-sm border
               {{ request()->routeIs('admin.availability.*') ? 'bg-gray-900 text-white border-gray-900' : 'bg-white text-gray-800 border-gray-300' }}">
                Availability (Today)
            </a>
            <a href="{{ route('admin.areas.index') }}"
               class="inline-flex items-center rounded-full px-3 py-1 text-sm border
               {{ request()->routeIs('admin.areas.*') ? 'bg-gray-900 text-white border-gray-900' : 'bg-white text-gray-800 border-gray-300' }}">
                Areas
            </a>
            <a href="{{ route('admin.skills.index') }}"
               class="inline-flex items-center rounded-full px-3 py-1 text-sm border
               {{ request()->routeIs('admin.skills.*') ? 'bg-gray-900 text-white border-gray-900' : 'bg-white text-gray-800 border-gray-300' }}">
                Skills
            </a>
        </div>
    </nav>
</header>

<main id="main" class="mx-auto max-w-3xl px-4 py-6">
    @if (session('status'))
        <div class="mb-4 rounded border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-900">
            {{ session('status') }}
        </div>
    @endif

    {{ $slot }}
</main>
</body>
</html>



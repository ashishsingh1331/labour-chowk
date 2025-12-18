<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daily Labour Finder · Labour Chowk</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gray-50 text-gray-900">
<a class="sr-only focus:not-sr-only focus:absolute focus:top-2 focus:left-2 bg-white border px-3 py-2 rounded"
   href="#main">
    Skip to content
</a>

<header class="border-b bg-white">
    <div class="mx-auto max-w-3xl px-4 py-3">
        <div class="flex items-center justify-between gap-3 mb-2">
            <h1 class="text-xl font-semibold">Daily Labour Finder</h1>
            <nav class="flex items-center gap-3">
                <a href="{{ route('home') }}" class="text-sm font-medium text-gray-700 hover:text-gray-900 underline">
                    Home
                </a>
                @auth
                    <a href="{{ route('admin.labourers.index') }}" class="text-sm font-medium text-gray-700 hover:text-gray-900 underline">
                        Admin
                    </a>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-medium text-gray-700 hover:text-gray-900 underline">
                        Admin Login
                    </a>
                @endauth
            </nav>
        </div>
        <p class="text-sm text-gray-700">
            Find labourers available today ({{ $today }}). Tap to call immediately.
        </p>
    </div>
</header>

<main id="main" class="mx-auto max-w-3xl px-4 py-6">
    <form method="GET" action="{{ route('browse') }}" class="space-y-4">
        <div>
            <label class="block text-sm font-medium" for="area">Area</label>
            <select id="area" name="area"
                    class="mt-1 w-full rounded border-gray-300 focus:border-gray-900 focus:ring-gray-900"
                    required>
                <option value="">Select an area</option>
                @foreach ($areas as $area)
                    <option value="{{ $area->id }}" @selected((int) $areaId === $area->id)>{{ $area->name }}</option>
                @endforeach
            </select>
            <div class="mt-1 text-xs text-gray-600">No GPS tracking. Areas are admin-maintained.</div>
        </div>

        <div>
            <label class="block text-sm font-medium" for="q">Search by name (optional)</label>
            <input id="q" name="q" value="{{ $q }}"
                   class="mt-1 w-full rounded border-gray-300 focus:border-gray-900 focus:ring-gray-900"
                   placeholder="e.g., Ramesh" />
        </div>

        <div>
            <div class="text-sm font-medium">Skills (optional)</div>
            <div class="mt-2 flex flex-wrap gap-2">
                @foreach ($skills as $skill)
                    @php($checked = in_array($skill->id, $skillIds, true))
                    <label class="inline-flex items-center gap-2 rounded-full border px-3 py-2 text-sm bg-white">
                        <input type="checkbox" name="skills[]" value="{{ $skill->id }}" @checked($checked) />
                        <span>{{ $skill->name }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        <div class="flex items-center gap-2">
            <button type="submit" class="rounded bg-gray-900 px-4 py-2 text-sm font-medium text-white">
                Show available today
            </button>
            <a href="{{ route('browse') }}" class="text-sm underline text-gray-700">Reset</a>
            <a href="{{ route('home') }}" class="text-sm underline text-gray-700">Home</a>
        </div>
    </form>

    @if ($results)
        <section class="mt-6">
            <h2 class="text-lg font-semibold mb-4">Filtered Results</h2>
            @if ($results->count() === 0)
                <div class="rounded border bg-white p-6 text-sm text-gray-700">
                    No labourers are marked available today in this area.
                    Try another area or remove filters.
                </div>
            @else
                <div class="space-y-3">
                    @foreach ($results as $labourer)
                        @include('browse._labourer-card', ['labourer' => $labourer])
                    @endforeach
                </div>

                <div class="mt-6">
                    {{ $results->links() }}
                </div>
            @endif
        </section>
    @endif

    <section class="mt-8">
        <h2 class="text-lg font-semibold mb-4">All Available Today</h2>
        @if ($allAvailable && $allAvailable->count() > 0)
            <div class="space-y-3">
                @foreach ($allAvailable as $labourer)
                    @include('browse._labourer-card', ['labourer' => $labourer])
                @endforeach
            </div>

            <div class="mt-6">
                {{ $allAvailable->links() }}
            </div>
        @else
            <div class="rounded border bg-white p-6 text-sm text-gray-700">
                No labourers are marked available today.
            </div>
        @endif
    </section>
</main>
</body>
</html>



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
    <div class="mx-auto max-w-3xl px-4 py-4">
        <h1 class="text-xl font-semibold">Daily Labour Finder</h1>
        <p class="mt-1 text-sm text-gray-700">
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

    <section class="mt-6">
        @if (!$areaId)
            <div class="rounded border bg-white p-6 text-sm text-gray-700">
                Select an area to see labourers available today.
            </div>
        @elseif ($results && $results->count() === 0)
            <div class="rounded border bg-white p-6 text-sm text-gray-700">
                No labourers are marked available today in this area.
                Try another area or remove filters.
            </div>
        @elseif ($results)
            <div class="space-y-3">
                @foreach ($results as $labourer)
                    <article class="rounded border bg-white p-4">
                        <div class="flex items-start gap-3">
                            <div class="h-14 w-14 shrink-0 overflow-hidden rounded bg-gray-200">
                                @if ($labourer->photo_path)
                                    <img src="{{ asset('storage/'.$labourer->photo_path) }}" alt=""
                                         class="h-full w-full object-cover" />
                                @endif
                            </div>

                            <div class="min-w-0 flex-1">
                                <div class="font-semibold truncate">{{ $labourer->full_name }}</div>
                                @if ($labourer->skills->count())
                                    <div class="mt-2 flex flex-wrap gap-1">
                                        @foreach ($labourer->skills as $skill)
                                            <span class="rounded-full border border-gray-300 bg-white px-2 py-0.5 text-xs text-gray-800">
                                                {{ $skill->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif

                                <div class="mt-3">
                                    <a href="tel:{{ $labourer->phone_e164 }}"
                                       class="inline-flex w-full items-center justify-center rounded bg-green-700 px-4 py-3 text-sm font-semibold text-white"
                                       aria-label="Call {{ $labourer->full_name }}">
                                        Call now
                                    </a>
                                    <div class="mt-1 text-xs text-gray-600">
                                        Phone: {{ $labourer->phone_e164 }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $results->links() }}
            </div>
        @endif
    </section>
</main>
</body>
</html>



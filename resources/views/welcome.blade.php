<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Labour Chowk · Daily Labour Finder</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gray-50 text-gray-900">
<a class="sr-only focus:not-sr-only focus:absolute focus:top-2 focus:left-2 bg-white border px-3 py-2 rounded"
   href="#main">
    Skip to content
</a>

<header class="border-b bg-white">
    <div class="mx-auto max-w-3xl px-4 py-4">
        <h1 class="text-xl font-semibold">Labour Chowk</h1>
        <p class="mt-1 text-sm text-gray-700">Daily Labour Finder</p>
    </div>
</header>

<main id="main" class="mx-auto max-w-3xl px-4 py-8">
    <div class="space-y-6">
        <section class="rounded border bg-white p-6">
            <h2 class="text-lg font-semibold mb-3">What is Labour Chowk?</h2>
            <p class="text-sm text-gray-800 leading-relaxed mb-3">
                In many areas, finding daily labour is difficult. Contractors and individuals have to go to the chowk early in the morning and find labour manually. Labourers also waste time waiting without guarantee of work.
            </p>
            <p class="text-sm text-gray-800 leading-relaxed">
                <strong>Labour Chowk</strong> helps hirers quickly discover available labourers in a specific area for <strong>today</strong> and contact them immediately via phone call.
            </p>
        </section>

        <section class="rounded border bg-white p-6">
            <h2 class="text-lg font-semibold mb-3">How it works</h2>
            <ol class="space-y-3 text-sm text-gray-800">
                <li class="flex gap-3">
                    <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-gray-900 text-xs font-semibold text-white">1</span>
                    <span>Select an area (chowk/locality) where you need labour.</span>
                </li>
                <li class="flex gap-3">
                    <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-gray-900 text-xs font-semibold text-white">2</span>
                    <span>Optionally filter by skills (e.g., masonry, carpentry, painting).</span>
                </li>
                <li class="flex gap-3">
                    <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-gray-900 text-xs font-semibold text-white">3</span>
                    <span>See labourers marked as <strong>available today</strong> in that area.</span>
                </li>
                <li class="flex gap-3">
                    <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-gray-900 text-xs font-semibold text-white">4</span>
                    <span>Tap <strong>Call now</strong> to contact them directly.</span>
                </li>
            </ol>
        </section>

        <section class="rounded border bg-white p-6">
            <h2 class="text-lg font-semibold mb-3">What we don't do</h2>
            <ul class="space-y-2 text-sm text-gray-800">
                <li class="flex items-start gap-2">
                    <span class="text-gray-400">•</span>
                    <span>No payments or booking system — you call and arrange directly.</span>
                </li>
                <li class="flex items-start gap-2">
                    <span class="text-gray-400">•</span>
                    <span>No ratings or reviews — simple contact information only.</span>
                </li>
                <li class="flex items-start gap-2">
                    <span class="text-gray-400">•</span>
                    <span>No chat or messaging — phone calls only.</span>
                </li>
                <li class="flex items-start gap-2">
                    <span class="text-gray-400">•</span>
                    <span>No GPS tracking — areas are admin-maintained lists.</span>
                </li>
            </ul>
        </section>

        <div class="pt-4">
            <a href="{{ route('browse') }}"
               class="block w-full rounded bg-gray-900 px-6 py-4 text-center text-sm font-semibold text-white">
                Find labourers available today →
            </a>
        </div>
    </div>
</main>
</body>
</html>

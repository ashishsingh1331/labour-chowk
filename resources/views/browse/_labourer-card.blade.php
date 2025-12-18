@php
    /** @var \App\Models\Labourer $labourer */
@endphp
<article class="rounded border bg-white p-4">
    <div class="flex items-start gap-3">
        <div class="h-14 w-14 shrink-0 overflow-hidden rounded bg-gray-200">
            @if ($labourer->photo_path)
                <img src="{{ asset('storage/'.$labourer->photo_path) }}" alt=""
                     class="h-full w-full object-cover" />
            @else
                @php
                    $initials = strtoupper(substr($labourer->full_name, 0, 2));
                    $avatarUrl = 'https://ui-avatars.com/api/?name=' . urlencode($labourer->full_name) . '&size=56&background=6366f1&color=ffffff&bold=true';
                @endphp
                <img src="{{ $avatarUrl }}" alt="{{ $labourer->full_name }}"
                     class="h-full w-full object-cover" />
            @endif
        </div>

        <div class="min-w-0 flex-1">
            <div class="flex items-start justify-between gap-2">
                <div class="min-w-0 flex-1">
                    <div class="font-semibold truncate">{{ $labourer->full_name }}</div>
                    @if ($labourer->area)
                        <div class="mt-1 text-xs text-gray-600">{{ $labourer->area->name }}</div>
                    @endif
                </div>
            </div>

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


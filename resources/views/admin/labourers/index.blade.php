<x-admin-layout title="Labourers" subtitle="Search, edit, and manage labourer profiles">
    <div class="flex items-center justify-between gap-3 mb-4">
        <h1 class="text-lg font-semibold">Labourers</h1>
        <a href="{{ route('admin.labourers.create') }}"
           class="inline-flex items-center rounded bg-gray-900 px-3 py-2 text-sm font-medium text-white">
            Add labourer
        </a>
    </div>

    <form method="GET" action="{{ route('admin.labourers.index') }}" class="mb-4 space-y-3">
        <div>
            <label class="block text-sm font-medium" for="q">Search</label>
            <input id="q" name="q" value="{{ $q }}"
                   class="mt-1 w-full rounded border-gray-300 focus:border-gray-900 focus:ring-gray-900"
                   placeholder="Name or phone (e.g., +91...)" />
        </div>

        <div>
            <label class="block text-sm font-medium" for="area_id">Area</label>
            <select id="area_id" name="area_id"
                    class="mt-1 w-full rounded border-gray-300 focus:border-gray-900 focus:ring-gray-900">
                <option value="">All areas</option>
                @foreach ($areas as $area)
                    <option value="{{ $area->id }}" @selected((int) $areaId === $area->id)>
                        {{ $area->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="flex items-center gap-2">
            <button type="submit"
                    class="rounded bg-gray-900 px-3 py-2 text-sm font-medium text-white">
                Apply
            </button>
            <a href="{{ route('admin.labourers.index') }}" class="text-sm underline text-gray-700">
                Reset
            </a>
        </div>
    </form>

    <div class="space-y-3">
        @forelse ($labourers as $labourer)
            <div class="rounded border bg-white p-4">
                <div class="flex items-start gap-3">
                    <div class="h-12 w-12 shrink-0 overflow-hidden rounded bg-gray-200">
                        @if ($labourer->photo_path)
                            <img src="{{ asset('storage/'.$labourer->photo_path) }}" alt=""
                                 class="h-full w-full object-cover" />
                        @else
                            @php
                                $avatarUrl = 'https://ui-avatars.com/api/?name=' . urlencode($labourer->full_name) . '&size=48&background=6366f1&color=ffffff&bold=true';
                            @endphp
                            <img src="{{ $avatarUrl }}" alt="{{ $labourer->full_name }}"
                                 class="h-full w-full object-cover" />
                        @endif
                    </div>

                    <div class="min-w-0 flex-1">
                        <div class="flex items-center justify-between gap-2">
                            <div class="min-w-0">
                                <div class="font-semibold truncate">{{ $labourer->full_name }}</div>
                                <div class="text-sm text-gray-700 truncate">
                                    {{ $labourer->phone_e164 }} · {{ $labourer->area?->name ?? '—' }}
                                </div>
                            </div>
                            <div class="text-xs">
                                @if ($labourer->is_active)
                                    <span class="rounded-full bg-green-100 text-green-900 px-2 py-1">Active</span>
                                @else
                                    <span class="rounded-full bg-gray-100 text-gray-800 px-2 py-1">Inactive</span>
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

                        <div class="mt-3 flex items-center gap-3">
                            <a href="{{ route('admin.labourers.edit', $labourer) }}"
                               class="text-sm font-medium underline">
                                Edit
                            </a>

                            <form method="POST" action="{{ route('admin.labourers.destroy', $labourer) }}"
                                  onsubmit="return confirm('Delete this labourer?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-sm font-medium text-red-700 underline">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="rounded border bg-white p-6 text-sm text-gray-700">
                No labourers found.
            </div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $labourers->links() }}
    </div>
</x-admin-layout>



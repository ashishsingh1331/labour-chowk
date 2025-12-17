<x-admin-layout title="Availability (Today)" subtitle="Mark labourers available for {{ $today->toDateString() }}">
    <div class="flex items-center justify-between gap-3 mb-4">
        <h1 class="text-lg font-semibold">Availability (Today)</h1>
        <div class="text-xs text-gray-700">{{ $today->toDateString() }}</div>
    </div>

    <form method="GET" action="{{ route('admin.availability.today') }}" class="mb-4 space-y-3">
        <div>
            <label class="block text-sm font-medium" for="q">Search</label>
            <input id="q" name="q" value="{{ $q }}"
                   class="mt-1 w-full rounded border-gray-300 focus:border-gray-900 focus:ring-gray-900"
                   placeholder="Name or phone" />
        </div>

        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
            <div>
                <label class="block text-sm font-medium" for="area_id">Area</label>
                <select id="area_id" name="area_id"
                        class="mt-1 w-full rounded border-gray-300 focus:border-gray-900 focus:ring-gray-900">
                    <option value="">All areas</option>
                    @foreach ($areas as $area)
                        <option value="{{ $area->id }}" @selected((int) $areaId === $area->id)>{{ $area->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium" for="skill_id">Skill</label>
                <select id="skill_id" name="skill_id"
                        class="mt-1 w-full rounded border-gray-300 focus:border-gray-900 focus:ring-gray-900">
                    <option value="">All skills</option>
                    @foreach ($skills as $skill)
                        <option value="{{ $skill->id }}" @selected((int) $skillId === $skill->id)>{{ $skill->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <button type="submit" class="rounded bg-gray-900 px-3 py-2 text-sm font-medium text-white">Apply</button>
            <a href="{{ route('admin.availability.today') }}" class="text-sm underline text-gray-700">Reset</a>
        </div>
    </form>

    <form method="POST" action="{{ route('admin.availability.today.store') }}">
        @csrf

        <div class="sticky top-0 z-10 -mx-4 px-4 py-3 bg-white border-y flex items-center justify-between gap-3">
            <div class="text-sm text-gray-800">
                Select labourers, then choose an action.
            </div>
            <div class="flex items-center gap-2">
                <button type="submit" name="action" value="mark_available"
                        class="rounded bg-green-700 px-3 py-2 text-sm font-medium text-white">
                    Mark available
                </button>
                <button type="submit" name="action" value="remove"
                        class="rounded border border-gray-300 px-3 py-2 text-sm font-medium text-gray-800">
                    Remove
                </button>
            </div>
        </div>

        @error('labourer_ids')
        <div class="mt-3 rounded border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
            {{ $message }}
        </div>
        @enderror

        <div class="mt-4 space-y-2">
            @forelse ($labourers as $labourer)
                @php($isAvailable = isset($availableIds[$labourer->id]))
                <label class="block rounded border bg-white p-4">
                    <div class="flex items-start gap-3">
                        <input type="checkbox" name="labourer_ids[]" value="{{ $labourer->id }}"
                               class="mt-1" />
                        <div class="min-w-0 flex-1">
                            <div class="flex items-center justify-between gap-2">
                                <div class="min-w-0">
                                    <div class="font-semibold truncate">{{ $labourer->full_name }}</div>
                                    <div class="text-sm text-gray-700 truncate">
                                        {{ $labourer->phone_e164 }} · {{ $labourer->area?->name ?? '—' }}
                                    </div>
                                </div>
                                <div class="text-xs shrink-0">
                                    @if ($isAvailable)
                                        <span class="rounded-full bg-green-100 text-green-900 px-2 py-1">Available</span>
                                    @else
                                        <span class="rounded-full bg-gray-100 text-gray-800 px-2 py-1">Not set</span>
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
                        </div>
                    </div>
                </label>
            @empty
                <div class="rounded border bg-white p-6 text-sm text-gray-700">
                    No labourers found.
                </div>
            @endforelse
        </div>

        <div class="mt-6">
            {{ $labourers->links() }}
        </div>
    </form>
</x-admin-layout>



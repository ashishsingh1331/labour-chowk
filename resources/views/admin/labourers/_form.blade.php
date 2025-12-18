@php
    /** @var \App\Models\Labourer|null $labourer */
    $selectedSkillIds = isset($labourer) ? $labourer->skills->pluck('id')->all() : [];
@endphp

<div class="space-y-4">
    <div>
        <label class="block text-sm font-medium" for="full_name">Full name</label>
        <input id="full_name" name="full_name" value="{{ old('full_name', $labourer->full_name ?? '') }}"
               class="mt-1 w-full rounded border-gray-300 focus:border-gray-900 focus:ring-gray-900"
               required />
        @error('full_name') <div class="mt-1 text-sm text-red-700">{{ $message }}</div> @enderror
    </div>

    <div>
        <label class="block text-sm font-medium" for="phone_e164">Phone (E.164)</label>
        <input id="phone_e164" name="phone_e164" value="{{ old('phone_e164', $labourer->phone_e164 ?? '') }}"
               class="mt-1 w-full rounded border-gray-300 focus:border-gray-900 focus:ring-gray-900"
               placeholder="+919876543210" required />
        <div class="mt-1 text-xs text-gray-600">Example: +91XXXXXXXXXX</div>
        @error('phone_e164') <div class="mt-1 text-sm text-red-700">{{ $message }}</div> @enderror
    </div>

    <div>
        <label class="block text-sm font-medium" for="area_id">Area</label>
        <select id="area_id" name="area_id"
                class="mt-1 w-full rounded border-gray-300 focus:border-gray-900 focus:ring-gray-900" required>
            <option value="">Select an area</option>
            @foreach ($areas as $area)
                <option value="{{ $area->id }}" @selected((int) old('area_id', $labourer->area_id ?? 0) === $area->id)>
                    {{ $area->name }}{{ $area->is_active ? '' : ' (inactive)' }}
                </option>
            @endforeach
        </select>
        @error('area_id') <div class="mt-1 text-sm text-red-700">{{ $message }}</div> @enderror
    </div>

    <div>
        <div class="text-sm font-medium">Skills</div>
        <div class="mt-2 grid grid-cols-2 gap-2">
            @foreach ($skills as $skill)
                <label class="flex items-center gap-2 rounded border p-2">
                    <input type="checkbox" name="skill_ids[]"
                           value="{{ $skill->id }}"
                           @checked(in_array($skill->id, old('skill_ids', $selectedSkillIds), true)) />
                    <span class="text-sm">{{ $skill->name }}{{ $skill->is_active ? '' : ' (inactive)' }}</span>
                </label>
            @endforeach
        </div>
        @error('skill_ids') <div class="mt-1 text-sm text-red-700">{{ $message }}</div> @enderror
    </div>

    <div>
        <label class="block text-sm font-medium" for="photo">Photo (optional)</label>
        <input id="photo" name="photo" type="file" accept="image/*"
               class="mt-1 w-full rounded border-gray-300 focus:border-gray-900 focus:ring-gray-900" />
        @error('photo') <div class="mt-1 text-sm text-red-700">{{ $message }}</div> @enderror

        @if (!empty($labourer?->photo_path))
            <div class="mt-2 flex items-center gap-3">
                <img src="{{ asset('storage/'.$labourer->photo_path) }}" alt=""
                     class="h-14 w-14 rounded object-cover border" />
                <div class="text-xs text-gray-700">Uploading a new file will replace the current photo.</div>
            </div>
        @endif
    </div>

    <div class="flex items-center gap-2">
        <input id="is_active" name="is_active" type="checkbox" value="1"
               @checked((bool) old('is_active', $labourer->is_active ?? true)) />
        <label for="is_active" class="text-sm font-medium">Active</label>
    </div>
</div>



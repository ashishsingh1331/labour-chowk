<x-admin-layout title="Areas" subtitle="Manage chowk/locality list used for browsing">
    <h1 class="text-lg font-semibold mb-4">Areas</h1>

    <div class="rounded border bg-white p-4 mb-4">
        <form method="POST" action="{{ route('admin.areas.store') }}" class="space-y-3">
            @csrf
            <div>
                <label class="block text-sm font-medium" for="name">Add area</label>
                <input id="name" name="name" value="{{ old('name') }}"
                       class="mt-1 w-full rounded border-gray-300 focus:border-gray-900 focus:ring-gray-900"
                       placeholder="e.g., Sector 12 Chowk" required />
                @error('name') <div class="mt-1 text-sm text-red-700">{{ $message }}</div> @enderror
            </div>
            <button type="submit" class="rounded bg-gray-900 px-3 py-2 text-sm font-medium text-white">
                Add
            </button>
        </form>
    </div>

    <div class="space-y-2">
        @foreach ($areas as $area)
            <div class="rounded border bg-white p-4 flex items-center justify-between gap-3">
                <div class="min-w-0">
                    <div class="font-medium truncate">{{ $area->name }}</div>
                    <div class="text-xs text-gray-700">
                        {{ $area->is_active ? 'Active' : 'Inactive' }}
                    </div>
                </div>

                <form method="POST" action="{{ route('admin.areas.update', $area) }}">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="is_active" value="{{ $area->is_active ? 0 : 1 }}">
                    <button type="submit"
                            class="rounded border px-3 py-2 text-sm font-medium
                            {{ $area->is_active ? 'border-gray-300 text-gray-800' : 'border-green-300 text-green-900' }}">
                        {{ $area->is_active ? 'Deactivate' : 'Activate' }}
                    </button>
                </form>
            </div>
        @endforeach
    </div>
</x-admin-layout>



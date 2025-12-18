<x-admin-layout title="Skills" subtitle="Manage work types used for filtering">
    <h1 class="text-lg font-semibold mb-4">Skills</h1>

    <div class="rounded border bg-white p-4 mb-4">
        <form method="POST" action="{{ route('admin.skills.store') }}" class="space-y-3">
            @csrf
            <div>
                <label class="block text-sm font-medium" for="name">Add skill</label>
                <input id="name" name="name" value="{{ old('name') }}"
                       class="mt-1 w-full rounded border-gray-300 focus:border-gray-900 focus:ring-gray-900"
                       placeholder="e.g., Mason" required />
                @error('name') <div class="mt-1 text-sm text-red-700">{{ $message }}</div> @enderror
            </div>
            <button type="submit" class="rounded bg-gray-900 px-3 py-2 text-sm font-medium text-white">
                Add
            </button>
        </form>
    </div>

    <div class="space-y-2">
        @foreach ($skills as $skill)
            <div class="rounded border bg-white p-4 flex items-center justify-between gap-3">
                <div class="min-w-0">
                    <div class="font-medium truncate">{{ $skill->name }}</div>
                    <div class="text-xs text-gray-700">
                        {{ $skill->is_active ? 'Active' : 'Inactive' }}
                    </div>
                </div>

                <form method="POST" action="{{ route('admin.skills.update', $skill) }}">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="is_active" value="{{ $skill->is_active ? 0 : 1 }}">
                    <button type="submit"
                            class="rounded border px-3 py-2 text-sm font-medium
                            {{ $skill->is_active ? 'border-gray-300 text-gray-800' : 'border-green-300 text-green-900' }}">
                        {{ $skill->is_active ? 'Deactivate' : 'Activate' }}
                    </button>
                </form>
            </div>
        @endforeach
    </div>
</x-admin-layout>



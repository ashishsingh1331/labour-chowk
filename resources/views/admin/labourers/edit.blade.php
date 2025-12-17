<x-admin-layout title="Edit labourer" subtitle="Update labourer details and skills">
    <div class="flex items-center justify-between gap-3 mb-4">
        <h1 class="text-lg font-semibold truncate">Edit labourer</h1>
        <a href="{{ route('admin.labourers.index') }}" class="text-sm underline text-gray-700">Back</a>
    </div>

    <form method="POST" action="{{ route('admin.labourers.update', $labourer) }}" enctype="multipart/form-data"
          class="space-y-4">
        @csrf
        @method('PUT')

        @include('admin.labourers._form', ['labourer' => $labourer])

        <div class="flex items-center justify-between gap-2">
            <button type="submit" class="rounded bg-gray-900 px-4 py-2 text-sm font-medium text-white">
                Save
            </button>
            <button type="submit"
                    form="delete-labourer"
                    class="text-sm font-medium text-red-700 underline">
                Delete
            </button>
        </div>

        <form id="delete-labourer" method="POST" action="{{ route('admin.labourers.destroy', $labourer) }}"
              onsubmit="return confirm('Delete this labourer?');">
                @csrf
                @method('DELETE')
        </form>
    </form>
</x-admin-layout>



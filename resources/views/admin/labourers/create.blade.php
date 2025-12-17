<x-admin-layout title="Add labourer" subtitle="Create a new labourer profile">
    <h1 class="text-lg font-semibold mb-4">Add labourer</h1>

    <form method="POST" action="{{ route('admin.labourers.store') }}" enctype="multipart/form-data"
          class="space-y-4">
        @csrf

        @include('admin.labourers._form', ['labourer' => null])

        <div class="flex items-center gap-2">
            <button type="submit" class="rounded bg-gray-900 px-4 py-2 text-sm font-medium text-white">
                Create
            </button>
            <a href="{{ route('admin.labourers.index') }}" class="text-sm underline text-gray-700">Cancel</a>
        </div>
    </form>
</x-admin-layout>



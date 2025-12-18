<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Dashboard') }}
            </h2>
            <nav class="flex items-center gap-4">
                <a href="{{ route('home') }}" class="text-sm font-medium text-gray-700 hover:text-gray-900 underline">
                    Home
                </a>
                <a href="{{ route('browse') }}" class="text-sm font-medium text-gray-700 hover:text-gray-900 underline">
                    Browse
                </a>
                @if (Auth::user()->is_admin ?? false)
                    <a href="{{ route('admin.labourers.index') }}" class="text-sm font-medium text-gray-700 hover:text-gray-900 underline">
                        Admin
                    </a>
                @endif
            </nav>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <p class="mb-4">{{ __("You're logged in!") }}</p>

                    @if (Auth::user()->is_admin ?? false)
                        <div class="mt-4 space-y-2">
                            <p class="text-sm font-medium text-gray-700">Admin Quick Links:</p>
                            <ul class="list-disc list-inside space-y-1 text-sm text-gray-600">
                                <li><a href="{{ route('admin.labourers.index') }}" class="underline hover:text-gray-900">Manage Labourers</a></li>
                                <li><a href="{{ route('admin.availability.today') }}" class="underline hover:text-gray-900">Manage Availability (Today)</a></li>
                                <li><a href="{{ route('admin.areas.index') }}" class="underline hover:text-gray-900">Manage Areas</a></li>
                                <li><a href="{{ route('admin.skills.index') }}" class="underline hover:text-gray-900">Manage Skills</a></li>
                            </ul>
                        </div>
                    @else
                        <div class="mt-4">
                            <p class="text-sm text-gray-600">You are logged in as a regular user. Admin access is required to manage labourers.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

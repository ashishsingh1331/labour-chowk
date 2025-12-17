@props(['title' => null, 'subtitle' => null])

@php($title = $title ?? 'Admin')

<x-slot name="header">
    {{-- intentionally unused --}}
</x-slot>

@include('admin.layout', [
    'title' => $title,
    'subtitle' => $subtitle,
    'slot' => $slot,
])



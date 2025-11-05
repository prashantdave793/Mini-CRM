@props(['status'])

@php
    $status = $status ?? 'warm';
    $classes = [
        'hot' => 'bg-red-100 text-red-800',
        'warm' => 'bg-indigo-100 text-indigo-800', // Indigo theme
        'cold' => 'bg-gray-100 text-gray-800',
    ][$status] ?? 'bg-gray-100 text-gray-800';
@endphp

<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $classes }}">
    {{ ucfirst($status) }}
</span>

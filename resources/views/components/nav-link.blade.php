@props(['active'])

@php
$classes = ($active ?? false)
            ? 'flex items-center px-2 py-2 text-sm font-medium rounded-md bg-white text-gray-900'
            : 'flex items-center px-2 py-2 text-sm font-medium rounded-md text-gray-300 hover:bg-gray-700 hover:text-white group';
@endphp
<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>

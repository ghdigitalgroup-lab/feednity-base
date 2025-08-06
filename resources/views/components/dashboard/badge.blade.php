{{--
    Dashboard Badge component

    Usage:
    <x-dashboard.badge type="info" size="sm">
        New
    </x-dashboard.badge>
--}}
@props([
    'type' => 'info',
    'size' => 'md'
])

@php
$typeClasses = [
    'info' => 'bg-blue-100 text-blue-800',
    'success' => 'bg-green-100 text-green-800',
    'warning' => 'bg-yellow-100 text-yellow-800',
    'danger' => 'bg-red-100 text-red-800',
];

$sizeClasses = [
    'sm' => 'text-xs px-2 py-0.5',
    'md' => 'text-sm px-3 py-0.5',
    'lg' => 'text-base px-4 py-1',
];

$classes = ($typeClasses[$type] ?? $typeClasses['info']) . ' ' . ($sizeClasses[$size] ?? $sizeClasses['md']);
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center font-semibold rounded ' . $classes]) }}>
    {{ $slot }}
</span>

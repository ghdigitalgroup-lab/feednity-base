{{--
    Dashboard Alert component

    Usage:
    <x-dashboard.alert type="success" size="md">
        Operation successful.
    </x-dashboard.alert>
--}}
@props([
    'type' => 'info',
    'size' => 'md'
])

@php
$typeClasses = [
    'success' => 'bg-green-100 border-green-400 text-green-700',
    'danger' => 'bg-red-100 border-red-400 text-red-700',
    'warning' => 'bg-yellow-100 border-yellow-400 text-yellow-700',
    'info' => 'bg-blue-100 border-blue-400 text-blue-700',
];

$sizeClasses = [
    'sm' => 'px-2 py-2 text-sm',
    'md' => 'px-4 py-3',
    'lg' => 'px-6 py-4 text-lg',
];

$classes = ($typeClasses[$type] ?? $typeClasses['info']) . ' ' . ($sizeClasses[$size] ?? $sizeClasses['md']);
@endphp

<div {{ $attributes->merge(['class' => 'border-l-4 rounded ' . $classes]) }}>
    {{ $slot }}
</div>

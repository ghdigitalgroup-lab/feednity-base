{{--
    Dashboard Icon component

    Usage:
    <x-dashboard.icon name="check" size="md" color="green" class="mr-1" />
--}}
@props([
    'name' => 'info',
    'size' => 'md',
    'color' => 'gray'
])

@php
$paths = [
    'check' => 'M5 13l4 4L19 7',
    'x' => 'M6 18L18 6M6 6l12 12',
    'info' => 'M13 16h-1v-4h-1m1-4h.01',
];
$path = $paths[$name] ?? $paths['info'];

$sizeClasses = [
    'sm' => 'h-4 w-4',
    'md' => 'h-5 w-5',
    'lg' => 'h-6 w-6',
];
$sizeClass = $sizeClasses[$size] ?? $sizeClasses['md'];

$colorClasses = [
    'gray' => 'text-gray-500',
    'red' => 'text-red-500',
    'green' => 'text-green-500',
    'blue' => 'text-blue-500',
];
$colorClass = $colorClasses[$color] ?? $colorClasses['gray'];
@endphp

<svg {{ $attributes->merge(['class' => $sizeClass . ' ' . $colorClass]) }}
     fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $path }}" />
</svg>

{{--
    Dashboard Card component

    Usage:
    <x-dashboard.card type="basic" size="md" color="white" class="mt-4">
        Content goes here.
    </x-dashboard.card>
--}}
@props([
    'type' => 'basic',
    'size' => 'md',
    'color' => 'white'
])

@php
$colorClasses = [
    'white' => 'bg-white dark:bg-gray-800',
    'gray' => 'bg-gray-100 dark:bg-gray-700',
    'indigo' => 'bg-indigo-50 dark:bg-indigo-700',
];

$sizeClasses = [
    'sm' => 'p-4',
    'md' => 'p-6',
    'lg' => 'p-8',
];

$typeClasses = [
    'basic' => '',
    'bordered' => 'border border-gray-200 dark:border-gray-600',
    'hover' => 'transition hover:shadow-lg',
];

$classes = ($colorClasses[$color] ?? $colorClasses['white'])
    . ' ' . ($sizeClasses[$size] ?? $sizeClasses['md'])
    . ' rounded-lg shadow ' . ($typeClasses[$type] ?? $typeClasses['basic']);
@endphp

<div {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</div>

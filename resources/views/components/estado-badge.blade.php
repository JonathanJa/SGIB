@props(['estado'])

@php
    $classes = match ($estado) {
        \App\Models\Empleado::ESTADO_ACTIVO => 'bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-200',
        \App\Models\Empleado::ESTADO_INACTIVO => 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300',
        \App\Models\Empleado::ESTADO_LICENCIA => 'bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-200',
        default => 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300',
    };
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {$classes}"]) }}>
    {{ ucfirst($estado) }}
</span>

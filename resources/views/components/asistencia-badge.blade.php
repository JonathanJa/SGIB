@props(['estado'])

@php
    $classes = match ($estado) {
        \App\Models\Asistencia::ESTADO_PRESENTE => 'bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-200',
        \App\Models\Asistencia::ESTADO_AUSENTE => 'bg-red-100 text-red-800 dark:bg-red-900/40 dark:text-red-200',
        \App\Models\Asistencia::ESTADO_TARDANZA => 'bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-200',
        \App\Models\Asistencia::ESTADO_JUSTIFICADO => 'bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-200',
        default => 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300',
    };
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {$classes}"]) }}>
    {{ ucfirst($estado) }}
</span>

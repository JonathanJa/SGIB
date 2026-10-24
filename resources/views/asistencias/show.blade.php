@extends('layouts.app')

@section('title', 'Asistencia de '.$asistencia->empleado->nombre_completo)

@section('content')
    @php
        $detalles = [
            ['label' => 'Empleado', 'value' => $asistencia->empleado->nombre_completo],
            ['label' => 'DNI', 'value' => $asistencia->empleado->dni],
            ['label' => 'Fecha', 'value' => $asistencia->fecha?->format('d/m/Y')],
            ['label' => 'Hora de entrada', 'value' => $asistencia->hora_entrada_formateada ?: '—'],
            ['label' => 'Hora de salida', 'value' => $asistencia->hora_salida_formateada ?: '—'],
            ['label' => 'Observación', 'value' => $asistencia->observacion ?: '—'],
        ];
    @endphp

    <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
        <div>
            <a href="{{ route('asistencias.index') }}" class="text-sm font-medium text-indigo-600 transition hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                &larr; Volver a asistencias
            </a>
            <h1 class="mt-2 text-2xl font-bold tracking-tight">Asistencia de {{ $asistencia->empleado->nombre_completo }}</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                {{ $asistencia->fecha?->format('d/m/Y') }} · DNI {{ $asistencia->empleado->dni }}
            </p>
        </div>

        <div class="flex items-center gap-3">
            <a
                href="{{ route('asistencias.edit', $asistencia) }}"
                class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-950"
            >
                Editar
            </a>
            <form method="POST" action="{{ route('asistencias.destroy', $asistencia) }}" onsubmit="return confirm('¿Eliminar esta asistencia? Esta acción no se puede deshacer.')">
                @csrf
                @method('DELETE')
                <button
                    type="submit"
                    class="inline-flex items-center justify-center rounded-lg border border-red-200 bg-white px-4 py-2 text-sm font-semibold text-red-600 transition hover:bg-red-50 dark:border-red-900 dark:bg-gray-900 dark:text-red-400 dark:hover:bg-red-950/40"
                >
                    Eliminar
                </button>
            </form>
        </div>
    </div>

    <div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm lg:col-span-2 dark:border-gray-800 dark:bg-gray-900">
            <dl class="divide-y divide-gray-200 dark:divide-gray-800">
                @foreach ($detalles as $detalle)
                    <div class="grid grid-cols-1 gap-1 px-6 py-4 sm:grid-cols-3">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ $detalle['label'] }}</dt>
                        <dd class="text-sm text-gray-900 sm:col-span-2 dark:text-gray-100">{{ $detalle['value'] }}</dd>
                    </div>
                @endforeach
            </dl>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">
            <h2 class="text-sm font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Estado</h2>
            <div class="mt-3">
                <x-asistencia-badge :estado="$asistencia->estado" />
            </div>
            <p class="mt-4 text-sm text-gray-500 dark:text-gray-400">
                Registrada el {{ $asistencia->created_at?->format('d/m/Y H:i') }}.
            </p>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Última actualización: {{ $asistencia->updated_at?->format('d/m/Y H:i') }}.
            </p>
        </div>
    </div>
@endsection

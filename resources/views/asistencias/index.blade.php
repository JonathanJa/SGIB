@extends('layouts.app')

@section('title', 'Asistencias')

@section('content')
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight">Asistencias</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Registra y administra la asistencia diaria del personal.
            </p>
        </div>

        <a
            href="{{ route('asistencias.create') }}"
            class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-950"
        >
            + Nueva asistencia
        </a>
    </div>

    <div class="mt-6 overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900">
        @if ($asistencias->isEmpty())
            <div class="px-6 py-16 text-center">
                <p class="text-sm font-medium text-gray-900 dark:text-gray-100">Todavía no hay asistencias registradas.</p>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Registra la primera asistencia para comenzar.</p>
                <a
                    href="{{ route('asistencias.create') }}"
                    class="mt-4 inline-flex items-center justify-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-indigo-700"
                >
                    Registrar asistencia
                </a>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-800">
                    <thead class="bg-gray-50 dark:bg-gray-800/60">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Empleado</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Fecha</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Entrada</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Salida</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Estado</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                        @foreach ($asistencias as $asistencia)
                            <tr class="transition hover:bg-gray-50 dark:hover:bg-gray-800/40">
                                <td class="whitespace-nowrap px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $asistencia->empleado->nombre_completo }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">DNI {{ $asistencia->empleado->dni }}</div>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-600 dark:text-gray-300">
                                    {{ $asistencia->fecha?->format('d/m/Y') }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-600 dark:text-gray-300">
                                    {{ $asistencia->hora_entrada_formateada ?: '—' }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-600 dark:text-gray-300">
                                    {{ $asistencia->hora_salida_formateada ?: '—' }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4">
                                    <x-asistencia-badge :estado="$asistencia->estado" />
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-right text-sm font-medium">
                                    <div class="flex items-center justify-end gap-3">
                                        <a href="{{ route('asistencias.show', $asistencia) }}" class="text-indigo-600 transition hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                            Ver
                                        </a>
                                        <a href="{{ route('asistencias.edit', $asistencia) }}" class="text-gray-600 transition hover:text-gray-900 dark:text-gray-300 dark:hover:text-white">
                                            Editar
                                        </a>
                                        <form method="POST" action="{{ route('asistencias.destroy', $asistencia) }}" onsubmit="return confirm('¿Eliminar esta asistencia? Esta acción no se puede deshacer.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 transition hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                                Eliminar
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="border-t border-gray-200 px-6 py-4 dark:border-gray-800">
                {{ $asistencias->links() }}
            </div>
        @endif
    </div>
@endsection

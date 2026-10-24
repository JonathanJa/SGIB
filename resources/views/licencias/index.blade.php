@extends('layouts.app')

@section('title', 'Licencias')

@section('content')
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight">Licencias</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Administra las licencias y ausencias justificadas del personal.
            </p>
        </div>

        <a
            href="{{ route('licencias.create') }}"
            class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-950"
        >
            + Nueva licencia
        </a>
    </div>

    <div class="mt-6 overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900">
        @if ($licencias->isEmpty())
            <div class="px-6 py-16 text-center">
                <p class="text-sm font-medium text-gray-900 dark:text-gray-100">Todavía no hay licencias registradas.</p>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Registra la primera licencia para comenzar.</p>
                <a
                    href="{{ route('licencias.create') }}"
                    class="mt-4 inline-flex items-center justify-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-indigo-700"
                >
                    Registrar licencia
                </a>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-800">
                    <thead class="bg-gray-50 dark:bg-gray-800/60">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Empleado</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Tipo</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Período</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Duración</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Estado</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                        @foreach ($licencias as $licencia)
                            <tr class="transition hover:bg-gray-50 dark:hover:bg-gray-800/40">
                                <td class="whitespace-nowrap px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $licencia->empleado->nombre_completo }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">DNI {{ $licencia->empleado->dni }}</div>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-600 dark:text-gray-300">
                                    {{ $licencia->tipo_etiqueta }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-600 dark:text-gray-300">
                                    {{ $licencia->fecha_inicio?->format('d/m/Y') }} — {{ $licencia->fecha_fin?->format('d/m/Y') }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-600 dark:text-gray-300">
                                    {{ $licencia->duracion_dias }} {{ $licencia->duracion_dias === 1 ? 'día' : 'días' }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4">
                                    <x-licencia-badge :estado="$licencia->estado" />
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-right text-sm font-medium">
                                    <div class="flex items-center justify-end gap-3">
                                        <a href="{{ route('licencias.show', $licencia) }}" class="text-indigo-600 transition hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                            Ver
                                        </a>
                                        <a href="{{ route('licencias.edit', $licencia) }}" class="text-gray-600 transition hover:text-gray-900 dark:text-gray-300 dark:hover:text-white">
                                            Editar
                                        </a>
                                        <form method="POST" action="{{ route('licencias.destroy', $licencia) }}" onsubmit="return confirm('¿Eliminar esta licencia? Esta acción no se puede deshacer.')">
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
                {{ $licencias->links() }}
            </div>
        @endif
    </div>
@endsection

@extends('layouts.app')

@section('title', 'Editar licencia')

@section('content')
    <div class="mx-auto max-w-3xl">
        <div class="mb-6">
            <a href="{{ route('licencias.show', $licencia) }}" class="text-sm font-medium text-indigo-600 transition hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                &larr; Volver a la licencia
            </a>
            <h1 class="mt-2 text-2xl font-bold tracking-tight">Editar licencia</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Actualiza la licencia de {{ $licencia->empleado->nombre_completo }}.
            </p>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">
            @include('licencias._form', ['licencia' => $licencia])
        </div>
    </div>
@endsection

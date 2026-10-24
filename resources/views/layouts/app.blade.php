<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', 'Empleados') · {{ config('app.name', 'SGIB') }}</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-gray-100 text-gray-900 antialiased dark:bg-gray-950 dark:text-gray-100">
        <header class="border-b border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
            <div class="mx-auto flex max-w-7xl flex-col gap-4 px-4 py-4 sm:flex-row sm:items-center sm:justify-between sm:px-6 lg:px-8">
                <a href="{{ route('empleados.index') }}" class="flex items-center gap-3">
                    <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-indigo-600 text-sm font-bold text-white">SG</span>
                    <span>
                        <span class="block text-base font-semibold leading-tight">SGIB</span>
                        <span class="block text-xs text-gray-500 dark:text-gray-400">Gestión de personal</span>
                    </span>
                </a>

                <nav class="flex flex-wrap items-center gap-2">
                    <a
                        href="{{ route('empleados.index') }}"
                        @class([
                            'rounded-lg px-3 py-2 text-sm font-medium transition',
                            'bg-indigo-50 text-indigo-700 dark:bg-indigo-500/10 dark:text-indigo-300' => request()->routeIs('empleados.*'),
                            'text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800' => ! request()->routeIs('empleados.*'),
                        ])
                    >
                        Empleados
                    </a>
                    <a
                        href="{{ route('asistencias.index') }}"
                        @class([
                            'rounded-lg px-3 py-2 text-sm font-medium transition',
                            'bg-indigo-50 text-indigo-700 dark:bg-indigo-500/10 dark:text-indigo-300' => request()->routeIs('asistencias.*'),
                            'text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800' => ! request()->routeIs('asistencias.*'),
                        ])
                    >
                        Asistencias
                    </a>
                    <a
                        href="{{ route('licencias.index') }}"
                        @class([
                            'rounded-lg px-3 py-2 text-sm font-medium transition',
                            'bg-indigo-50 text-indigo-700 dark:bg-indigo-500/10 dark:text-indigo-300' => request()->routeIs('licencias.*'),
                            'text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800' => ! request()->routeIs('licencias.*'),
                        ])
                    >
                        Licencias
                    </a>
                </nav>
            </div>
        </header>

        <main class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-6 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800 dark:border-green-800 dark:bg-green-900/40 dark:text-green-200" role="status">
                    {{ session('success') }}
                </div>
            @endif

            @yield('content')
        </main>
    </body>
</html>

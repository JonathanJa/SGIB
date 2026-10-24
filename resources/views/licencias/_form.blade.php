@php
    $licencia = $licencia ?? null;
    $esEdicion = $licencia !== null;

    $labelClasses = 'block text-sm font-medium text-gray-700 dark:text-gray-300';
    $inputClasses = 'mt-1 block w-full rounded-lg border bg-white px-3 py-2 text-sm text-gray-900 shadow-sm transition focus:outline-none focus:ring-2 dark:bg-gray-950 dark:text-gray-100';
    $normalInputClasses = "{$inputClasses} border-gray-300 focus:border-indigo-500 focus:ring-indigo-500/30 dark:border-gray-700";
    $errorInputClasses = "{$inputClasses} border-red-500 focus:border-red-500 focus:ring-red-500/30 dark:border-red-500";
@endphp

<form method="POST" action="{{ $esEdicion ? route('licencias.update', $licencia) : route('licencias.store') }}" class="space-y-6">
    @csrf

    @if ($esEdicion)
        @method('PUT')
    @endif

    <p class="text-sm text-gray-500 dark:text-gray-400">
        Los campos marcados con <span class="text-red-500">*</span> son obligatorios.
    </p>

    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
        <div class="sm:col-span-2">
            <label for="empleado_id" class="{{ $labelClasses }}">Empleado <span class="text-red-500">*</span></label>
            <select
                name="empleado_id"
                id="empleado_id"
                required
                class="{{ $errors->has('empleado_id') ? $errorInputClasses : $normalInputClasses }}"
            >
                <option value="">Selecciona un empleado</option>
                @foreach ($empleados as $empleado)
                    <option value="{{ $empleado->id }}" @selected((string) old('empleado_id', $licencia?->empleado_id) === (string) $empleado->id)>
                        {{ $empleado->nombre_completo }} — DNI {{ $empleado->dni }}
                    </option>
                @endforeach
            </select>
            @error('empleado_id')
                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="tipo" class="{{ $labelClasses }}">Tipo de licencia <span class="text-red-500">*</span></label>
            <select
                name="tipo"
                id="tipo"
                required
                class="{{ $errors->has('tipo') ? $errorInputClasses : $normalInputClasses }}"
            >
                @foreach (\App\Models\Licencia::TIPOS as $tipo)
                    <option value="{{ $tipo }}" @selected(old('tipo', $licencia?->tipo ?? \App\Models\Licencia::TIPO_VACACIONES) === $tipo)>
                        {{ \App\Models\Licencia::TIPO_ETIQUETAS[$tipo] }}
                    </option>
                @endforeach
            </select>
            @error('tipo')
                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="estado" class="{{ $labelClasses }}">Estado <span class="text-red-500">*</span></label>
            <select
                name="estado"
                id="estado"
                required
                class="{{ $errors->has('estado') ? $errorInputClasses : $normalInputClasses }}"
            >
                @foreach (\App\Models\Licencia::ESTADOS as $estado)
                    <option value="{{ $estado }}" @selected(old('estado', $licencia?->estado ?? \App\Models\Licencia::ESTADO_PENDIENTE) === $estado)>
                        {{ ucfirst($estado) }}
                    </option>
                @endforeach
            </select>
            @error('estado')
                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="fecha_inicio" class="{{ $labelClasses }}">Fecha de inicio <span class="text-red-500">*</span></label>
            <input
                type="date"
                name="fecha_inicio"
                id="fecha_inicio"
                value="{{ old('fecha_inicio', $licencia?->fecha_inicio?->format('Y-m-d') ?? now()->format('Y-m-d')) }}"
                required
                class="{{ $errors->has('fecha_inicio') ? $errorInputClasses : $normalInputClasses }}"
            >
            @error('fecha_inicio')
                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="fecha_fin" class="{{ $labelClasses }}">Fecha de fin <span class="text-red-500">*</span></label>
            <input
                type="date"
                name="fecha_fin"
                id="fecha_fin"
                value="{{ old('fecha_fin', $licencia?->fecha_fin?->format('Y-m-d') ?? now()->format('Y-m-d')) }}"
                required
                class="{{ $errors->has('fecha_fin') ? $errorInputClasses : $normalInputClasses }}"
            >
            @error('fecha_fin')
                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <div class="sm:col-span-2">
            <label for="motivo" class="{{ $labelClasses }}">Motivo</label>
            <textarea
                name="motivo"
                id="motivo"
                rows="3"
                maxlength="1000"
                class="{{ $errors->has('motivo') ? $errorInputClasses : $normalInputClasses }}"
            >{{ old('motivo', $licencia?->motivo) }}</textarea>
            @error('motivo')
                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="flex flex-col-reverse gap-3 border-t border-gray-200 pt-6 sm:flex-row sm:justify-end dark:border-gray-800">
        <a
            href="{{ $esEdicion ? route('licencias.show', $licencia) : route('licencias.index') }}"
            class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 dark:hover:bg-gray-800"
        >
            Cancelar
        </a>
        <button
            type="submit"
            class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900"
        >
            {{ $esEdicion ? 'Actualizar licencia' : 'Guardar licencia' }}
        </button>
    </div>
</form>

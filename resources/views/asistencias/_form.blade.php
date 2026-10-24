@php
    $asistencia = $asistencia ?? null;
    $esEdicion = $asistencia !== null;

    $labelClasses = 'block text-sm font-medium text-gray-700 dark:text-gray-300';
    $inputClasses = 'mt-1 block w-full rounded-lg border bg-white px-3 py-2 text-sm text-gray-900 shadow-sm transition focus:outline-none focus:ring-2 dark:bg-gray-950 dark:text-gray-100';
    $normalInputClasses = "{$inputClasses} border-gray-300 focus:border-indigo-500 focus:ring-indigo-500/30 dark:border-gray-700";
    $errorInputClasses = "{$inputClasses} border-red-500 focus:border-red-500 focus:ring-red-500/30 dark:border-red-500";
@endphp

<form method="POST" action="{{ $esEdicion ? route('asistencias.update', $asistencia) : route('asistencias.store') }}" class="space-y-6">
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
                    <option value="{{ $empleado->id }}" @selected((string) old('empleado_id', $asistencia?->empleado_id) === (string) $empleado->id)>
                        {{ $empleado->nombre_completo }} — DNI {{ $empleado->dni }}
                    </option>
                @endforeach
            </select>
            @error('empleado_id')
                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="fecha" class="{{ $labelClasses }}">Fecha <span class="text-red-500">*</span></label>
            <input
                type="date"
                name="fecha"
                id="fecha"
                value="{{ old('fecha', $asistencia?->fecha?->format('Y-m-d') ?? now()->format('Y-m-d')) }}"
                required
                class="{{ $errors->has('fecha') ? $errorInputClasses : $normalInputClasses }}"
            >
            @error('fecha')
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
                @foreach (\App\Models\Asistencia::ESTADOS as $estado)
                    <option value="{{ $estado }}" @selected(old('estado', $asistencia?->estado ?? \App\Models\Asistencia::ESTADO_PRESENTE) === $estado)>
                        {{ ucfirst($estado) }}
                    </option>
                @endforeach
            </select>
            @error('estado')
                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="hora_entrada" class="{{ $labelClasses }}">Hora de entrada</label>
            <input
                type="time"
                name="hora_entrada"
                id="hora_entrada"
                value="{{ old('hora_entrada', $asistencia?->hora_entrada_formateada) }}"
                class="{{ $errors->has('hora_entrada') ? $errorInputClasses : $normalInputClasses }}"
            >
            @error('hora_entrada')
                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="hora_salida" class="{{ $labelClasses }}">Hora de salida</label>
            <input
                type="time"
                name="hora_salida"
                id="hora_salida"
                value="{{ old('hora_salida', $asistencia?->hora_salida_formateada) }}"
                class="{{ $errors->has('hora_salida') ? $errorInputClasses : $normalInputClasses }}"
            >
            @error('hora_salida')
                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <div class="sm:col-span-2">
            <label for="observacion" class="{{ $labelClasses }}">Observación</label>
            <textarea
                name="observacion"
                id="observacion"
                rows="3"
                maxlength="1000"
                class="{{ $errors->has('observacion') ? $errorInputClasses : $normalInputClasses }}"
            >{{ old('observacion', $asistencia?->observacion) }}</textarea>
            @error('observacion')
                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="flex flex-col-reverse gap-3 border-t border-gray-200 pt-6 sm:flex-row sm:justify-end dark:border-gray-800">
        <a
            href="{{ $esEdicion ? route('asistencias.show', $asistencia) : route('asistencias.index') }}"
            class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 dark:hover:bg-gray-800"
        >
            Cancelar
        </a>
        <button
            type="submit"
            class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900"
        >
            {{ $esEdicion ? 'Actualizar asistencia' : 'Guardar asistencia' }}
        </button>
    </div>
</form>

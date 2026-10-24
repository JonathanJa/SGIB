@php
    $empleado = $empleado ?? null;
    $esEdicion = $empleado !== null;

    $labelClasses = 'block text-sm font-medium text-gray-700 dark:text-gray-300';
    $inputClasses = 'mt-1 block w-full rounded-lg border bg-white px-3 py-2 text-sm text-gray-900 shadow-sm transition focus:outline-none focus:ring-2 dark:bg-gray-950 dark:text-gray-100';
    $normalInputClasses = "{$inputClasses} border-gray-300 focus:border-indigo-500 focus:ring-indigo-500/30 dark:border-gray-700";
    $errorInputClasses = "{$inputClasses} border-red-500 focus:border-red-500 focus:ring-red-500/30 dark:border-red-500";
@endphp

<form method="POST" action="{{ $esEdicion ? route('empleados.update', $empleado) : route('empleados.store') }}" class="space-y-6">
    @csrf

    @if ($esEdicion)
        @method('PUT')
    @endif

    <p class="text-sm text-gray-500 dark:text-gray-400">
        Los campos marcados con <span class="text-red-500">*</span> son obligatorios.
    </p>

    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
        <div>
            <label for="dni" class="{{ $labelClasses }}">DNI <span class="text-red-500">*</span></label>
            <input
                type="text"
                name="dni"
                id="dni"
                value="{{ old('dni', $empleado?->dni) }}"
                required
                maxlength="20"
                autocomplete="off"
                class="{{ $errors->has('dni') ? $errorInputClasses : $normalInputClasses }}"
            >
            @error('dni')
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
                @foreach (\App\Models\Empleado::ESTADOS as $estado)
                    <option value="{{ $estado }}" @selected(old('estado', $empleado?->estado ?? \App\Models\Empleado::ESTADO_ACTIVO) === $estado)>
                        {{ ucfirst($estado) }}
                    </option>
                @endforeach
            </select>
            @error('estado')
                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="nombre" class="{{ $labelClasses }}">Nombre <span class="text-red-500">*</span></label>
            <input
                type="text"
                name="nombre"
                id="nombre"
                value="{{ old('nombre', $empleado?->nombre) }}"
                required
                maxlength="255"
                autocomplete="given-name"
                class="{{ $errors->has('nombre') ? $errorInputClasses : $normalInputClasses }}"
            >
            @error('nombre')
                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="apellido" class="{{ $labelClasses }}">Apellido <span class="text-red-500">*</span></label>
            <input
                type="text"
                name="apellido"
                id="apellido"
                value="{{ old('apellido', $empleado?->apellido) }}"
                required
                maxlength="255"
                autocomplete="family-name"
                class="{{ $errors->has('apellido') ? $errorInputClasses : $normalInputClasses }}"
            >
            @error('apellido')
                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="telefono" class="{{ $labelClasses }}">Teléfono</label>
            <input
                type="text"
                name="telefono"
                id="telefono"
                value="{{ old('telefono', $empleado?->telefono) }}"
                maxlength="30"
                autocomplete="tel"
                class="{{ $errors->has('telefono') ? $errorInputClasses : $normalInputClasses }}"
            >
            @error('telefono')
                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="fecha_nacimiento" class="{{ $labelClasses }}">Fecha de nacimiento</label>
            <input
                type="date"
                name="fecha_nacimiento"
                id="fecha_nacimiento"
                value="{{ old('fecha_nacimiento', $empleado?->fecha_nacimiento?->format('Y-m-d')) }}"
                max="{{ now()->format('Y-m-d') }}"
                class="{{ $errors->has('fecha_nacimiento') ? $errorInputClasses : $normalInputClasses }}"
            >
            @error('fecha_nacimiento')
                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="fecha_ingreso" class="{{ $labelClasses }}">Fecha de ingreso <span class="text-red-500">*</span></label>
            <input
                type="date"
                name="fecha_ingreso"
                id="fecha_ingreso"
                value="{{ old('fecha_ingreso', $empleado?->fecha_ingreso?->format('Y-m-d')) }}"
                required
                class="{{ $errors->has('fecha_ingreso') ? $errorInputClasses : $normalInputClasses }}"
            >
            @error('fecha_ingreso')
                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="salario" class="{{ $labelClasses }}">Salario <span class="text-red-500">*</span></label>
            <input
                type="number"
                name="salario"
                id="salario"
                value="{{ old('salario', $empleado?->salario) }}"
                required
                min="0"
                max="99999999.99"
                step="0.01"
                inputmode="decimal"
                class="{{ $errors->has('salario') ? $errorInputClasses : $normalInputClasses }}"
            >
            @error('salario')
                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <div class="sm:col-span-2">
            <label for="direccion" class="{{ $labelClasses }}">Dirección</label>
            <textarea
                name="direccion"
                id="direccion"
                rows="2"
                maxlength="255"
                autocomplete="street-address"
                class="{{ $errors->has('direccion') ? $errorInputClasses : $normalInputClasses }}"
            >{{ old('direccion', $empleado?->direccion) }}</textarea>
            @error('direccion')
                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="flex flex-col-reverse gap-3 border-t border-gray-200 pt-6 sm:flex-row sm:justify-end dark:border-gray-800">
        <a
            href="{{ $esEdicion ? route('empleados.show', $empleado) : route('empleados.index') }}"
            class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 dark:hover:bg-gray-800"
        >
            Cancelar
        </a>
        <button
            type="submit"
            class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900"
        >
            {{ $esEdicion ? 'Actualizar empleado' : 'Guardar empleado' }}
        </button>
    </div>
</form>

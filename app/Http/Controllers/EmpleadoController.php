<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEmpleadoRequest;
use App\Http\Requests\UpdateEmpleadoRequest;
use App\Models\Empleado;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class EmpleadoController extends Controller
{
    public function index(): View
    {
        $empleados = Empleado::query()
            ->orderBy('apellido')
            ->orderBy('nombre')
            ->paginate(10);

        return view('empleados.index', compact('empleados'));
    }

    public function create(): View
    {
        return view('empleados.create');
    }

    public function store(StoreEmpleadoRequest $request): RedirectResponse
    {
        Empleado::create($request->validated());

        return redirect()
            ->route('empleados.index')
            ->with('success', 'Empleado registrado correctamente.');
    }

    public function show(Empleado $empleado): View
    {
        return view('empleados.show', compact('empleado'));
    }

    public function edit(Empleado $empleado): View
    {
        return view('empleados.edit', compact('empleado'));
    }

    public function update(UpdateEmpleadoRequest $request, Empleado $empleado): RedirectResponse
    {
        $empleado->update($request->validated());

        return redirect()
            ->route('empleados.index')
            ->with('success', 'Empleado actualizado correctamente.');
    }

    public function destroy(Empleado $empleado): RedirectResponse
    {
        $empleado->delete();

        return redirect()
            ->route('empleados.index')
            ->with('success', 'Empleado eliminado correctamente.');
    }
}

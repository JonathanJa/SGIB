<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLicenciaRequest;
use App\Http\Requests\UpdateLicenciaRequest;
use App\Models\Empleado;
use App\Models\Licencia;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class LicenciaController extends Controller
{
    public function index(): View
    {
        $licencias = Licencia::query()
            ->with('empleado')
            ->orderByDesc('fecha_inicio')
            ->orderByDesc('id')
            ->paginate(10);

        return view('licencias.index', compact('licencias'));
    }

    public function create(): View
    {
        $empleados = Empleado::query()
            ->orderBy('apellido')
            ->orderBy('nombre')
            ->get();

        return view('licencias.create', compact('empleados'));
    }

    public function store(StoreLicenciaRequest $request): RedirectResponse
    {
        Licencia::create($request->validated());

        return redirect()
            ->route('licencias.index')
            ->with('success', 'Licencia registrada correctamente.');
    }

    public function show(Licencia $licencia): View
    {
        $licencia->load('empleado');

        return view('licencias.show', compact('licencia'));
    }

    public function edit(Licencia $licencia): View
    {
        $empleados = Empleado::query()
            ->orderBy('apellido')
            ->orderBy('nombre')
            ->get();

        return view('licencias.edit', compact('licencia', 'empleados'));
    }

    public function update(UpdateLicenciaRequest $request, Licencia $licencia): RedirectResponse
    {
        $licencia->update($request->validated());

        return redirect()
            ->route('licencias.index')
            ->with('success', 'Licencia actualizada correctamente.');
    }

    public function destroy(Licencia $licencia): RedirectResponse
    {
        $licencia->delete();

        return redirect()
            ->route('licencias.index')
            ->with('success', 'Licencia eliminada correctamente.');
    }
}

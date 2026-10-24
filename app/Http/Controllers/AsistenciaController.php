<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAsistenciaRequest;
use App\Http\Requests\UpdateAsistenciaRequest;
use App\Models\Asistencia;
use App\Models\Empleado;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AsistenciaController extends Controller
{
    public function index(): View
    {
        $asistencias = Asistencia::query()
            ->with('empleado') //genera una mensaje flash
            ->orderByDesc('fecha')
            ->orderByDesc('id')
            ->paginate(10);

        return view('asistencias.index', compact('asistencias'));
    }

    public function create(): View
    {
        $empleados = Empleado::query()
            ->orderBy('apellido')
            ->orderBy('nombre')
            ->get();

        return view('asistencias.create', compact('empleados'));
    }
    //StoreAsistenciaRequest --> Creamos un request en donde pasa la mayoria de los trabajos de validaciones, al generarlo ayudas al codigo a tenerlo mas limpio.
    public function store(StoreAsistenciaRequest $request): RedirectResponse
    {
        Asistencia::create($request->validated());

        return redirect()
            ->route('asistencias.index')
            ->with('success', 'Asistencia registrada correctamente.'); // paso y genera una respuesta flash exitosa
    }

    public function show(Asistencia $asistencia): View
    {
        $asistencia->load('empleado');

        return view('asistencias.show', compact('asistencia'));
    }

    public function edit(Asistencia $asistencia): View
    {
        $empleados = Empleado::query()
            ->orderBy('apellido')
            ->orderBy('nombre')
            ->get();

        return view('asistencias.edit', compact('asistencia', 'empleados'));
    }

    public function update(UpdateAsistenciaRequest $request, Asistencia $asistencia): RedirectResponse
    {
        $asistencia->update($request->validated());

        return redirect()
            ->route('asistencias.index')
            ->with('success', 'Asistencia actualizada correctamente.');
    }

    public function destroy(Asistencia $asistencia): RedirectResponse
    {
        $asistencia->delete();

        return redirect()
            ->route('asistencias.index')
            ->with('success', 'Asistencia eliminada correctamente.');
    }
}

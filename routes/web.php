<?php

use App\Http\Controllers\AsistenciaController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\LicenciaController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/empleados');

Route::resource('empleados', EmpleadoController::class);
Route::resource('asistencias', AsistenciaController::class);
Route::resource('licencias', LicenciaController::class);

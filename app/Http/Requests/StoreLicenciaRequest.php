<?php

namespace App\Http\Requests;

use App\Models\Licencia;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreLicenciaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'empleado_id' => ['required', 'integer', Rule::exists('empleados', 'id')],
            'tipo' => ['required', 'string', Rule::in(Licencia::TIPOS)],
            'fecha_inicio' => ['required', 'date'],
            'fecha_fin' => ['required', 'date', 'after_or_equal:fecha_inicio'],
            'motivo' => ['nullable', 'string', 'max:1000'],
            'estado' => ['required', 'string', Rule::in(Licencia::ESTADOS)],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'empleado_id.required' => 'El empleado es obligatorio.',
            'empleado_id.integer' => 'El empleado seleccionado no es válido.',
            'empleado_id.exists' => 'El empleado seleccionado no existe.',
            'tipo.required' => 'El tipo de licencia es obligatorio.',
            'tipo.in' => 'El tipo de licencia seleccionado no es válido.',
            'fecha_inicio.required' => 'La fecha de inicio es obligatoria.',
            'fecha_inicio.date' => 'La fecha de inicio no es válida.',
            'fecha_fin.required' => 'La fecha de fin es obligatoria.',
            'fecha_fin.date' => 'La fecha de fin no es válida.',
            'fecha_fin.after_or_equal' => 'La fecha de fin no puede ser anterior a la fecha de inicio.',
            'motivo.max' => 'El motivo no puede superar los 1000 caracteres.',
            'estado.required' => 'El estado es obligatorio.',
            'estado.in' => 'El estado seleccionado no es válido.',
        ];
    }
}

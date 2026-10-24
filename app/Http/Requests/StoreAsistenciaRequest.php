<?php

namespace App\Http\Requests;

use App\Models\Asistencia;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAsistenciaRequest extends FormRequest
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
            'fecha' => [
                'required',
                'date',
                Rule::unique('asistencias', 'fecha')
                    ->where(fn (Builder $query): Builder => $query->where('empleado_id', $this->input('empleado_id'))),
            ],
            'hora_entrada' => ['nullable', 'date_format:H:i', 'required_with:hora_salida'],
            'hora_salida' => ['nullable', 'date_format:H:i', 'after_or_equal:hora_entrada'],
            'estado' => ['required', 'string', Rule::in(Asistencia::ESTADOS)],
            'observacion' => ['nullable', 'string', 'max:1000'],
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
            'fecha.required' => 'La fecha es obligatoria.',
            'fecha.date' => 'La fecha no es válida.',
            'fecha.unique' => 'Ya existe una asistencia registrada para ese empleado en esa fecha.',
            'hora_entrada.date_format' => 'La hora de entrada debe tener el formato HH:MM.',
            'hora_entrada.required_with' => 'Debes indicar la hora de entrada si registras una hora de salida.',
            'hora_salida.date_format' => 'La hora de salida debe tener el formato HH:MM.',
            'hora_salida.after_or_equal' => 'La hora de salida no puede ser anterior a la hora de entrada.',
            'estado.required' => 'El estado es obligatorio.',
            'estado.in' => 'El estado seleccionado no es válido.',
            'observacion.max' => 'La observación no puede superar los 1000 caracteres.',
        ];
    }
}

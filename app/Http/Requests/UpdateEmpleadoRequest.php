<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class UpdateEmpleadoRequest extends StoreEmpleadoRequest
{
    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        $rules = parent::rules();

        $rules['dni'] = [
            'required',
            'string',
            'max:20',
            Rule::unique('empleados', 'dni')->ignore($this->route('empleado')),
        ];

        return $rules;
    }
}

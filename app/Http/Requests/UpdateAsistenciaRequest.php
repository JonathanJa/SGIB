<?php

namespace App\Http\Requests;

use Illuminate\Database\Query\Builder;
use Illuminate\Validation\Rule;

class UpdateAsistenciaRequest extends StoreAsistenciaRequest
{
    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        $rules = parent::rules();

        $rules['fecha'] = [
            'required',
            'date',
            Rule::unique('asistencias', 'fecha')
                ->ignore($this->route('asistencia'))
                ->where(fn (Builder $query): Builder => $query->where('empleado_id', $this->input('empleado_id'))),
        ];

        return $rules;
    }
}

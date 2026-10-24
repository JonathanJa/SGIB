<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Empleado extends Model
{
    use HasFactory;

    public const ESTADO_ACTIVO = 'activo';

    public const ESTADO_INACTIVO = 'inactivo';

    public const ESTADO_LICENCIA = 'licencia';

    public const ESTADOS = [
        self::ESTADO_ACTIVO,
        self::ESTADO_INACTIVO,
        self::ESTADO_LICENCIA,
    ];

    protected $fillable = [
        'dni',
        'nombre',
        'apellido',
        'telefono',
        'direccion',
        'fecha_nacimiento',
        'fecha_ingreso',
        'salario',
        'estado',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'fecha_nacimiento' => 'date',
            'fecha_ingreso' => 'date',
            'salario' => 'decimal:2',
        ];
    }

    protected function nombreCompleto(): Attribute
    {
        return Attribute::get(fn (): string => trim("{$this->nombre} {$this->apellido}"));
    }

    public function asistencias(): HasMany
    {
        return $this->hasMany(Asistencia::class);
    }

    public function licencias(): HasMany
    {
        return $this->hasMany(Licencia::class);
    }
}

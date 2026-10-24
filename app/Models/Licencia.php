<?php

namespace App\Models;

use Database\Factories\LicenciaFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

class Licencia extends Model
{
    /** @use HasFactory<LicenciaFactory> */
    use HasFactory;

    public const TIPO_VACACIONES = 'vacaciones';

    public const TIPO_ENFERMEDAD = 'enfermedad';

    public const TIPO_MATERNIDAD = 'maternidad';

    public const TIPO_PATERNIDAD = 'paternidad';

    public const TIPO_DUELO = 'duelo';

    public const TIPO_ESTUDIO = 'estudio';

    public const TIPO_SIN_GOCE_SUELDO = 'sin_goce_sueldo';

    public const TIPO_OTRA = 'otra';

    public const TIPOS = [
        self::TIPO_VACACIONES,
        self::TIPO_ENFERMEDAD,
        self::TIPO_MATERNIDAD,
        self::TIPO_PATERNIDAD,
        self::TIPO_DUELO,
        self::TIPO_ESTUDIO,
        self::TIPO_SIN_GOCE_SUELDO,
        self::TIPO_OTRA,
    ];

    public const TIPO_ETIQUETAS = [
        self::TIPO_VACACIONES => 'Vacaciones',
        self::TIPO_ENFERMEDAD => 'Enfermedad',
        self::TIPO_MATERNIDAD => 'Maternidad',
        self::TIPO_PATERNIDAD => 'Paternidad',
        self::TIPO_DUELO => 'Duelo',
        self::TIPO_ESTUDIO => 'Estudio',
        self::TIPO_SIN_GOCE_SUELDO => 'Sin goce de sueldo',
        self::TIPO_OTRA => 'Otra',
    ];

    public const ESTADO_PENDIENTE = 'pendiente';

    public const ESTADO_APROBADA = 'aprobada';

    public const ESTADO_RECHAZADA = 'rechazada';

    public const ESTADOS = [
        self::ESTADO_PENDIENTE,
        self::ESTADO_APROBADA,
        self::ESTADO_RECHAZADA,
    ];

    protected $fillable = [
        'empleado_id',
        'tipo',
        'fecha_inicio',
        'fecha_fin',
        'motivo',
        'estado',
    ];

    public function empleado(): BelongsTo
    {
        return $this->belongsTo(Empleado::class);
    }

    protected function fechaInicio(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value): ?Carbon => $value !== null ? Carbon::parse($value) : null,
            set: fn (?string $value): ?string => $value !== null ? Carbon::parse($value)->toDateString() : null,
        );
    }

    protected function fechaFin(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value): ?Carbon => $value !== null ? Carbon::parse($value) : null,
            set: fn (?string $value): ?string => $value !== null ? Carbon::parse($value)->toDateString() : null,
        );
    }

    protected function duracionDias(): Attribute
    {
        return Attribute::get(fn (): int => (int) $this->fecha_inicio->diffInDays($this->fecha_fin) + 1);
    }

    protected function tipoEtiqueta(): Attribute
    {
        return Attribute::get(fn (): string => self::TIPO_ETIQUETAS[$this->tipo] ?? ucfirst($this->tipo));
    }
}

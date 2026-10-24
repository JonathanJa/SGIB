<?php

namespace App\Models;

use Database\Factories\AsistenciaFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

class Asistencia extends Model
{
    /** @use HasFactory<AsistenciaFactory> */
    use HasFactory;

    public const ESTADO_PRESENTE = 'presente';

    public const ESTADO_AUSENTE = 'ausente';

    public const ESTADO_TARDANZA = 'tardanza';

    public const ESTADO_JUSTIFICADO = 'justificado';

    public const ESTADOS = [
        self::ESTADO_PRESENTE,
        self::ESTADO_AUSENTE,
        self::ESTADO_TARDANZA,
        self::ESTADO_JUSTIFICADO,
    ];

    protected $fillable = [
        'empleado_id',
        'fecha',
        'hora_entrada',
        'hora_salida',
        'estado',
        'observacion',
    ];

    public function empleado(): BelongsTo
    {
        return $this->belongsTo(Empleado::class);
    }

    protected function fecha(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value): ?Carbon => $value !== null ? Carbon::parse($value) : null,
            set: fn (?string $value): ?string => $value !== null ? Carbon::parse($value)->toDateString() : null,
        );
    }

    protected function horaEntradaFormateada(): Attribute
    {
        return Attribute::get(fn (): ?string => $this->formatearHora($this->hora_entrada));
    }

    protected function horaSalidaFormateada(): Attribute
    {
        return Attribute::get(fn (): ?string => $this->formatearHora($this->hora_salida));
    }

    private function formatearHora(?string $hora): ?string
    {
        if ($hora === null || $hora === '') {
            return null;
        }

        return substr($hora, 0, 5);
    }
}

<?php

namespace Database\Factories;

use App\Models\Asistencia;
use App\Models\Empleado;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Asistencia>
 */
class AsistenciaFactory extends Factory
{
    protected $model = Asistencia::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $estado = fake()->randomElement(Asistencia::ESTADOS);
        $presente = in_array($estado, [Asistencia::ESTADO_PRESENTE, Asistencia::ESTADO_TARDANZA], true);

        return [
            'empleado_id' => Empleado::factory(),
            'fecha' => now()->subDays(fake()->unique()->numberBetween(0, 3650))->format('Y-m-d'),
            'hora_entrada' => $presente ? fake()->time('H:i') : null,
            'hora_salida' => $presente ? fake()->time('H:i') : null,
            'estado' => $estado,
            'observacion' => fake()->optional()->sentence(),
        ];
    }
}

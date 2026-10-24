<?php

namespace Database\Factories;

use App\Models\Empleado;
use App\Models\Licencia;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Licencia>
 */
class LicenciaFactory extends Factory
{
    protected $model = Licencia::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $fechaInicio = fake()->dateTimeBetween('-6 months', '+1 month');
        $fechaFin = (clone $fechaInicio)->modify(sprintf('+%d days', fake()->numberBetween(1, 10)));

        return [
            'empleado_id' => Empleado::factory(),
            'tipo' => fake()->randomElement(Licencia::TIPOS),
            'fecha_inicio' => $fechaInicio->format('Y-m-d'),
            'fecha_fin' => $fechaFin->format('Y-m-d'),
            'motivo' => fake()->optional()->sentence(),
            'estado' => fake()->randomElement(Licencia::ESTADOS),
        ];
    }
}

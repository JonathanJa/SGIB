<?php

namespace Database\Factories;

use App\Models\Empleado;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Empleado>
 */
class EmpleadoFactory extends Factory
{
    protected $model = Empleado::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'dni' => fake()->unique()->numerify('########'),
            'nombre' => fake()->firstName(),
            'apellido' => fake()->lastName(),
            'telefono' => fake()->optional()->phoneNumber(),
            'direccion' => fake()->optional()->address(),
            'fecha_nacimiento' => fake()->optional()->dateTimeBetween('-60 years', '-18 years')?->format('Y-m-d'),
            'fecha_ingreso' => fake()->dateTimeBetween('-10 years', 'now')->format('Y-m-d'),
            'salario' => fake()->randomFloat(2, 500000, 3000000),
            'estado' => fake()->randomElement(Empleado::ESTADOS),
        ];
    }
}

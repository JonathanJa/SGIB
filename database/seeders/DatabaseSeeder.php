<?php

namespace Database\Seeders;

use App\Models\Asistencia;
use App\Models\Empleado;
use App\Models\Licencia;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $empleados = Empleado::factory()->count(20)->create();

        Asistencia::factory()->count(60)->create([
            'empleado_id' => fn () => $empleados->random()->id,
        ]);

        Licencia::factory()->count(15)->create([
            'empleado_id' => fn () => $empleados->random()->id,
        ]);
    }
}

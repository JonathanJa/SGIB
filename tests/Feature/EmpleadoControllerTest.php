<?php

namespace Tests\Feature;

use App\Models\Empleado;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class EmpleadoControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    public function test_index_lists_employees(): void
    {
        Empleado::factory()->create(['nombre' => 'Ana', 'apellido' => 'Gómez']);
        Empleado::factory()->create(['nombre' => 'Bruno', 'apellido' => 'Díaz']);

        $response = $this->get(route('empleados.index'));

        $response->assertOk();
        $response->assertSee('Ana Gómez');
        $response->assertSee('Bruno Díaz');
    }

    public function test_create_displays_the_employee_form(): void
    {
        $response = $this->get(route('empleados.create'));

        $response->assertOk();
        $response->assertSee('Nuevo empleado');
        $response->assertSee('name="dni"', false);
    }

    public function test_show_displays_the_employee(): void
    {
        $empleado = Empleado::factory()->create([
            'dni' => '30123456',
            'nombre' => 'Ana',
            'apellido' => 'Gómez',
        ]);

        $response = $this->get(route('empleados.show', $empleado));

        $response->assertOk();
        $response->assertSee('Ana Gómez');
        $response->assertSee('30123456');
    }

    public function test_edit_displays_the_employee_data(): void
    {
        $empleado = Empleado::factory()->create([
            'dni' => '30123456',
            'nombre' => 'Ana',
            'apellido' => 'Gómez',
        ]);

        $response = $this->get(route('empleados.edit', $empleado));

        $response->assertOk();
        $response->assertSee('Ana Gómez');
        $response->assertSee('value="30123456"', false);
    }

    public function test_store_creates_the_employee_and_redirects(): void
    {
        $response = $this->post(route('empleados.store'), $this->validPayload());

        $response->assertRedirect(route('empleados.index'));
        $response->assertSessionHas('success', 'Empleado registrado correctamente.');
        $this->assertDatabaseHas('empleados', [
            'dni' => '30123456',
            'nombre' => 'Ana',
            'apellido' => 'Gómez',
            'estado' => Empleado::ESTADO_ACTIVO,
        ]);
    }

    public function test_store_rejects_an_empty_payload(): void
    {
        $response = $this->from(route('empleados.create'))->post(route('empleados.store'), []);

        $response->assertRedirect(route('empleados.create'));
        $response->assertSessionHasErrors([
            'dni' => 'El DNI es obligatorio.',
            'nombre' => 'El nombre es obligatorio.',
            'apellido' => 'El apellido es obligatorio.',
            'fecha_ingreso' => 'La fecha de ingreso es obligatoria.',
            'salario' => 'El salario es obligatorio.',
            'estado' => 'El estado es obligatorio.',
        ]);
        $this->assertDatabaseCount('empleados', 0);
    }

    public function test_store_rejects_a_duplicate_dni(): void
    {
        Empleado::factory()->create(['dni' => '30123456']);

        $response = $this->from(route('empleados.create'))->post(route('empleados.store'), $this->validPayload());

        $response->assertRedirect(route('empleados.create'));
        $response->assertSessionHasErrors(['dni' => 'Ya existe un empleado con ese DNI.']);
        $this->assertDatabaseCount('empleados', 1);
    }

    #[DataProvider('invalidFieldValues')]
    public function test_store_rejects_invalid_field_values(string $field, mixed $value): void
    {
        $response = $this->from(route('empleados.create'))->post(
            route('empleados.store'),
            $this->validPayload([$field => $value]),
        );

        $response->assertRedirect(route('empleados.create'));
        $response->assertSessionHasErrors($field);
        $this->assertDatabaseCount('empleados', 0);
    }

    /**
     * @return array<string, array{0: string, 1: mixed}>
     */
    public static function invalidFieldValues(): array
    {
        return [
            'dni too long' => ['dni', str_repeat('1', 21)],
            'name too long' => ['nombre', str_repeat('a', 256)],
            'last name too long' => ['apellido', str_repeat('a', 256)],
            'phone too long' => ['telefono', str_repeat('1', 31)],
            'address too long' => ['direccion', str_repeat('a', 256)],
            'invalid birth date' => ['fecha_nacimiento', 'not-a-date'],
            'future birth date' => ['fecha_nacimiento', '2999-01-01'],
            'invalid entry date' => ['fecha_ingreso', 'not-a-date'],
            'non numeric salary' => ['salario', 'not-a-number'],
            'negative salary' => ['salario', '-1'],
            'salary above maximum' => ['salario', '999999999'],
            'unknown status' => ['estado', 'jubilado'],
        ];
    }

    public function test_update_modifies_the_employee_and_redirects(): void
    {
        $empleado = Empleado::factory()->create([
            'dni' => '30123456',
            'nombre' => 'Ana',
            'apellido' => 'Gómez',
        ]);

        $response = $this->put(
            route('empleados.update', $empleado),
            $this->validPayload(['dni' => '30999999', 'nombre' => 'Laura']),
        );

        $response->assertRedirect(route('empleados.index'));
        $response->assertSessionHas('success', 'Empleado actualizado correctamente.');
        $this->assertDatabaseHas('empleados', [
            'id' => $empleado->id,
            'dni' => '30999999',
            'nombre' => 'Laura',
            'apellido' => 'Gómez',
        ]);
    }

    public function test_update_keeps_the_current_dni(): void
    {
        $empleado = Empleado::factory()->create([
            'dni' => '30123456',
            'nombre' => 'Ana',
        ]);

        $response = $this->put(
            route('empleados.update', $empleado),
            $this->validPayload(['dni' => '30123456', 'nombre' => 'Laura']),
        );

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('empleados.index'));
        $this->assertDatabaseHas('empleados', [
            'id' => $empleado->id,
            'dni' => '30123456',
            'nombre' => 'Laura',
        ]);
    }

    public function test_destroy_deletes_the_employee_and_redirects(): void
    {
        $empleado = Empleado::factory()->create();

        $response = $this->delete(route('empleados.destroy', $empleado));

        $response->assertRedirect(route('empleados.index'));
        $response->assertSessionHas('success', 'Empleado eliminado correctamente.');
        $this->assertModelMissing($empleado);
    }

    /**
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    private function validPayload(array $overrides = []): array
    {
        return array_merge([
            'dni' => '30123456',
            'nombre' => 'Ana',
            'apellido' => 'Gómez',
            'telefono' => '1155551234',
            'direccion' => 'Av. Siempre Viva 123',
            'fecha_nacimiento' => '1990-05-15',
            'fecha_ingreso' => '2024-01-10',
            'salario' => '1500000.50',
            'estado' => Empleado::ESTADO_ACTIVO,
        ], $overrides);
    }
}

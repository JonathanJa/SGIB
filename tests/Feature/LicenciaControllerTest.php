<?php

namespace Tests\Feature;

use App\Models\Empleado;
use App\Models\Licencia;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class LicenciaControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    public function test_index_lists_leave_records(): void
    {
        $ana = Empleado::factory()->create(['nombre' => 'Ana', 'apellido' => 'Gómez']);
        $bruno = Empleado::factory()->create(['nombre' => 'Bruno', 'apellido' => 'Díaz']);

        Licencia::factory()->create([
            'empleado_id' => $ana->id,
            'tipo' => Licencia::TIPO_VACACIONES,
            'fecha_inicio' => '2026-05-01',
            'fecha_fin' => '2026-05-10',
        ]);
        Licencia::factory()->create([
            'empleado_id' => $bruno->id,
            'tipo' => Licencia::TIPO_ENFERMEDAD,
            'fecha_inicio' => '2026-05-05',
            'fecha_fin' => '2026-05-07',
        ]);

        $response = $this->get(route('licencias.index'));

        $response->assertOk();
        $response->assertSee('Ana Gómez');
        $response->assertSee('Bruno Díaz');
        $response->assertSee('Vacaciones');
        $response->assertSee('Enfermedad');
    }

    public function test_create_displays_the_leave_form(): void
    {
        Empleado::factory()->create(['nombre' => 'Ana', 'apellido' => 'Gómez']);

        $response = $this->get(route('licencias.create'));

        $response->assertOk();
        $response->assertSee('Nueva licencia');
        $response->assertSee('name="tipo"', false);
        $response->assertSee('Ana Gómez');
    }

    public function test_show_displays_the_leave_record(): void
    {
        $empleado = Empleado::factory()->create([
            'dni' => '30123456',
            'nombre' => 'Ana',
            'apellido' => 'Gómez',
        ]);

        $licencia = Licencia::factory()->create([
            'empleado_id' => $empleado->id,
            'tipo' => Licencia::TIPO_VACACIONES,
            'fecha_inicio' => '2026-05-01',
            'fecha_fin' => '2026-05-10',
            'estado' => Licencia::ESTADO_APROBADA,
        ]);

        $response = $this->get(route('licencias.show', $licencia));

        $response->assertOk();
        $response->assertSee('Ana Gómez');
        $response->assertSee('Vacaciones');
        $response->assertSee('01/05/2026');
        $response->assertSee('10/05/2026');
        $response->assertSee('10 días');
    }

    public function test_edit_displays_the_leave_data(): void
    {
        $empleado = Empleado::factory()->create(['nombre' => 'Ana', 'apellido' => 'Gómez']);

        $licencia = Licencia::factory()->create([
            'empleado_id' => $empleado->id,
            'tipo' => Licencia::TIPO_VACACIONES,
            'fecha_inicio' => '2026-05-01',
            'fecha_fin' => '2026-05-10',
        ]);

        $response = $this->get(route('licencias.edit', $licencia));

        $response->assertOk();
        $response->assertSee('Ana Gómez');
        $response->assertSee('value="2026-05-01"', false);
        $response->assertSee('value="2026-05-10"', false);
    }

    public function test_store_creates_the_leave_record_and_redirects(): void
    {
        $empleado = Empleado::factory()->create();

        $response = $this->post(route('licencias.store'), $this->validPayload($empleado));

        $response->assertRedirect(route('licencias.index'));
        $response->assertSessionHas('success', 'Licencia registrada correctamente.');
        $this->assertDatabaseHas('licencias', [
            'empleado_id' => $empleado->id,
            'tipo' => Licencia::TIPO_VACACIONES,
            'fecha_inicio' => '2026-05-01',
            'fecha_fin' => '2026-05-10',
            'estado' => Licencia::ESTADO_PENDIENTE,
        ]);
    }

    public function test_store_rejects_an_empty_payload(): void
    {
        $response = $this->from(route('licencias.create'))->post(route('licencias.store'), []);

        $response->assertRedirect(route('licencias.create'));
        $response->assertSessionHasErrors([
            'empleado_id' => 'El empleado es obligatorio.',
            'tipo' => 'El tipo de licencia es obligatorio.',
            'fecha_inicio' => 'La fecha de inicio es obligatoria.',
            'fecha_fin' => 'La fecha de fin es obligatoria.',
            'estado' => 'El estado es obligatorio.',
        ]);
        $this->assertDatabaseCount('licencias', 0);
    }

    #[DataProvider('invalidFieldValues')]
    public function test_store_rejects_invalid_field_values(string $field, mixed $value): void
    {
        $empleado = Empleado::factory()->create();

        $response = $this->from(route('licencias.create'))->post(
            route('licencias.store'),
            $this->validPayload($empleado, [$field => $value]),
        );

        $response->assertRedirect(route('licencias.create'));
        $response->assertSessionHasErrors($field);
        $this->assertDatabaseCount('licencias', 0);
    }

    /**
     * @return array<string, array{0: string, 1: mixed}>
     */
    public static function invalidFieldValues(): array
    {
        return [
            'unknown employee' => ['empleado_id', 999999],
            'unknown type' => ['tipo', 'jubilacion'],
            'invalid start date' => ['fecha_inicio', 'not-a-date'],
            'invalid end date' => ['fecha_fin', 'not-a-date'],
            'end before start' => ['fecha_fin', '2026-04-30'],
            'unknown status' => ['estado', 'cancelada'],
            'reason too long' => ['motivo', str_repeat('a', 1001)],
        ];
    }

    public function test_update_modifies_the_leave_record_and_redirects(): void
    {
        $empleado = Empleado::factory()->create();

        $licencia = Licencia::factory()->create([
            'empleado_id' => $empleado->id,
            'tipo' => Licencia::TIPO_VACACIONES,
            'fecha_inicio' => '2026-05-01',
            'fecha_fin' => '2026-05-10',
            'estado' => Licencia::ESTADO_PENDIENTE,
        ]);

        $response = $this->put(
            route('licencias.update', $licencia),
            $this->validPayload($empleado, [
                'tipo' => Licencia::TIPO_ENFERMEDAD,
                'fecha_fin' => '2026-05-12',
                'estado' => Licencia::ESTADO_APROBADA,
            ]),
        );

        $response->assertRedirect(route('licencias.index'));
        $response->assertSessionHas('success', 'Licencia actualizada correctamente.');
        $this->assertDatabaseHas('licencias', [
            'id' => $licencia->id,
            'empleado_id' => $empleado->id,
            'tipo' => Licencia::TIPO_ENFERMEDAD,
            'fecha_fin' => '2026-05-12',
            'estado' => Licencia::ESTADO_APROBADA,
        ]);
    }

    public function test_destroy_deletes_the_leave_record_and_redirects(): void
    {
        $licencia = Licencia::factory()->create();

        $response = $this->delete(route('licencias.destroy', $licencia));

        $response->assertRedirect(route('licencias.index'));
        $response->assertSessionHas('success', 'Licencia eliminada correctamente.');
        $this->assertModelMissing($licencia);
    }

    /**
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    private function validPayload(Empleado $empleado, array $overrides = []): array
    {
        return array_merge([
            'empleado_id' => $empleado->id,
            'tipo' => Licencia::TIPO_VACACIONES,
            'fecha_inicio' => '2026-05-01',
            'fecha_fin' => '2026-05-10',
            'motivo' => 'Viaje familiar.',
            'estado' => Licencia::ESTADO_PENDIENTE,
        ], $overrides);
    }
}

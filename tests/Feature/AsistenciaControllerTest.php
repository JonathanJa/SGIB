<?php

namespace Tests\Feature;

use App\Models\Asistencia;
use App\Models\Empleado;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class AsistenciaControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    public function test_index_lists_attendance_records(): void
    {
        $ana = Empleado::factory()->create(['nombre' => 'Ana', 'apellido' => 'Gómez']);
        $bruno = Empleado::factory()->create(['nombre' => 'Bruno', 'apellido' => 'Díaz']);

        Asistencia::factory()->create(['empleado_id' => $ana->id, 'fecha' => '2026-05-15']);
        Asistencia::factory()->create(['empleado_id' => $bruno->id, 'fecha' => '2026-05-16']);

        $response = $this->get(route('asistencias.index'));

        $response->assertOk();
        $response->assertSee('Ana Gómez');
        $response->assertSee('Bruno Díaz');
    }

    public function test_create_displays_the_attendance_form(): void
    {
        Empleado::factory()->create(['nombre' => 'Ana', 'apellido' => 'Gómez']);

        $response = $this->get(route('asistencias.create'));

        $response->assertOk();
        $response->assertSee('Nueva asistencia');
        $response->assertSee('name="fecha"', false);
        $response->assertSee('Ana Gómez');
    }

    public function test_show_displays_the_attendance_record(): void
    {
        $empleado = Empleado::factory()->create([
            'dni' => '30123456',
            'nombre' => 'Ana',
            'apellido' => 'Gómez',
        ]);

        $asistencia = Asistencia::factory()->create([
            'empleado_id' => $empleado->id,
            'fecha' => '2026-05-15',
            'hora_entrada' => '08:00',
            'hora_salida' => '17:00',
            'estado' => Asistencia::ESTADO_PRESENTE,
        ]);

        $response = $this->get(route('asistencias.show', $asistencia));

        $response->assertOk();
        $response->assertSee('Ana Gómez');
        $response->assertSee('08:00');
        $response->assertSee('17:00');
    }

    public function test_edit_displays_the_attendance_data(): void
    {
        $empleado = Empleado::factory()->create(['nombre' => 'Ana', 'apellido' => 'Gómez']);

        $asistencia = Asistencia::factory()->create([
            'empleado_id' => $empleado->id,
            'fecha' => '2026-05-15',
            'hora_entrada' => '08:00',
            'estado' => Asistencia::ESTADO_PRESENTE,
        ]);

        $response = $this->get(route('asistencias.edit', $asistencia));

        $response->assertOk();
        $response->assertSee('Ana Gómez');
        $response->assertSee('value="2026-05-15"', false);
        $response->assertSee('value="08:00"', false);
    }

    public function test_store_creates_the_attendance_record_and_redirects(): void
    {
        $empleado = Empleado::factory()->create();

        $response = $this->post(route('asistencias.store'), $this->validPayload($empleado));

        $response->assertRedirect(route('asistencias.index'));
        $response->assertSessionHas('success', 'Asistencia registrada correctamente.');
        $this->assertDatabaseHas('asistencias', [
            'empleado_id' => $empleado->id,
            'fecha' => '2026-05-15',
            'estado' => Asistencia::ESTADO_PRESENTE,
            'observacion' => 'Llegó puntual.',
        ]);
    }

    public function test_store_rejects_an_empty_payload(): void
    {
        $response = $this->from(route('asistencias.create'))->post(route('asistencias.store'), []);

        $response->assertRedirect(route('asistencias.create'));
        $response->assertSessionHasErrors([
            'empleado_id' => 'El empleado es obligatorio.',
            'fecha' => 'La fecha es obligatoria.',
            'estado' => 'El estado es obligatorio.',
        ]);
        $this->assertDatabaseCount('asistencias', 0);
    }

    public function test_store_rejects_a_duplicate_attendance_for_the_same_employee_and_date(): void
    {
        $empleado = Empleado::factory()->create();

        Asistencia::factory()->create([
            'empleado_id' => $empleado->id,
            'fecha' => '2026-05-15',
        ]);

        $response = $this->from(route('asistencias.create'))->post(
            route('asistencias.store'),
            $this->validPayload($empleado),
        );

        $response->assertRedirect(route('asistencias.create'));
        $response->assertSessionHasErrors([
            'fecha' => 'Ya existe una asistencia registrada para ese empleado en esa fecha.',
        ]);
        $this->assertDatabaseCount('asistencias', 1);
    }

    public function test_store_allows_the_same_date_for_different_employees(): void
    {
        $ana = Empleado::factory()->create();
        $bruno = Empleado::factory()->create();

        Asistencia::factory()->create(['empleado_id' => $ana->id, 'fecha' => '2026-05-15']);

        $response = $this->post(route('asistencias.store'), $this->validPayload($bruno));

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('asistencias.index'));
        $this->assertDatabaseCount('asistencias', 2);
    }

    #[DataProvider('invalidFieldValues')]
    public function test_store_rejects_invalid_field_values(string $field, mixed $value): void
    {
        $empleado = Empleado::factory()->create();

        $response = $this->from(route('asistencias.create'))->post(
            route('asistencias.store'),
            $this->validPayload($empleado, [$field => $value]),
        );

        $response->assertRedirect(route('asistencias.create'));
        $response->assertSessionHasErrors($field);
        $this->assertDatabaseCount('asistencias', 0);
    }

    /**
     * @return array<string, array{0: string, 1: mixed}>
     */
    public static function invalidFieldValues(): array
    {
        return [
            'unknown employee' => ['empleado_id', 999999],
            'invalid date' => ['fecha', 'not-a-date'],
            'invalid entry time' => ['hora_entrada', '25:00'],
            'invalid exit time' => ['hora_salida', 'not-a-time'],
            'exit before entry' => ['hora_salida', '07:00'],
            'unknown status' => ['estado', 'vacaciones'],
            'observation too long' => ['observacion', str_repeat('a', 1001)],
        ];
    }

    public function test_update_modifies_the_attendance_record_and_redirects(): void
    {
        $empleado = Empleado::factory()->create();

        $asistencia = Asistencia::factory()->create([
            'empleado_id' => $empleado->id,
            'fecha' => '2026-05-15',
            'estado' => Asistencia::ESTADO_PRESENTE,
        ]);

        $response = $this->put(
            route('asistencias.update', $asistencia),
            $this->validPayload($empleado, [
                'fecha' => '2026-05-16',
                'estado' => Asistencia::ESTADO_TARDANZA,
            ]),
        );

        $response->assertRedirect(route('asistencias.index'));
        $response->assertSessionHas('success', 'Asistencia actualizada correctamente.');
        $this->assertDatabaseHas('asistencias', [
            'id' => $asistencia->id,
            'empleado_id' => $empleado->id,
            'fecha' => '2026-05-16',
            'estado' => Asistencia::ESTADO_TARDANZA,
        ]);
    }

    public function test_update_keeps_the_current_date(): void
    {
        $empleado = Empleado::factory()->create();

        $asistencia = Asistencia::factory()->create([
            'empleado_id' => $empleado->id,
            'fecha' => '2026-05-15',
            'estado' => Asistencia::ESTADO_PRESENTE,
        ]);

        $response = $this->put(
            route('asistencias.update', $asistencia),
            $this->validPayload($empleado, ['estado' => Asistencia::ESTADO_JUSTIFICADO]),
        );

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('asistencias.index'));
        $this->assertDatabaseHas('asistencias', [
            'id' => $asistencia->id,
            'fecha' => '2026-05-15',
            'estado' => Asistencia::ESTADO_JUSTIFICADO,
        ]);
    }

    public function test_destroy_deletes_the_attendance_record_and_redirects(): void
    {
        $asistencia = Asistencia::factory()->create();

        $response = $this->delete(route('asistencias.destroy', $asistencia));

        $response->assertRedirect(route('asistencias.index'));
        $response->assertSessionHas('success', 'Asistencia eliminada correctamente.');
        $this->assertModelMissing($asistencia);
    }

    /**
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    private function validPayload(Empleado $empleado, array $overrides = []): array
    {
        return array_merge([
            'empleado_id' => $empleado->id,
            'fecha' => '2026-05-15',
            'hora_entrada' => '08:00',
            'hora_salida' => '17:00',
            'estado' => Asistencia::ESTADO_PRESENTE,
            'observacion' => 'Llegó puntual.',
        ], $overrides);
    }
}

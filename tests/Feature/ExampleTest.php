<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function test_home_redirects_to_the_employees_index(): void
    {
        $response = $this->get('/');

        $response->assertRedirect(route('empleados.index'));
    }
}

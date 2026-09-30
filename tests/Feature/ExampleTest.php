<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test healthcheck endpoint /up per Render deployment requirement.
     */
    public function test_health_check_returns_successful_response(): void
    {
        $response = $this->get('/up');

        $response->assertStatus(200);
    }

    /**
     * A basic test example for home page.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}


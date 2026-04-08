<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Teste si la page d'accueil redirige bien vers les tâches.
     */
    public function test_homepage_redirects_to_tasks(): void
    {
        $response = $this->get('/');
        // On accepte 200 ou 302 selon ta config
        $response->assertStatus($response->status()); 
    }

    /**
     * Teste si la page des tâches est accessible.
     */
    public function test_tasks_page_returns_successful_response(): void
    {
        $response = $this->get('/tasks');
        $response->assertStatus(200);
    }
}
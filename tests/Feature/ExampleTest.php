<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_guest_cannot_access_homepage(): void
    {
        $response = $this->get('/');
        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }
    
    public function test_authenticated_user_can_access_homepage(): void
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)->get('/');
        $response->assertStatus(200);
    }
    
    public function test_the_application_returns_a_successful_response(): void
    {
        // Tester une route qui n'a pas besoin d'authentification
        $response = $this->get('/login');
        $response->assertStatus(200);
    }
}
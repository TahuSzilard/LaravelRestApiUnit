<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UsersControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_returns_token()
    {
        $user = User::factory()->create([
            'email' => 'teszt@example.com',
            'password' => bcrypt('secret'),
        ]);

        $response = $this->postJson('/api/users/login', [
            'email' => 'teszt@example.com',
            'password' => 'secret',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['token']);
    }

    public function test_login_fails_with_wrong_password()
    {
        User::factory()->create([
            'email' => 'teszt@example.com',
            'password' => bcrypt('secret'),
        ]);

        $response = $this->postJson('/api/users/login', [
            'email' => 'teszt@example.com',
            'password' => 'rossz',
        ]);

        $response->assertStatus(401);
    }

    public function test_index_requires_auth()
    {
        $this->getJson('/api/users')
            ->assertStatus(401);
    }

    public function test_index_returns_all_users()
    {
        $user = User::factory()->create();
        $token = $user->createToken('Test')->plainTextToken;

        User::factory()->count(3)->create();

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->getJson('/api/users');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'users' => [
                    '*' => ['id', 'email']
                ]
            ]);
    }
}

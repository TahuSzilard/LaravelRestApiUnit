<?php

namespace Tests\Unit;

use App\Models\Director;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DirectorsControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_returns_all_directors()
    {
        Director::factory()->create(['name' => 'James Cameron']);
        Director::factory()->create(['name' => 'Christopher Nolan']);

        $response = $this->getJson('/api/directors');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'directors' => [
                    '*' => ['id', 'name']
                ]
            ])
            ->assertJsonFragment(['name' => 'James Cameron'])
            ->assertJsonFragment(['name' => 'Christopher Nolan']);
    }

    public function test_store_creates_new_director()
    {
        $user = User::factory()->create();
        $token = $user->createToken('TestToken')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/directors', [
                'name' => 'Steven Spielberg'
            ]);

        $response->assertStatus(201)
            ->assertJsonFragment(['name' => 'Steven Spielberg']);

        $this->assertDatabaseHas('directors', ['name' => 'Steven Spielberg']);
    }

    public function test_update_modifies_director()
    {
        $director = Director::factory()->create(['name' => 'Old Name']);

        $user = User::factory()->create();
        $token = $user->createToken('TestToken')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->patchJson("/api/directors/{$director->id}", [
                'name' => 'New Name'
            ]);

        $response->assertStatus(200)
            ->assertJsonFragment(['name' => 'New Name']);

        $this->assertDatabaseHas('directors', [
            'id' => $director->id,
            'name' => 'New Name'
        ]);
    }

    public function test_delete_removes_director()
    {
        $director = Director::factory()->create(['name' => 'To Delete']);

        $user = User::factory()->create();
        $token = $user->createToken('TestToken')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->deleteJson("/api/directors/{$director->id}");

        $response->assertStatus(200)
            ->assertJsonFragment(['message' => 'Director deleted successfully']);

        $this->assertDatabaseMissing('directors', ['id' => $director->id]);
    }
}

<?php

namespace Tests\Unit;

use App\Models\Film;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FilmsControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        DB::table('types')->insert([
            'id' => 1,
            'name' => 'Movie'
        ]);

        DB::table('directors')->insert([
            'id' => 1,
            'name' => 'Test Director'
        ]);
    }

    public function test_index_returns_all_films()
    {
        Film::factory()->create(['title' => 'Avatar', 'type_id' => 1, 'director_id' => 1]);
        Film::factory()->create(['title' => 'Inception', 'type_id' => 1, 'director_id' => 1]);

        $response = $this->getJson('/api/films');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'films' => [
                    '*' => ['id', 'title', 'release_date']
                ]
            ])
            ->assertJsonFragment(['title' => 'Avatar'])
            ->assertJsonFragment(['title' => 'Inception']);
    }

    public function test_store_creates_new_film()
    {
        $user = User::factory()->create();
        $token = $user->createToken('TestToken')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/films', [
            'title' => 'Interstellar',
            'type_id' => 1,
            'director_id' => 1,
            'description' => 'Some description',
            'lenght' => 120  
        ]);

        $response->assertStatus(201)
            ->assertJsonFragment(['title' => 'Interstellar']);

        $this->assertDatabaseHas('films', ['title' => 'Interstellar']);
    }

    public function test_update_modifies_existing_film()
    {
        $film = Film::factory()->create([
            'title' => 'Old Title',
            'type_id' => 1,
            'director_id' => 1
        ]);

        $user = User::factory()->create();
        $token = $user->createToken('TestToken')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->patchJson("/api/films/{$film->id}", [
                'title' => 'New Title'
            ]);

        $response->assertStatus(200)
            ->assertJsonFragment(['title' => 'New Title']);

        $this->assertDatabaseHas('films', [
            'id' => $film->id,
            'title' => 'New Title'
        ]);
    }

    public function test_delete_removes_film()
    {
        $film = Film::factory()->create([
            'title' => 'To Be Deleted',
            'type_id' => 1,
            'director_id' => 1
        ]);

        $user = User::factory()->create();
        $token = $user->createToken('TestToken')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->deleteJson("/api/films/{$film->id}");

        $response->assertStatus(200)
            ->assertJsonFragment(['message' => 'Film deleted successfully']);

        $this->assertDatabaseMissing('films', ['id' => $film->id]);
    }
}

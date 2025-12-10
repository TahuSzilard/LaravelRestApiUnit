<?php

namespace Database\Factories;

use App\Models\Film;
use Illuminate\Database\Eloquent\Factories\Factory;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Film>
 */
class FilmFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */ 
    protected $model = Film::class;
    public function definition()
    {
        return [
            'title' => $this->faker->unique()->sentence(2),
            'release_date' => $this->faker->date(),
            'description' => $this->faker->paragraph(),
            'image' => null,
            'length' => $this->faker->numberBetween(60, 180),
            'type_id' => 1,
            'director_id' => null,
        ];
    }
}

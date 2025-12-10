<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
        DemoMediaSeeder::class,
    ]);


        User::factory()->create([
            'name' => 'Levi',
            'email' => 'levi@cc.hu',
            'password' => '12345',
        ]);
    }
}

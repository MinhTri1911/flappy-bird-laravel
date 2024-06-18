<?php

namespace Database\Seeders;

use App\Models\Game;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Game::factory()->create([
            'name' => 'Flappy Bird',
            'icon' => 'flappy_bird.png',
            'setting' => [
                'reward_points' => 5,
            ],
        ]);
    }
}

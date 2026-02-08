<?php

namespace Database\Seeders;

use App\Models\Game;
use Illuminate\Database\Seeder;

class GameSeeder extends Seeder
{
    public function run(): void
    {
        $games = [
            [
                'name' => 'PEMDAS Rush',
                'description' => 'Solve order of operations problems against the clock!',
                'slug' => 'pemdas-rush',
                'config' => ['time_limit' => 60, 'difficulty' => 'adaptive']
            ],
            [
                'name' => 'Integer Battle',
                'description' => 'Battle monsters by solving integer addition and subtraction problems.',
                'slug' => 'integer-battle',
                'config' => ['health' => 100, 'enemies' => 5]
            ],
            [
                'name' => 'Spin the Wheel Math',
                'description' => 'Spin the wheel to get a random operation and solve it for points.',
                'slug' => 'spin-wheel',
                'config' => ['prizes' => [10, 20, 50, 100, 'Jackpot']]
            ],
            [
                'name' => 'Drag-and-Drop Sorting',
                'description' => 'Sort integers from least to greatest or match equations to answers.',
                'slug' => 'drag-drop-sort',
                'config' => ['mode' => 'ascending']
            ],
            [
                'name' => 'Puzzle Match',
                'description' => 'Match the problem card with the correct answer card to reveal a picture.',
                'slug' => 'puzzle-match',
                'config' => ['grid' => '4x4']
            ],
            [
                'name' => 'Multiplayer Simulation',
                'description' => 'Compete against a bot or a classmate in real-time!',
                'slug' => 'multiplayer-sim',
                'config' => ['bot_difficulty' => 'medium']
            ],
        ];

        foreach ($games as $game) {
            Game::create($game);
        }
    }
}

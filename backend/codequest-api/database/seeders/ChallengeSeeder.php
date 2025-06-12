<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Level;
use App\Models\Challenge;

class ChallengeSeeder extends Seeder
{
    public function run(): void
    {
        $level = Level::create([
            'title' => 'Nivel 1: Las Ruinas del Código',
            'description' => 'Explora las ruinas resolviendo acertijos y combates.',
            'difficulty' => 'easy'
        ]);

        Challenge::create([
            'level_id' => $level->id,
            'title' => 'Puzzle de la Puerta Antigua',
            'description' => 'Suma dos números para abrir la puerta.',
            'variables' => ['a' => 2, 'b' => 3],
            'expected_solution' => "5\n",
            'language' => 'python',
            'type' => 'puzzle'
        ]);

        Challenge::create([
            'level_id' => $level->id,
            'title' => 'Combate contra el Gólem',
            'description' => 'Calcula la potencia del ataque mágico.',
            'variables' => ['base' => 2, 'exp' => 3],
            'expected_solution' => "8\n",
            'language' => 'python',
            'type' => 'combat'
        ]);
    }
}


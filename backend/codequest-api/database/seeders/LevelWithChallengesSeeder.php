<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Level;
use App\Models\Challenge;

class LevelWithChallengesSeeder extends Seeder
{
    public function run(): void
    {
        $level = Level::create([
            'title' => 'El Bosque de la Lógica',
            'description' => 'Explora el bosque resolviendo acertijos de lógica y programación.',
            'language' => 'python', // ✅ Añadir este campo
            'difficulty' => 'easy',
]);        $challenges = [
            [
                'title' => 'Puzzle del Cofre',
                'description' => 'Suma dos números y abre el cofre.',
                'language' => 'python3',
                'type' => 'puzzle',
                'starter_code' => "a = 3\nb = 5\n# tu código aquí\nprint(resultado)",
                'input_variables' => json_encode(['a' => 3, 'b' => 5]),
                'expected_output' => '8',
            ],
            [
                'title' => 'Puerta del Laberinto',
                'description' => 'Invierte una cadena para descifrar el código.',
                'language' => 'python3',
                'type' => 'unlock',
                'starter_code' => "texto = 'codequest'\n# tu código aquí\nprint(resultado)",
                'input_variables' => json_encode(['texto' => 'codequest']),
                'expected_output' => 'tseuqedoc',
            ],
            [
                'title' => 'Enemigo Final',
                'description' => 'Determina si una palabra es palíndromo.',
                'language' => 'python3',
                'type' => 'combat',
                'starter_code' => "palabra = 'oso'\n# tu código aquí\nprint(resultado)",
                'input_variables' => json_encode(['palabra' => 'oso']),
                'expected_output' => 'True',
            ],
        ];

        foreach ($challenges as $data) {
            $level->challenges()->create($data);
        }
    }
}

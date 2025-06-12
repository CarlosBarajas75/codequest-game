<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Level;

class LevelSeeder extends Seeder
{
    public function run(): void
    {
        Level::insert([
            [
                'title' => 'Suma básica',
                'description' => 'Escribe un programa que sume dos números y muestre el resultado.',
                'variables' => json_encode(['a' => 3, 'b' => 5]),
                'expected_solution' => '8',
                'difficulty' => 'Fácil',
                'language' => 'python3',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Factorial',
                'description' => 'Escribe un programa que calcule el factorial de un número dado.',
                'variables' => json_encode(['n' => 4]),
                'expected_solution' => '24',
                'difficulty' => 'Media',
                'language' => 'python3',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}

<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Level;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Challenge>
 */
class ChallengeFactory extends Factory
{
    public function definition(): array
    {
        return [
            'level_id' => Level::factory(),
            'title' => fake()->sentence(4),
            'description' => fake()->paragraph(),
            'starter_code' => 'print("Hello World")',
            'input_variables' => json_encode(['x' => 5, 'y' => 10]),
            'expected_output' => '15',
            'language' => 'python3',
            'type' => fake()->randomElement(['puzzle', 'combat', 'search', 'unlock', 'logic']),
        ];
    }
}
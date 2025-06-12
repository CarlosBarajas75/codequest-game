<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
{
    \App\Models\User::factory()->create([
        'name' => 'Carlos Dev',
        'email' => 'admin@codequest.com',
        'password' => bcrypt('password'),
    ]);

    $this->call(LevelWithChallengesSeeder::class);
}
}

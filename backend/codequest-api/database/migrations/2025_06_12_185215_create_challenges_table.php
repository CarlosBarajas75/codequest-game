<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('challenges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('level_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->json('variables')->nullable(); // Variables de entrada para el código
            $table->text('expected_solution'); // Salida esperada
            $table->string('language')->default('python'); // Lenguaje del reto
            $table->enum('type', ['puzzle', 'combat', 'search', 'unlock', 'logic'])->default('puzzle');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('challenges');
    }
};


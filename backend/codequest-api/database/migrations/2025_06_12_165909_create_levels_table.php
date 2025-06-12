<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('levels', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->string('language'); // ej: python3, javascript
            $table->json('variables')->nullable(); // input que se pasa a Piston
            $table->text('expected_solution'); // salida esperada exacta
            $table->string('difficulty')->default('easy'); // easy, medium, hard
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('levels');
    }
};

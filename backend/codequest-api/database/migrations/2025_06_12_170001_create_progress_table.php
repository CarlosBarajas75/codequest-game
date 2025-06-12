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
    Schema::create('progress', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->foreignId('level_id')->constrained()->onDelete('cascade');
        $table->boolean('completed')->default(false);
        $table->integer('attempts')->default(0);
        $table->text('last_submission')->nullable(); // último código enviado
        $table->text('success_output')->nullable();  // output correcto si lo logró
        $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('progress');
    }
};

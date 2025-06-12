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
        Schema::table('progress', function (Blueprint $table) {
            // Eliminar la foreign key si existe
            $table->dropForeign(['level_id']);
            // Eliminar la columna level_id
            $table->dropColumn('level_id');
            // Agregar challenge_id con clave foránea
            $table->foreignId('challenge_id')->constrained()->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('progress', function (Blueprint $table) {
            $table->dropForeign(['challenge_id']);
            $table->dropColumn('challenge_id');
            $table->foreignId('level_id')->constrained()->onDelete('cascade');
        });
    }
};

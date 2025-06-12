<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('challenges', function (Blueprint $table) {
            // Renombrar columnas para mantener consistencia
            $table->renameColumn('variables', 'input_variables');
            $table->renameColumn('expected_solution', 'expected_output');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('challenges', function (Blueprint $table) {
            $table->renameColumn('input_variables', 'variables');
            $table->renameColumn('expected_output', 'expected_solution');
        });
    }
};

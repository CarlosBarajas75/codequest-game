<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{   public function up(): void
{
    Schema::table('challenges', function (Blueprint $table) {
        $table->text('starter_code')->nullable();
        // La columna 'type' ya existe en la migración original
    });
}

public function down(): void
{
    Schema::table('challenges', function (Blueprint $table) {
        $table->dropColumn(['starter_code']);
    });
}
};

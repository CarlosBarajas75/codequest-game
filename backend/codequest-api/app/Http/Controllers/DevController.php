<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class DevController extends Controller
{
    /**
     * Reiniciar base de datos y seeders para desarrollo
     * SOLO para entorno de desarrollo
     */
    public function resetDatabase()
    {
        if (app()->environment('production')) {
            return response()->json([
                'message' => 'Esta acción no está disponible en producción'
            ], 403);
        }

        try {
            // Limpiar y migrar
            Artisan::call('migrate:fresh');
            
            // Ejecutar seeders
            Artisan::call('db:seed', ['--class' => 'LevelWithChallengesSeeder']);
            
            return response()->json([
                'message' => 'Base de datos reiniciada exitosamente',
                'data' => [
                    'migrations' => 'executed',
                    'seeders' => 'executed'
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al reiniciar la base de datos',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

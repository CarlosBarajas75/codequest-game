<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\ChallengeController;
use App\Http\Controllers\ProgressController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DevController;
use Illuminate\Support\Facades\Route;
use App\Models\Level;
use App\Models\Challenge;
use App\Models\User;

// Auth pública
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Rutas protegidas con autenticación + recuperación de vidas
Route::middleware(['auth:sanctum', 'check.and.recover.lives'])->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [UserController::class, 'profile']);

    // Niveles
    Route::get('/levels', [LevelController::class, 'index']);
    Route::get('/levels/{id}', [LevelController::class, 'show']);

    // Retos (Challenges) dentro de niveles
    Route::get('/levels/{levelId}/challenges', [ChallengeController::class, 'indexByLevel']); // opcional
    Route::get('/challenges/{id}', [ChallengeController::class, 'show']);
    Route::post('/challenges/{id}/submit', [ChallengeController::class, 'submit']);    // Progreso
    Route::get('/progress', [ProgressController::class, 'index']);
    Route::get('/progress/{challengeId}', [ProgressController::class, 'show']);
    Route::post('/progress', [ProgressController::class, 'storeOrUpdate']);
});

// Rutas de desarrollo (solo en entorno local)
if (app()->environment(['local', 'development'])) {
    Route::post('/dev/reset-database', [DevController::class, 'resetDatabase']);
}

// Health check endpoint
Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'timestamp' => now(),
        'version' => '1.0.0',
        'environment' => app()->environment(),
        'database' => [
            'users_count' => User::count(),
            'levels_count' => Level::count(),
            'challenges_count' => Challenge::count(),
        ]
    ]);
});


<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Progress;

class UserController extends Controller
{
    // Retornar información completa del usuario
    public function profile(Request $request)
    {
        $user = $request->user();
        
        // Calcular estadísticas del usuario
        $completedChallenges = Progress::where('user_id', $user->id)
            ->where('completed', true)
            ->count();
            
        $totalAttempts = Progress::where('user_id', $user->id)
            ->sum('attempts');

        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'lives' => $user->lives,
            'next_life_in' => $user->next_life_in,
            'stats' => [
                'completed_challenges' => $completedChallenges,
                'total_attempts' => $totalAttempts,
                'success_rate' => $totalAttempts > 0 ? round(($completedChallenges / $totalAttempts) * 100, 2) : 0
            ]
        ]);
    }
}

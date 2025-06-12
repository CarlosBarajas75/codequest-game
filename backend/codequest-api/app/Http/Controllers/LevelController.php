<?php

namespace App\Http\Controllers;

use App\Models\Level;
use App\Models\Progress;
use Illuminate\Http\Request;

class LevelController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $levels = Level::with('challenges')->get();
        
        // Agregar información de progreso para cada nivel
        $levels = $levels->map(function ($level) use ($user) {
            $challengeIds = $level->challenges->pluck('id');
            $completedChallenges = Progress::where('user_id', $user->id)
                ->whereIn('challenge_id', $challengeIds)
                ->where('completed', true)
                ->count();
            
            $level->progress_info = [
                'total_challenges' => $level->challenges->count(),
                'completed_challenges' => $completedChallenges,
                'completion_percentage' => $level->challenges->count() > 0 
                    ? round(($completedChallenges / $level->challenges->count()) * 100, 2)
                    : 0
            ];
            
            return $level;
        });
        
        return response()->json($levels);
    }

    public function show($id, Request $request)
    {
        $user = $request->user();
        $level = Level::with('challenges')->findOrFail($id);
        
        // Agregar información de progreso
        $challengeIds = $level->challenges->pluck('id');
        $userProgress = Progress::where('user_id', $user->id)
            ->whereIn('challenge_id', $challengeIds)
            ->get()
            ->keyBy('challenge_id');
            
        $level->challenges = $level->challenges->map(function ($challenge) use ($userProgress) {
            $progress = $userProgress->get($challenge->id);
            $challenge->user_progress = $progress ? [
                'completed' => $progress->completed,
                'attempts' => $progress->attempts,
                'last_submission' => $progress->last_submission
            ] : [
                'completed' => false,
                'attempts' => 0,
                'last_submission' => null
            ];
            
            return $challenge;
        });
        
        return response()->json($level);
    }
}

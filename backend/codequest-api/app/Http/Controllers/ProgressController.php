<?php

namespace App\Http\Controllers;

use App\Models\Progress;
use App\Models\Challenge;
use Illuminate\Http\Request;

class ProgressController extends Controller
{
    /**
     * Mostrar todo el progreso del usuario autenticado.
     */    public function index(Request $request)
    {
        $user = $request->user();

        $progress = Progress::where('user_id', $user->id)
            ->get()
            ->map(function ($p) {
                return [
                    'id' => $p->id,
                    'challenge_id' => $p->challenge_id,
                    'completed' => $p->completed,
                    'attempts' => $p->attempts,
                    'last_submission' => $p->last_submission,
                    'success_output' => $p->success_output,
                    'created_at' => $p->created_at,
                    'updated_at' => $p->updated_at,
                ];
            });

        $completed = $progress->where('completed', true)->count();
        $totalChallenges = Challenge::count();
        $percentage = $totalChallenges > 0 ? round(($completed / $totalChallenges) * 100, 2) : 0;

        return response()->json($progress);
    }

    /**
     * Registrar o actualizar progreso en un challenge.
     */
    public function storeOrUpdate(Request $request)
    {
        $request->validate([
            'challenge_id' => 'required|exists:challenges,id',
            'code' => 'required|string',
            'success' => 'required|boolean',
            'output' => 'nullable|string',
        ]);

        $user = $request->user();
        $challengeId = $request->challenge_id;

        $progress = Progress::firstOrNew([
            'user_id' => $user->id,
            'challenge_id' => $challengeId
        ]);

        $progress->attempts += 1;
        $progress->last_submission = $request->code;

        if ($request->success) {
            $progress->completed = true;
            $progress->success_output = $request->output;
        }

        $progress->save();

        return response()->json([
            'message' => 'Progress updated successfully',
            'data' => $progress
        ]);
    }

    /**
     * Obtener progreso específico de un reto.
     */
    public function show(Request $request, $challengeId)
    {
        $user = $request->user();

        $progress = Progress::where('user_id', $user->id)
            ->where('challenge_id', $challengeId)
            ->first();

        if (!$progress) {
            return response()->json(['message' => 'No progress found'], 404);
        }

        return response()->json($progress);
    }
}

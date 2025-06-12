<?php

namespace App\Http\Controllers;

use App\Models\Challenge;
use App\Models\Progress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ChallengeController extends Controller
{
    // Mostrar un reto específico
    public function show($id)
    {
        $challenge = Challenge::with('level')->findOrFail($id);
        return response()->json($challenge);
    }

    // Listar todos los retos de un nivel
    public function indexByLevel($levelId)
    {
        $challenges = Challenge::where('level_id', $levelId)->get();
        return response()->json($challenges);
    }

    // Enviar solución de código a un reto
    public function submit(Request $request, $id)
    {        $request->validate([
            'code' => 'required|string|max:10000',
            'language' => 'required|string|in:python,python3,javascript,java,cpp,c'
        ]);

        $challenge = Challenge::findOrFail($id);
        $user = $request->user();

        // Verificar vidas
        if ($user->lives <= 0) {
            return response()->json([
                'message' => 'No tienes vidas restantes. Intenta más tarde o adquiere más.'
            ], 403);
        }        // Preparar input (variables)
        $stdin = '';
        if ($challenge->input_variables) {
            $variables = is_array($challenge->input_variables) ? $challenge->input_variables : json_decode($challenge->input_variables, true);
            foreach ($variables as $key => $value) {
                $stdin .= "$key = $value\n";
            }
        }        // Ejecutar código con Piston API
        try {
            $response = Http::timeout(30)->post('https://emkc.org/api/v2/piston/execute', [
                'language' => $request->language,
                'source' => $request->code,
                'stdin' => $stdin
            ]);

            if (!$response->successful()) {
                Log::error('Piston API error', ['response' => $response->body()]);
                return response()->json([
                    'message' => 'Error al ejecutar el código. Intenta de nuevo.'
                ], 500);
            }

            $responseData = $response->json();
            $output = trim($responseData['run']['stdout'] ?? '');
            $stderr = trim($responseData['run']['stderr'] ?? '');
            
            if (!empty($stderr)) {
                Log::info('Code execution error', ['stderr' => $stderr]);
            }

        } catch (\Exception $e) {
            Log::error('Code execution failed', ['error' => $e->getMessage()]);
            return response()->json([
                'message' => 'Error al ejecutar el código. Intenta de nuevo.'
            ], 500);
        }
        $expected = trim($challenge->expected_output);
        $success = $output === $expected;

        // Si falló, perder una vida
        if (!$success) {
            $user->decrement('lives');
        }

        // Guardar progreso
        $progress = Progress::firstOrNew([
            'user_id' => $user->id,
            'challenge_id' => $challenge->id
        ]);
        $progress->attempts += 1;
        $progress->last_submission = $request->code;
        $progress->success_output = $output;
        if ($success) {
            $progress->completed = true;
        }
        $progress->save();

        return response()->json([
            'success' => $success,
            'output' => $output,
            'expected' => $expected,
            'lives_remaining' => $user->lives,
            'progress' => $progress,
        ]);
    }
}

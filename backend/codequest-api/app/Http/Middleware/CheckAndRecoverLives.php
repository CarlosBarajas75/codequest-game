<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CheckAndRecoverLives
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        $maxLives = 5;

        if ($user && $user->lives < $maxLives) {
            if (!$user->next_life_at) {
                $user->next_life_at = now()->addHour();
                $user->save();
            } elseif (now()->greaterThanOrEqualTo($user->next_life_at)) {
                $user->increment('lives');
                $user->next_life_at = now()->addHour();
                $user->save();
            }
        }

        return $next($request);
    }
}

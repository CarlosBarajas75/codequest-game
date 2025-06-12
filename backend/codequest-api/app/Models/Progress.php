<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Progress extends Model
{    protected $fillable = [
        'user_id',
        'challenge_id',
        'completed',
        'attempts',
        'last_submission',
        'success_output'
    ];

    // Relación con usuario
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Relación con nivel
    public function challenge()
    {
        return $this->belongsTo(Challenge::class);
    }
}

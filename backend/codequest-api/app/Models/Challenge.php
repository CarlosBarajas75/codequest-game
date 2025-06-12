<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Challenge extends Model
{
    use HasFactory;    protected $fillable = [
        'level_id',
        'title',
        'description',
        'starter_code',
        'input_variables',
        'expected_output',
        'language',
        'type',
    ];

    protected $casts = [
        'input_variables' => 'array',
    ];

    // Validar tipos de challenges permitidos
    public static $validTypes = ['puzzle', 'combat', 'search', 'unlock', 'logic'];

    public function level()
    {
        return $this->belongsTo(Level::class);
    }

    public function progresses()
    {
        return $this->hasMany(Progress::class);
    }
}

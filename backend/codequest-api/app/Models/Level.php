<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Level extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'language',
        'difficulty',
    ];

    public function progress()
    {
        return $this->hasMany(Progress::class);
    }

    public function challenges()
    {
        return $this->hasMany(Challenge::class);
    }
}

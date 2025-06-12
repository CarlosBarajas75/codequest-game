<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Models\Level;
use App\Models\Challenge;
use App\Models\User;
use App\Models\Progress;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('debug:data', function () {
    $this->info('=== VERIFICACIÓN DE DATOS ===');
    
    $this->info('📊 Contadores:');
    $this->line('  Users: ' . User::count());
    $this->line('  Levels: ' . Level::count());
    $this->line('  Challenges: ' . Challenge::count());
    $this->line('  Progress: ' . Progress::count());
    
    $this->info('📚 Levels con sus challenges:');
    Level::with('challenges')->get()->each(function ($level) {
        $this->line('  Level ' . $level->id . ': "' . $level->title . '" - ' . $level->challenges->count() . ' challenges');
        $level->challenges->each(function ($challenge) {
            $this->line('    • Challenge ' . $challenge->id . ': "' . $challenge->title . '"');
        });
    });
    
    $this->info('🎯 Challenges órfanos (sin level):');
    $orphans = Challenge::whereNull('level_id')->orWhereNotIn('level_id', Level::pluck('id'))->get();
    if ($orphans->count() > 0) {
        $orphans->each(function ($challenge) {
            $this->line('  Challenge ' . $challenge->id . ': "' . $challenge->title . '" (level_id: ' . $challenge->level_id . ')');
        });
    } else {
        $this->line('  No hay challenges órfanos');
    }
    
})->purpose('Verificar la estructura de datos de CodeQuest');

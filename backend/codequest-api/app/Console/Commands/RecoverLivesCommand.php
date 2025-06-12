<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Carbon\Carbon;

class RecoverLivesCommand extends Command
{
    protected $signature = 'codequest:recover-lives';
    protected $description = 'Recupera una vida a los usuarios cada hora si tienen menos de 5 vidas';

    public function handle()
    {
        $users = User::where('lives', '<', 5)->get();
        $now = Carbon::now();

        foreach ($users as $user) {
            if (!$user->next_life_at) {
                $user->next_life_at = $now->addHour();
                $user->save();
                continue;
            }

            if ($now->greaterThanOrEqualTo($user->next_life_at)) {
                $user->increment('lives');
                $user->next_life_at = $now->addHour();
                $user->save();

                $this->info("Se recuperó 1 vida para el usuario ID {$user->id}");
            }
        }

        return Command::SUCCESS;
    }
}

<?php

namespace App\Jobs;

use App\Models\commande;
use App\Models\LigneCommande;
use App\Models\tube;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Log;

class calculerRetard implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $cs = commande::all();

        foreach ($cs as $c) {
            $ts = $c->tubes()->get();
            foreach ($ts as $t) {
                if ($t->pivot->statut === 'en attente' or $t->pivot->statut === 'partial') {
                    $c->tubes()->updateExistingPivot($t->id, ['statut' => $t->pivot->statut]);
                    $retard = Carbon::parse($t->pivot->updated_at)->diffInSeconds($t->pivot->created_at, true);
                    $c->tubes()->updateExistingPivot($t->id, ['retard' => $retard]);
                }
            }
        }
    }
}

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
                if ($t->pivot->statut === 'en attente') {
                    $c->tubes()->updateExistingPivot($t->id, ['statut' => 'en attente']);
                    $retard = Carbon::parse($t->pivot->updated_at)->diffInSeconds($t->pivot->created_at,true);
                    $c->tubes()->updateExistingPivot($t->id, ['retard' => $retard]);
                }
            }
        }

        // try {
        //     $c = commande::first();
        //     $t = $c->tubes()->first();
        //     if (!$t || !$t->pivot) {
        //         Log::warning('no tubes or pivots');
        //         return;
        //     }
        //     $retard = Carbon::parse($t->pivot->updated_at)->diffInSeconds(now());
        //     // $c->tubes()->updateExistingPivot($t->id, ['retard' => $retard]);
        //     $c->tubes()->updateExistingPivot($t->id, ['retard' => $retard + $t->pivot->retard]);
        //     Log::info('updated');
        // } catch (\Throwable $th) {
        //     //throw $th;
        //     Log::error('failed'.$th->getMessage());
        // }


    }
}

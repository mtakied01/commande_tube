<?php

namespace App\Http\Controllers;

use App\Models\commande;
use App\Models\LigneCommande;
use App\Models\tube;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;

class testController extends Controller
{


    public function check(Request $request)
    {
        $res = DB::table('tubes')->where('dpn', $request->value)->exists();
        return response()->json(['exist' => $res]);
    }


    function test()
    {

        // $t = tube::find(1)->commandes()->get();

        // foreach ($t as $value) {
        //     if ($value->pivot->statut === 'en attente') {
        //         $rt = $value->pivot->retard;
        //         $retard = Carbon::parse($value->pivot->updated_at)->diffInSeconds(now());
        //         $s = $value->pivot->update(['retard'=>$retard+$retard]);
        //     }
        // }


        // $c = commande::all()->first()->tubes()->get()->first()->pivot;
        // $retard = Carbon::parse($c->updated_at)->diffInSeconds(now());
        // $t = $c->update(['retard'=>$retard]);

        // $c = commande::all()->first()->tubes()->get()->first()->pivot;
        // $retard = Carbon::parse($c->updated_at)->diffInSeconds(now());
        // $t = $c->update(['retard'=>$retard]);


        // $c = commande::first();
        // $t = $c->tubes()->first();
        // $retard = Carbon::parse($t->pivot->updated_at)->diffInSeconds(now());
        // $c->tubes()->updateExistingPivot($t->id,['retard' => $retard]);
        // $res = $c->tubes()->updateExistingPivot($t->id,['retard' => $retard+$t->pivot->retard]);


        // $t = tube::find(1)->commandes()->first();
        // $retard = Carbon::parse($t->pivot->updated_at)->diffInSeconds($t->pivot->created_at);


        // return dd($retard);
        // // return dd($retard);

        // $cs = commande::all();

        // foreach ($cs as $c) {
        //     $ts = $c->tubes()->get();
        //     foreach ($ts as $t) {
        //         if ($t->pivot->statut === 'en attente' || $t->pivot->statut === 'partial') {
        //             $c->tubes()->updateExistingPivot($t->id, ['created_at' => $t->pivot->created_at]);
        //             $retard = Carbon::parse($t->pivot->updated_at)->diffInSeconds($t->pivot->created_at, true);
        //             $c->tubes()->updateExistingPivot($t->id, ['retard' => $retard]);
        //         }
        //     }
        // }

        // return dd($cs->first()->tubes()->first()->pivot);
    }
}

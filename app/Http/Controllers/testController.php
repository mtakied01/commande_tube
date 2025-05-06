<?php

namespace App\Http\Controllers;

use App\Models\commande;
use App\Models\LigneCommande;
use App\Models\tube;
use App\Models\validation;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class testController extends Controller
{


    public function check(Request $request)
    {
        $value = $request->query('value');

        $exists = DB::table('tubes')->where('dpn', $value)->exists();

        return response()->json(['exists' => $exists]);
    }


    function test()
    {
        return dd(LigneCommande::selectRaw('count(*) as total_quantity, 
        CASE 
            WHEN HOUR(validations.validated_at) BETWEEN 0 AND 7 THEN "Shift 1" 
            WHEN HOUR(validations.validated_at) BETWEEN 8 AND 15 THEN "Shift 2" 
            ELSE "Shift 3" 
        END as shift')
            ->join('validations', function ($join) {
                $join->on('ligne_commande.tube_id', '=', 'validations.tube_id')
                    ->on('ligne_commande.serial_cmd', '=', 'validations.commande_id');
            })
            ->groupBy('shift')
            ->get());
    }
}

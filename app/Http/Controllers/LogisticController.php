<?php

namespace App\Http\Controllers;

use App\Models\commande;
use App\Models\LigneCommande;
use App\Models\tube;
use Illuminate\Http\Request;

class LogisticController extends Controller
{

  public function index()
  {
    $commande = commande::all();
    $orders = LigneCommande::whereIn('statut', ['en attente','partial'])
      ->orderBy('description', 'desc')
      ->orderBy('updated_at')
      ->get();

    // $orders[0]->commande();

    $serials = $commande->pluck('barcode');

    return view('logisticPage.index', compact('orders', 'serials'));
    // return dd($serials);
  }


  public function create()
  {
    //
  }


  public function store(Request $request)
  {
    //
  }


  public function show(string $id)
  {
    //
  }


  public function edit(string $id)
  {
    //
  }


  public function update(Request $request, string $id)
  {

    $cmd = $request->input('serial_cmd');

    LigneCommande::where('serial_cmd', $cmd)->where('tube_id', tube::where('dpn', $id)->first()->id)->update(['statut' => 'livrée']);

    return response()->json(['message' => 'done']);
    // return dd($cmd);
  }


  public function destroy(string $id)
  {
    LigneCommande::where('serial_cmd', commande::orderBy('created_at', 'desc')->first()->barcode)->where('tube_id', tube::where('dpn', $id)->first()->id)->delete();

    return response()->json(['message' => 'done']);
    // return dd(LigneCommande::where('serial_cmd', commande::orderBy('created_at', 'desc')->first()->barcode)->where('tube_id', tube::where('dpn', $id)->first()->id)->delete());
  }

}

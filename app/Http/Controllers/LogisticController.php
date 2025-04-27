<?php

namespace App\Http\Controllers;

use App\Models\commande;
use App\Models\LigneCommande;
use Illuminate\Http\Request;

class LogisticController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    $commande = commande::all();
    $orders = LigneCommande::with(['tube','commande'])->orderBy('description','desc')->orderBy('updated_at')->get();


    // $orders[0]->commande();

    return view('logisticPage.index', compact('orders', 'commande'));
    // return dd($orders);
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    //
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    //
  }

  /**
   * Display the specified resource.
   */
  public function show(string $id)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(string $id)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, string $id)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(string $id)
  {
    //
  }
}

<?php

namespace App\Http\Controllers;

use App\Models\commande;
use App\Models\LigneCommande;
use App\Models\tube;
use Illuminate\Http\Request;

class CommandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tubes = tube::paginate(10);
        return view('tubePage.index', compact('tubes'));
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
        $serial_cmd = commande::generateSerial();
        $data = $request->all();
        commande::create([
            'barcode' => $serial_cmd,
            'command_by' => 1,
        ]);
        foreach ($data as $val) {
            $dpn = $val['dpn'];
            $qte = $val['qte'];
            $tube_id = tube::where('dpn', '=', $dpn)->get()[0]->id;
            LigneCommande::create([
                'serial_cmd' => $serial_cmd,
                'tube_id' => $tube_id,
                'quantity' => $qte,
                'rack' => 'F01',
                'statut' => 'en attente',
                'retard' => 0,
                'description' => 'manque',
            ]);
        }

        //   $table->uuid('id')->primary();
        //   $table->string('barcode')->unique();
        //   $table->foreignId('command_by')->nullable()->constrained('users', 'id');
        //   $table->string('status')->default('en_attente');
        //   $table->timestamps();

        return response()->json(['status' => 'accepted', 'message' => 'hello']);
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

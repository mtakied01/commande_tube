<?php

namespace App\Http\Controllers;

use App\Models\commande;
use App\Models\LigneCommande;
use App\Models\tube;
use App\Models\validation;
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
        $orders = LigneCommande::whereIn('statut', ['en attente', 'partial'])
            ->orderBy('description', 'desc')
            ->orderBy('updated_at')
            ->get();

        foreach ($orders as $order) {
            $fullorder = $order->quantity;
            $partorder = validation::where('commande_id', $order->serial_cmd)
                ->where('tube_id', $order->tube_id)->count();
            $order->quantity = $fullorder - $partorder;
        }

        return view('tubePage.validate', compact('orders'));
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
            'command_by' => auth()->user()->id,
        ]);
        foreach ($data as $val) {
            $dpn = $val['dpn'];
            $qte = $val['qte'];
            $tube = tube::where('dpn', '=', $dpn)->get()[0];
            LigneCommande::create([
                'serial_cmd' => $serial_cmd,
                'tube_id' => $tube->id,
                'quantity' => $qte,
                'rack' => $tube->rack,
                'statut' => 'en attente',
                'retard' => 0,
                'description' => '-',
            ]);
        }

        return response()->json(['status' => 'accepted', 'message' => 'Commande envoyée']);
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

    public function checkProduct(Request $request)
    {
        $commande = Commande::where('barcode', $request->serial_cmd)->first();

        if (!$commande) {
            return response()->json(['valid' => false]);
        }

        // Step 1: Find matching ligne_commande (line with right APN and command)
        $ligne = LigneCommande::where('serial_cmd', $commande->barcode)
            ->whereHas('tube', fn($q) => $q->where('dpn', $request->apn))
            ->first();

        if (!$ligne) {
            return response()->json(['valid' => false]);
        }

        // Step 2: Check if serial_product is already validated
        $alreadyValidated = Validation::where('serial_product', $request->serial_product)->exists();

        if ($alreadyValidated) {
            return response()->json(['valid' => false, 'reason' => 'Déjà validé']);
        }

        // Step 3: Count existing validations for this ligne
        $countValidated = Validation::where('commande_id', $commande->barcode)
            ->where('tube_id', $ligne->tube_id)
            ->count();

        // Step 4: Check if we still have room to validate this one
        $canValidate = $countValidated <= $ligne->quantity;

        return response()->json(['valid' => $canValidate]);

    }


    public function validateProducts(Request $request)
    {
        $request->validate([
            'serial_cmd' => 'required|string',
            'products' => 'required|array',
            'products.*.apn' => 'required|string',
            'products.*.serials' => 'required|array',
        ]);

        foreach ($request->products as $product) {
            $tube = Tube::where('dpn', $product['apn'])->first();

            if (!$tube)
                continue;

            $ligne = LigneCommande::where('serial_cmd', $request->serial_cmd)
                ->where('tube_id', $tube->id)
                ->first();

            if (!$ligne)
                continue;

            $expectedQty = $ligne->quantity ?? 0;

            $alreadyValidated = Validation::where('commande_id', $request->serial_cmd)
                ->where('tube_id', $tube->id)
                ->count();

            $remainingQty = $expectedQty - $alreadyValidated;

            $serialsToValidate = array_slice($product['serials'], 0, $remainingQty);

            foreach ($serialsToValidate as $serial) {
                Validation::create([
                    'commande_id' => $request->serial_cmd,
                    'tube_id' => $tube->id,
                    'serial_product' => $serial,
                ]);
            }

            $totalValidated = $alreadyValidated + count($serialsToValidate);
            $newStatus = match (true) {
                $totalValidated >= $expectedQty => 'livrée',
                $totalValidated > 0 => 'partial',
                default => 'en attente',
            };

            LigneCommande::where('serial_cmd', $request->serial_cmd)
                ->where('tube_id', $tube->id)
                ->update(['statut' => $newStatus]);

        }

        return response()->json(['success' => true]);
    }




}

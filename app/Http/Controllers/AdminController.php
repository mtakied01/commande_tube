<?php

namespace App\Http\Controllers;

use App\Models\tube;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (auth()->check() && auth()->user()->role === 'admin') {
            $products = Tube::paginate(13);
            return view('adminPage.index', compact('products'));
        }

        // Auth::logout();
        // $request->session()->invalidate();
        // $request->session()->regenerateToken();

        // return redirect()->route('login');
        return abort(401);
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
        // return view('admin.edit', compact('product'));   
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, tube $product)
    {
        // 
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($product)
    {
        tube::find($product)->delete();

        return redirect()->route('admin.index')->with('success', 'Produit supprimé.');
        // return dd($pr);
    }
}

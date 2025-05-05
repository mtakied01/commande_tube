<?php

namespace App\Http\Controllers;

use App\Models\tube;
use Illuminate\Http\Request;

class administrationController extends Controller
{

  // affichage
  public function showApn()
  {
    if (auth()->check() && auth()->user()->role === 'admin') {
      return view('adminPage.addApn');
    }
  }

  public function showRack()
  {
    $racks = [];
    foreach (range(1, 12) as $fNumber) {
      $racks[] = "F" . str_pad($fNumber, 2, '0', STR_PAD_LEFT);
    }
    foreach (['01', '02', '03'] as $number) {
      foreach (['A', 'B', 'C'] as $letter) {
      foreach (range(1, 5) as $suffix) {
        $racks[] = "R{$number}-{$letter}" . str_pad($suffix, 2, '0', STR_PAD_LEFT);
      }
      }
    }
    if (auth()->check() && auth()->user()->role === 'admin') {
      $products = tube::paginate(10);
      return view('adminPage.rack', compact('products','racks'));
    }
  }
  public function searchRack(Request $req)
  {
    $racks = [];
    foreach (range(1, 12) as $fNumber) {
      $racks[] = "F" . str_pad($fNumber, 2, '0', STR_PAD_LEFT);
    }
    foreach (['01', '02', '03'] as $number) {
      foreach (['A', 'B', 'C'] as $letter) {
      foreach (range(1, 5) as $suffix) {
        $racks[] = "R{$number}-{$letter}" . str_pad($suffix, 2, '0', STR_PAD_LEFT);
      }
      }
    }
    if (auth()->check() && auth()->user()->role === 'admin') {
      $products = tube::where('dpn','LIKE', '%'.$req->inpt)->paginate(20);
      return view('adminPage.rack', compact('products','racks'));
      // return dd($products);
    }
  }

  // creation
  public function addApn(Request $req)
  {
    tube::create($req->all());
    return redirect()->route('admin.apn');
    // return dd($req);
  }
  public function addRack(Request $req)
  {
    return redirect()->route('adminPage');
  }

  // delete
  public function deleteApn(Request $req, $id)
  {
    return redirect()->route('adminPage');
  }
  public function deleteRack(Request $req, $id)
  {
    return redirect()->route('adminPage');
  }

  // update
  public function updateRack(Request $req,$id)
  {
    $product = tube::findOrFail($id);
    $product->update($req->only(['dpn', 'type', 'packaging', 'unity', 'rack']));
    return redirect()->back()->with('success', 'Produit mis à jour.');
  }
}

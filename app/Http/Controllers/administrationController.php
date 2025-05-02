<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class administrationController extends Controller
{

  // affichage
  public function showApn()
  {
    return view('adminPage.addApn');
  }
  public function showRack()
  {
    return view('adminPage.rack');
  }

  // creation
  public function addApn(Request $req)
  {
    return redirect()->route('adminPage');
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
}

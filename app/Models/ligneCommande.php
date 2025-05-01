<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LigneCommande extends Model
{
  protected $table = 'ligne_commande';


  protected $guarded = [];

  // public function tube()
  // {
  //   return $this->belongsToMany(Tube::class);
  // }

  public function tube()
  {
    return $this->belongsTo(Tube::class, 'tube_id');
  }

  public function validation()
  {
    return $this->hasMany(Validation::class, 'serial_cmd', 'commande_id')
      ->whereColumn('tube_id', 'ligne_commande.tube_id');
  }


  // public function commande()
  // {
  //   return $this->belongsToMany(Commande::class, 'serial_cmd', 'barcode');
  // }
}


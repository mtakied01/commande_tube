<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LigneCommande extends Model
{
  public $timestamps = false;
  protected $table = 'ligne_commande';

  // protected $fillable = [
  //   'serial_cmd',
  //   'tube_id',
  //   'quantity',
  //   'location',
  //   'statut',
  //   'retard',
  //   'description'
  // ];

  protected $guarded = [];

  public function tube()
  {
    return $this->belongsTo(Tube::class);
  }


  public function commande()
  {
    return $this->belongsTo(Commande::class, 'serial_cmd', 'barcode');
  }
}


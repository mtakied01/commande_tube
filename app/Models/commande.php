<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class commande extends Model
{
  protected $keyType = 'string';
  public $incrementing = false;
  protected $guarded = [];
  protected $primaryKey = 'barcode';

  protected static function boot()
  {
    parent::boot();

    // static::creating(function ($model) {
    //   $model->id = Str::uuid()->toString();
    // });
  }

  public function tubes()
  {
    return $this->belongsToMany(Tube::class, 'ligne_commande', 'serial_cmd', 'tube_id')
      ->withPivot(['quantity', 'location', 'statut', 'retard', 'description', 'updated_at']);
  }

  public static function generateSerial(): string
  {
      $datePart = now()->format('Ymd');
      $randomPart = strtoupper(Str::random(5));
      return "CMD-{$datePart}-{$randomPart}";
  }

}

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
      ->withPivot(['quantity', 'statut', 'retard', 'description', 'updated_at','created_at']);
  }

  public function user()
  {
    return $this->belongsTo(User::class, 'command_by');
  }

  public function validations()
{
    return $this->hasMany(Validation::class);
}

  public static function generateSerial(): string
  {
      $datePart = now()->format('Ymd');
      $randomPart = strtoupper(Str::random(5));
      return "CMD-{$datePart}-{$randomPart}";
  }

}

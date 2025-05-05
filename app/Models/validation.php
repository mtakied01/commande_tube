<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class validation extends Model
{
    protected $guarded = [];

    public $timestamps = false;

    public function commande()
    {
        return $this->belongsTo(Commande::class);
    }

    public function tube()
    {
        return $this->belongsTo(Tube::class);
    }
}

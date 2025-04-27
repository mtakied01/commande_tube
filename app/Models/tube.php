<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class tube extends Model
{
	// protected $keyType = 'string';
	// public $incrementing = false;

	// protected static function boot()
	// {
	// 	parent::boot();

	// 	static::creating(function ($model) {
	// 		$model->id = Str::uuid()->toString();
	// 	});
	// }

	protected $guarded = [];

	public function commandes()
	{
		return $this->belongsToMany(Commande::class, 'ligne_commande', 'tube_id', 'serial_cmd')
			->withPivot(['quantity', 'location', 'statut', 'retard', 'description', 'updated_at']);
	}

}

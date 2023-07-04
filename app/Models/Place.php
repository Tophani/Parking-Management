<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Place extends Model
{
	public $timestamps = false;

	protected $table = "place";

    protected $fillable = [
		'name',
		'address',
		'latitude',
		'longitude',
		'limit',
		'serials',
		'note',
		'status',
    ];

}

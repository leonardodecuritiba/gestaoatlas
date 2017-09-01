<?php

namespace App\Models\Inputs\Stocks;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;

class ToolStock extends Pivot {
	use SoftDeletes;
//	use InstrumentsTrait;
	public $timestamps = true;
	protected $fillable = [
		'quantity',
		'expiration',
		'cost'
	];

	// ************************** RELASHIONSHIP **********************************


	// ************************** RELASHIONSHIP **********************************

}

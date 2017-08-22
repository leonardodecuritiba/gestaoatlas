<?php

namespace App\Models\Inputs;

use App\Traits\InstrumentsTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Instrument extends Model {
	use SoftDeletes;
	use InstrumentsTrait;
	public $timestamps = true;
	protected $fillable = [
		'serial_number',
		'inventory',
//		'patrimony',
		'year',
		'idbase',
//		'idprotection',
//		'etiqueta_identificacao',
//		'etiqueta_inventario'
	];

	// ************************** RELASHIONSHIP **********************************


	// ************************** RELASHIONSHIP **********************************

}

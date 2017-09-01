<?php

namespace App\Models\Inputs;

use App\Colaborador;
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

	public function collaborator() {
		return $this->belongsToMany( Colaborador::class, 'instrument_stocks', 'idinstrument', 'idcolaborador' );
	}
}

<?php

namespace App\Models\Inputs;

use App\Colaborador;
use App\Helpers\DataHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pattern extends Model
{
	use SoftDeletes;
	public $timestamps = true;
	protected $fillable = [
		'idunit',
		'idbrand',
		'description',
		'measure',
		'cost',
		'class',
	];

	// ************************** FUNCTION **********************************

	static public function getAlltoSelectList() {
		return self::get()->map( function ( $s ) {
			return [
				'id'          => $s->id,
				'description' => $s->description . ' - ' . $s->getMeasure() . ' - ' . $s->getBrandText() . ' - ' . $s->getCost()
			];
		} )->pluck( 'description', 'id' );
	}


	public function getResume() {
		return $this->attributes['description'] . ' - ' .
		       $this->getBrandText() . ' - ' .
		       $this->getMeasure();
	}

	public function getUnityText() {
		return $this->unity->codigo;
	}

	public function getMeasureAttribute($value) {
		return DataHelper::getFloat2Real($value);
	}

	public function getCostAttribute($value) {
		return DataHelper::getFloat2Real($value);
	}

	public function getCost() {
		return DataHelper::getFloat2RealMoeda($this->attributes['cost']);
	}

	public function getMeasure() {
		return $this->getAttribute('measure') . ' ' . $this->unity->codigo;
	}

	public function getBrandText() {
		return $this->brand->description;
	}


	// ************************** RELASHIONSHIP **********************************

	public function unity() {
		return $this->hasOne('App\Unidade', 'idunidade', 'idunit');
	}

	public function brand() {
		return $this->hasOne('App\Models\Commons\Brand', 'id', 'idbrand');
	}

	public function collaborators() {
		return $this->belongsToMany( Colaborador::class, 'pattern_stocks', 'idpattern', 'idcolaborador' );
	}
}

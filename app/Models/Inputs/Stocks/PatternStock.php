<?php

namespace App\Models\Inputs\Stocks;

use App\Colaborador;
use App\Helpers\DataHelper;
use App\Models\Inputs\Pattern;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;

class PatternStock extends Model {
//	use SoftDeletes;
//	use InstrumentsTrait;
	public $timestamps = true;
	protected $fillable = [
		'idcolaborador',
		'idpattern',
		'quantity',
		'expiration',
		'cost',
		'certification_cost',
		'certification',
	];

	// ************************** RELASHIONSHIP **********************************


	public function getCost() {
		return DataHelper::getFloat2RealMoeda( $this->attributes['cost'] );
	}

	public function setCostAttribute( $value ) {
		return $this->attributes['cost'] = DataHelper::getReal2Float( $value );
	}

	public function setExpirationAttribute( $value ) {
		return $this->attributes['expiration'] = DataHelper::setDate( $value );
	}

	public function getExpirationAttribute() {
		return DataHelper::getPrettyDate( $this->attributes['expiration'] );
	}

	public function getCostCertification() {
		return DataHelper::getFloat2RealMoeda( $this->attributes['certification_cost'] );
	}

	public function setCertificationCostAttribute( $value ) {
		return $this->attributes['certification_cost'] = DataHelper::getReal2Float( $value );
	}


	public function setQuantityAttribute( $value ) {
		return $this->attributes['expiration'] = DataHelper::getOnlyNumbers( $value );
	}

	// ************************** RELASHIONSHIP **********************************


	public function pattern() {
		return $this->belongsTo( Pattern::class, 'idpattern' );
	}

	public function collaborator() {
		return $this->belongsTo( Colaborador::class, 'idcolaborador' );
	}

}

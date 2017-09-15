<?php

namespace App\Models\Inputs\Stocks;

use App\Colaborador;
use App\Helpers\DataHelper;
use App\Models\Inputs\Pattern;
use App\Models\Inputs\Voids\VoidPattern;
use App\Models\Inputs\Voids\Voidx;
use App\Traits\SecurityTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class PatternStock extends Model {
	use SoftDeletes;
	use SecurityTrait;
	public $timestamps = true;
	protected $fillable = [
		'security_id',
		'pattern_id',
		'owner_id',
		'void_pattern_id',
		'expiration',
		'cost',
		'certification_cost',
		'certification',
	];

	// ************************** FUNCTIONS **********************************


	public function setVoid( $void_pattern_id ) {
		return $this->update( [
			'void_pattern_id' => $void_pattern_id
		] );
	}

	public function setOwner( $owner_id ) {
		return $this->update( [
			'owner_id' => $owner_id
		] );
	}

	static public function createClean( array $attributes = [] ) {
		$attributes['owner_id']        = null;
		$attributes['void_pattern_id'] = null;

		return parent::create( $attributes ); // TODO: Change the autogenerated stub
	}

	static public function createWithVoid( array $attributes = [] ) {
		Voidx::setUsed( $attributes['void_id'] );
		$Void = VoidPattern::createClean( $attributes );
		$Void->enable( Auth::user()->colaborador->idcolaborador );
		$attributes['void_pattern_id'] = $Void->id;
		unset( $attributes['void_id'] );

		return parent::create( $attributes ); // TODO: Change the autogenerated stub
	}

	public function getCertificationText() {
		return $this->certification . " (" . $this->getCostCertification() . ")";
	}

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
		return $this->belongsTo( Pattern::class );
	}

	public function owner() {
		return $this->belongsTo( Colaborador::class, 'owner_id', 'idcolaborador' );
	}

	public function void_pattern() {
		return $this->belongsTo( VoidPattern::class, 'void_pattern_id' );
	}

}

<?php

namespace App\Models\Security;

use App\Helpers\DataHelper;
use Illuminate\Database\Eloquent\Model;

class Security extends Model {
	public $timestamps = true;
	protected $table = 'seguranca_criacaos';
	protected $fillable = [
		'idcriador',
		'verb',
		'idvalidador',
		'validated_at'
	];

	static public function setCreate( array $data, $validated = false ) {
		$cr = [
			'idcriador' => $data['idcolaborador'],
			'verb'      => 'CREATE'
		];
		if ( $validated ) {
			$cr['idvalidador']  = $cr['idcriador'];
			$cr['validated_at'] = DataHelper::now();
		}

		return self::create( $cr );
	}

	static public function setUpdate( array $data, $validated = false ) {
		$cr = [
			'idcriador' => $data['idcolaborador'],
			'verb'      => 'UPDATE'
		];
		if ( $validated ) {
			$cr['idvalidador']  = $cr['idcriador'];
			$cr['validated_at'] = DataHelper::now();
		}

		return self::create( $cr );
	}

	static public function setDelete( array $data, $validated = false ) {
		$cr = [
			'idcriador' => $data['idcolaborador'],
			'verb'      => 'DELETE'
		];
		if ( $validated ) {
			$cr['idvalidador']  = $cr['idcriador'];
			$cr['validated_at'] = DataHelper::now();
		}

		return self::create( $cr );
	}

	static public function validate( $id, $idvalidador ) {
		$Data = self::findOrFail( $id );

		return $Data->update( [
			'idvalidador'  => $idvalidador,
			'validated_at' => DataHelper::now()
		] );
	}

	// ********************** BELONGS ********************************
	public function criador() {
		return $this->belongsTo( 'App\Colaborador', 'idcriador', 'idcolaborador' );
	}

	public function validador() {
		return $this->belongsTo( 'App\Colaborador', 'idvalidador', 'idcolaborador' );
	}

	// ************************** HASMANY **********************************
	public function instrumento_bases() {
		return $this->hasMany( 'App\Models\InstrumentoBase', 'idmodelo' );
	}
}

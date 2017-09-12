<?php

namespace App\Traits;

use App\Models\Commons\Voidx;
use App\Colaborador;
use App\Helpers\DataHelper;

trait VoidTrait {

	public function enable( $enabler_id ) {
		return $this->update( [
			'enabler_id' => $enabler_id,
			'enabled_at' => DataHelper::now(),
		] );
	}

	public function disable( $disabler_id ) {
		return $this->update( [
			'disabler_id' => $disabler_id,
			'disabled_at' => DataHelper::now(),
		] );
	}

	// ************************** RELASHIONSHIP **********************************

	public function void() {
		return $this->belongsTo( Voidx::class, 'void_id' );
	}

	public function disabler() {
		return $this->belongsTo( Colaborador::class, 'disabler_id', 'idcolaborador' );
	}

	public function enabler() {
		return $this->belongsTo( Colaborador::class, 'enabler_id', 'idcolaborador' );
	}
}
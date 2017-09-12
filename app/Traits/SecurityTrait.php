<?php

namespace App\Traits;

use App\Models\Security\Security;
use App\User;

trait SecurityTrait {

	static public function setCreate( User $user ) {
		$data['idcolaborador'] = $user->colaborador->idcolaborador;
		$Security              = Security::setCreate( $data, $user->is( 'admin' ) );

		return $Security->id;
	}

	public function security() {
		return $this->belongsTo( Security::class, 'security_id' );
	}
}
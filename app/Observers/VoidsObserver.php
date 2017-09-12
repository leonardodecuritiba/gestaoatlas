<?php
/**
 * Created by PhpStorm.
 * User: Leonardo
 * Date: 01/12/2016
 * Time: 12:02
 */


namespace App\Observers;

use App\Models\Inputs\Voids\Voidx;
use App\Traits\SecurityTrait;
use Illuminate\Support\Facades\Auth;

class VoidsObserver {
	/**
	 * Listen to the User created event.
	 *
	 * @param  Voidx $void
	 *
	 * @return void
	 */
	public function creating( Voidx $void ) {
		$void->security_id = SecurityTrait::setCreate( Auth::user() );
	}

}
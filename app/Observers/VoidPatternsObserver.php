<?php
/**
 * Created by PhpStorm.
 * User: Leonardo
 * Date: 01/12/2016
 * Time: 12:02
 */


namespace App\Observers;

use App\Models\Inputs\Voids\VoidPattern;
use App\Traits\SecurityTrait;
use Illuminate\Support\Facades\Auth;

class VoidPatternsObserver {
	/**
	 * Listen to the VoidPattern created event.
	 *
	 * @param  VoidPattern $data
	 *
	 * @return void
	 */
	public function creating( VoidPattern $data ) {
		$data->security_id = SecurityTrait::setCreate( Auth::user() );
	}

	/**
	 * Listen to the VoidPattern deleting event.
	 *
	 * @param  VoidPattern $data
	 *
	 * @return void
	 */
	public function deleting( VoidPattern $data ) {
		$data->void->remove();
	}

}
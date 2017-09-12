<?php
/**
 * Created by PhpStorm.
 * User: Leonardo
 * Date: 01/12/2016
 * Time: 12:02
 */


namespace App\Observers;

use App\Models\Inputs\Voids\VoidTool;
use App\Traits\SecurityTrait;
use Illuminate\Support\Facades\Auth;

class VoidToolsObserver {
	/**
	 * Listen to the User created event.
	 *
	 * @param  VoidTool $data
	 *
	 * @return void
	 */
	public function creating( VoidTool $data ) {
		$data->security_id = SecurityTrait::setCreate( Auth::user() );
	}

}
<?php
/**
 * Created by PhpStorm.
 * User: Leonardo
 * Date: 01/12/2016
 * Time: 12:02
 */


namespace App\Observers;

use App\Models\Inputs\Stocks\PatternStock;
use App\Traits\SecurityTrait;
use Illuminate\Support\Facades\Auth;

class PatternStocksObserver {
	/**
	 * Listen to the User created event.
	 *
	 * @param  PatternStock $data
	 *
	 * @return void
	 */
	public function creating( PatternStock $data ) {
		$data->security_id = SecurityTrait::setCreate( Auth::user() );
	}


	/**
	 * Listen to the ToolStock deleting event.
	 *
	 * @param  PatternStock $data
	 *
	 * @return void
	 */
	public function deleting( PatternStock $data ) {
		$data->void_pattern->delete();
	}
}
<?php
/**
 * Created by PhpStorm.
 * User: Leonardo
 * Date: 01/12/2016
 * Time: 12:02
 */


namespace App\Observers;

use App\Models\Inputs\Stocks\ToolStock;
use App\Traits\SecurityTrait;
use Illuminate\Support\Facades\Auth;

class ToolStocksObserver {
	/**
	 * Listen to the ToolStock created event.
	 *
	 * @param  ToolStock $data
	 *
	 * @return void
	 */
	public function creating( ToolStock $data ) {
		$data->security_id = SecurityTrait::setCreate( Auth::user() );
	}

	/**
	 * Listen to the ToolStock deleting event.
	 *
	 * @param  ToolStock $data
	 *
	 * @return void
	 */
	public function deleting( ToolStock $data ) {
		$data->void_tool->delete();
	}
}
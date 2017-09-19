<?php
/**
 * Created by PhpStorm.
 * User: Leonardo
 * Date: 01/12/2016
 * Time: 12:02
 */


namespace App\Observers;

use App\Models\Inputs\Pattern;

class PatternsObserver {
	/**
	 * Listen to the User deleting event.
	 *
	 * @param  Pattern $data
	 *
	 * @return void
	 */
	public function deleting( Pattern $data ) {
		foreach ( $data->stocks as $stock ) {
			$stock->delete();
		}
	}

}
<?php
/**
 * Created by PhpStorm.
 * User: Leonardo
 * Date: 01/12/2016
 * Time: 12:02
 */


namespace App\Observers;

use App\Models\Inputs\Tool;

class ToolsObserver {
	/**
	 * Listen to the User deleting event.
	 *
	 * @param  Tool $data
	 *
	 * @return void
	 */
	public function deleting( Tool $data ) {
		foreach ( $data->stocks as $stock ) {
			$stock->delete();
		}
	}

}
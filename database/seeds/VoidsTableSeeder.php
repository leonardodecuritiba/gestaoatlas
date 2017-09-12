<?php

use Illuminate\Database\Seeder;
use \App\Models\Inputs\Voids\Voidx;
use \Illuminate\Support\Facades\Auth;

class VoidsTableSeeder extends Seeder {
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run() {
		//php artisan db:seed --class=VoidsTableSeeder

		Auth::loginUsingId( 9, true );

		$start   = microtime( true );
		$numbers = range( 2, 3 );
		foreach ( $numbers as $n ) {
			Voidx::create( [ 'number' => $n ] );
		}
		$this->command->info( 'VoidsTableSeeder complete: in ' . round( ( microtime( true ) - $start ), 3 ) . 's ...' );
	}
}

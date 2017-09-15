<?php

use Illuminate\Database\Seeder;

class PatternsTableSeeder extends Seeder {
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run() {
		//php artisan db:seed --class=ToolsTableSeeder
		$start = microtime( true );
		factory( \App\Models\Inputs\Pattern::class, 10 )->create();
		$this->command->info( 'Testing complete: in ' . round( ( microtime( true ) - $start ), 3 ) . 's ...' );
	}
}

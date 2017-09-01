<?php

use Illuminate\Database\Seeder;

class UpdateV1_8Seeder extends Seeder {
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run() {
//    php artisan db:seed --class=UpdateV1_8Seeder
		$start = microtime( true );

		//$this->call(ConstraintsSeloLacresRequestSeeder::class);
		$this->call( StatusRequestSeeder::class );
		$this->call( TypeRequestSeeder::class );
		$this->call( ToolsSeeder::class );


		$directories = [
			public_path( 'uploads' . DIRECTORY_SEPARATOR . 'equipments' . DIRECTORY_SEPARATOR ),
		];
		foreach ( $directories as $directory ) {
			File::makeDirectory( $directory, $mode = 0777, true, true );
		}

		$this->command->info( 'Update V1.8 complete: in ' . round( ( microtime( true ) - $start ), 3 ) . 's ...' );

	}
}

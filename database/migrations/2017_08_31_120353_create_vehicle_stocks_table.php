<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVehicleStocksTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create( 'vehicle_stocks', function ( Blueprint $table ) {
			$table->increments( 'id' );
			$table->unsignedInteger( 'idvehicle' );
			$table->foreign( 'idvehicle' )->references( 'id' )->on( 'vehicles' )->onDelete( 'cascade' );
			$table->unsignedInteger( 'idcolaborador' );
			$table->foreign( 'idcolaborador' )->references( 'idcolaborador' )->on( 'colaboradores' )->onDelete( 'cascade' );
			$table->timestamps();
			$table->softDeletes();
		} );
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::drop( 'vehicle_stocks' );
	}
}

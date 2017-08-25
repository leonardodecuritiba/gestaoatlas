<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEquipmentsTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create( 'equipments', function ( Blueprint $table ) {
			$table->increments( 'id' );
			$table->integer( 'idbrand' )->unsigned();
			$table->foreign( 'idbrand' )->references( 'idmarca' )->on( 'marcas' )->onDelete( 'cascade' );

			$table->string( 'description', 100 )->unique();
			$table->string( 'photo', 60 )->nullable();
			$table->string( 'serial_number', 50 );
			$table->string( 'model', 100 );

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
		Schema::drop( 'equipments' );
	}
}

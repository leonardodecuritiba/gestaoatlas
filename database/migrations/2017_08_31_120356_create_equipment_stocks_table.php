<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEquipmentStocksTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create( 'equipment_stocks', function ( Blueprint $table ) {
			$table->increments( 'id' );
			$table->unsignedInteger( 'idequipment' );
			$table->foreign( 'idequipment' )->references( 'id' )->on( 'equipments' )->onDelete( 'cascade' );
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
		Schema::drop( 'equipment_stocks' );
	}
}

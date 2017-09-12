<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInstrumentStocksTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create( 'instrument_stocks', function ( Blueprint $table ) {
			$table->increments( 'id' );
			$table->unsignedInteger( 'idinstrument' );
			$table->foreign( 'idinstrument' )->references( 'id' )->on( 'instruments' )->onDelete( 'cascade' );
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
		Schema::drop( 'instrument_stocks' );
	}
}

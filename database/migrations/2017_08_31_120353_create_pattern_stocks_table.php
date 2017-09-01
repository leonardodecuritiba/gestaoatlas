<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePatternStocksTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create( 'pattern_stocks', function ( Blueprint $table ) {
			$table->increments( 'id' );
			$table->unsignedInteger( 'idpattern' );
			$table->foreign( 'idpattern' )->references( 'id' )->on( 'patterns' )->onDelete( 'cascade' );
			$table->unsignedInteger( 'idcolaborador' );
			$table->foreign( 'idcolaborador' )->references( 'idcolaborador' )->on( 'colaboradores' )->onDelete( 'cascade' );
			$table->unsignedInteger( 'quantity' );
			$table->date( 'expiration' );
			$table->decimal( 'cost', 20, 2 )->default( 0 );
			$table->decimal( 'certification_cost', 20, 2 )->default( 0 );
			$table->string( 'certification', 50 );
			$table->timestamps();
		} );
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::drop( 'pattern_stocks' );
	}
}

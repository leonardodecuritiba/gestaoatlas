<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateToolStocksTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create( 'tool_stocks', function ( Blueprint $table ) {
			$table->increments( 'id' );
			$table->unsignedInteger( 'idtool' );
			$table->foreign( 'idtool' )->references( 'id' )->on( 'tools' )->onDelete( 'cascade' );
			$table->unsignedInteger( 'idcolaborador' );
			$table->foreign( 'idcolaborador' )->references( 'idcolaborador' )->on( 'colaboradores' )->onDelete( 'cascade' );
			$table->unsignedInteger( 'quantity' );
			$table->date( 'expiration' );
			$table->decimal( 'cost', 20, 2 )->default( 0 );
			$table->timestamps();
		} );
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::drop( 'tool_stocks' );
	}
}

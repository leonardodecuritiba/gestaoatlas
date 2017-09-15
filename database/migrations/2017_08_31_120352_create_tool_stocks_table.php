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
			$table->unsignedInteger( 'security_id' )->nullable();
			$table->foreign( 'security_id' )->references( 'id' )->on( 'seguranca_criacaos' )->onDelete( 'SET NULL' );
			$table->unsignedInteger( 'tool_id' );
			$table->foreign( 'tool_id' )->references( 'id' )->on( 'tools' )->onDelete( 'cascade' );
			$table->unsignedInteger( 'owner_id' )->nullable();
			$table->foreign( 'owner_id' )->references( 'idcolaborador' )->on( 'colaboradores' )->onDelete( 'SET NULL' );
			$table->unsignedInteger( 'void_tool_id' )->nullable();
			$table->foreign( 'void_tool_id' )->references( 'id' )->on( 'void_tools' )->onDelete( 'SET NULL' );
			$table->decimal( 'cost', 20, 2 )->default( 0 );
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
		Schema::drop( 'tool_stocks' );
	}
}

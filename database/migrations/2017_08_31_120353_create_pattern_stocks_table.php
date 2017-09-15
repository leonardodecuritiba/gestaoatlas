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
			$table->unsignedInteger( 'security_id' )->nullable();
			$table->foreign( 'security_id' )->references( 'id' )->on( 'seguranca_criacaos' )->onDelete( 'SET NULL' );
			$table->unsignedInteger( 'pattern_id' );
			$table->foreign( 'pattern_id' )->references( 'id' )->on( 'patterns' )->onDelete( 'cascade' );
			$table->unsignedInteger( 'owner_id' )->nullable();
			$table->foreign( 'owner_id' )->references( 'idcolaborador' )->on( 'colaboradores' )->onDelete( 'SET NULL' );
			$table->unsignedInteger( 'void_pattern_id' )->nullable();
			$table->foreign( 'void_pattern_id' )->references( 'id' )->on( 'void_patterns' )->onDelete( 'SET NULL' );

			$table->date( 'expiration' );
			$table->decimal( 'cost', 20, 2 )->default( 0 );
			$table->decimal( 'certification_cost', 20, 2 )->default( 0 );
			$table->string( 'certification', 50 );
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
		Schema::drop( 'pattern_stocks' );
	}
}

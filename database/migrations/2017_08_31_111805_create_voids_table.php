<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVoidsTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create( 'voids', function ( Blueprint $table ) {
			$table->increments( 'id' );
			$table->unsignedInteger( 'security_id' )->nullable();
			$table->foreign( 'security_id' )->references( 'id' )->on( 'seguranca_criacaos' )->onDelete( 'SET NULL' );
			$table->string( 'number', 20 )->unique();
			$table->boolean( 'used' )->default( 0 );
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
		Schema::drop( 'voids' );
	}
}

<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVoidPatternsTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create( 'void_patterns', function ( Blueprint $table ) {
			$table->increments( 'id' );
			$table->unsignedInteger( 'security_id' )->nullable();
			$table->foreign( 'security_id' )->references( 'id' )->on( 'seguranca_criacaos' )->onDelete( 'SET NULL' );
			$table->unsignedInteger( 'void_id' );
			$table->foreign( 'void_id' )->references( 'id' )->on( 'voids' )->onDelete( 'cascade' );
			$table->unsignedInteger( 'enabler_id' )->nullable();
			$table->foreign( 'enabler_id' )->references( 'idcolaborador' )->on( 'colaboradores' )->onDelete( 'SET NULL' );
			$table->unsignedInteger( 'disabler_id' )->nullable();
			$table->foreign( 'disabler_id' )->references( 'idcolaborador' )->on( 'colaboradores' )->onDelete( 'SET NULL' );
			$table->dateTime( 'enabled_at' )->nullable();
			$table->dateTime( 'disabled_at' )->nullable();
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
		Schema::drop( 'void_patterns' );
	}
}

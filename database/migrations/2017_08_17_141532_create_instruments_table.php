<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInstrumentsTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create( 'instruments', function ( Blueprint $table ) {
			$table->increments( 'id' );
			$table->unsignedInteger( 'idbase' );
//	        $table->unsignedInteger('idsetor')->nullable()->default(NULL);
//	        $table->unsignedInteger('idprotecao')->nullable()->default(NULL);
			$table->foreign( 'idbase' )->references( 'id' )->on( 'instrumento_bases' )->onDelete( 'cascade' );
//	        $table->foreign('idsetor')->references('id')->on('instrumento_setors')->onDelete('cascade');
//	        $table->foreign('idprotecao')->references('id')->on('seguranca_criacaos')->onDelete('cascade');

			$table->string( 'serial_number', 50 );
			$table->string( 'inventory', 100 );
//	        $table->string('patrimony',100);
			$table->string( 'year', 4 );

//	        $table->string('etiqueta_inventario', 100)->nullable();
//	        $table->string('etiqueta_identificacao', 100)->nullable();
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
		Schema::drop( 'instruments' );
	}
}

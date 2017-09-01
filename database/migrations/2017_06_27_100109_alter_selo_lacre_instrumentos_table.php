<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterSeloLacreInstrumentosTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::table( 'selo_instrumentos', function ( Blueprint $table ) {
			//renomear idaparelho_manutencao PARA idaparelho_manutencao_set
			//criar idaparelho_manutencao_unset
			$table->renameColumn( 'idaparelho_manutencao', 'idaparelho_set' );
			$table->integer( 'idaparelho_unset' )->unsigned()->nullable();
			$table->foreign( 'idaparelho_unset' )
			      ->references( 'idaparelho_manutencao' )->on( 'aparelho_manutencaos' )->onDelete( 'cascade' );

		} );
		Schema::table( 'lacre_instrumentos', function ( Blueprint $table ) {
			//renomear idaparelho_manutencao PARA idaparelho_manutencao_set
			//criar idaparelho_manutencao_unset
			$table->renameColumn( 'idaparelho_manutencao', 'idaparelho_set' );
			$table->integer( 'idaparelho_unset' )->unsigned()->nullable();
			$table->foreign( 'idaparelho_unset' )
			      ->references( 'idaparelho_manutencao' )->on( 'aparelho_manutencaos' )->onDelete( 'cascade' );

		} );
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::table( 'selo_instrumentos', function ( Blueprint $table ) {
			$table->renameColumn( 'idaparelho_set', 'idaparelho_manutencao' );
		} );
		Schema::table( 'lacre_instrumentos', function ( Blueprint $table ) {
			$table->renameColumn( 'idaparelho_set', 'idaparelho_manutencao' );
		} );
	}
}

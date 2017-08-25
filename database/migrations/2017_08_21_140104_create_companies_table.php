<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompaniesTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create( 'companies', function ( Blueprint $table ) {
			$table->increments( 'id' );

			$table->string( 'cnpj', 100 )->unique();
			$table->string( 'ie', 60 );
			$table->string( 'im', 60 );
			$table->string( 'n_autorizacao', 60 );
			$table->string( 'slogan', 60 );
			$table->string( 'razao_social', 60 );
			$table->string( 'nome_fantasia', 60 );
			$table->string( 'cnae_fiscal', 60 );
			$table->string( 'regime_tributario', 60 );

			$table->string( 'logradouro', 60 );
			$table->string( 'numero', 60 );
			$table->string( 'bairro', 60 );
			$table->string( 'cidade', 60 );
			$table->string( 'estado', 60 );
			$table->string( 'cep', 60 );
			$table->string( 'telefone', 60 );
			$table->string( 'email_os', 60 );

			$table->string( 'modalidade_frete', 60 );
			$table->string( 'icms_servico', 60 );
			$table->string( 'icms_base_calculo', 60 );
			$table->string( 'icms_aliquota', 60 );
			$table->string( 'icms_valor', 60 );
			$table->string( 'icms_cfop', 60 );
			$table->string( 'icms_codigo_municipio', 60 );

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
		Schema::drop( 'companies' );
	}
}

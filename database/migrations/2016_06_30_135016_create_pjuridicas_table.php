<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePjuridicasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pjuridicas', function (Blueprint $table) {
            $table->increments('idpjuridica');

            $table->string('cnpj',60);
            $table->string('ie',60);
            $table->boolean('isencao_ie')->default(0);
            $table->string('razao_social',100);
            $table->string('nome_fantasia',100);
            $table->string('ativ_economica',100);
            $table->string('sit_cad_vigente',50);
            $table->string('sit_cad_status',50);
            $table->date('data_sit_cad');
            $table->string('reg_apuracao',100);
            $table->date('data_credenciamento');
            $table->string('ind_obrigatoriedade',100);
            $table->date('data_ini_obrigatoriedade');

/*
            $table->string('numero_inscricao',60);
            $table->date('data_abertura');
            $table->string('nome_empresarial',100);
            $table->string('nome_fantasia',100);
            $table->string('cod_atividade_principal',20);
            $table->string('desc_atividade_principal',100);
            $table->string('cod_atividade_secundaria',20)->nullable();
            $table->string('desc_atividade_secundaria',100)->nullable();
            $table->string('cod_natureza_juridica',10);
            $table->string('desc_natureza_juridica',100);
            $table->string('ent_fed_responsavel',100);
            $table->string('situacao_cadastral',20);
            $table->date('data_situacao_cadastral');
            $table->string('mot_situacao_cadastral',100)->nullable();
            $table->string('situacao_especial',100)->nullable();
            $table->date('data_situacao_especial',11);

*/
            $table->timestamps();
            $table->softDeletes();
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('pjuridicas');
    }
}

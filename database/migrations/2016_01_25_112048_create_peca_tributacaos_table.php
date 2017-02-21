<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePecaTributacaosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('peca_tributacaos', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('idcfop')->unsigned();
            $table->foreign('idcfop')->references('id')->on('cfops')->onDelete('cascade');

            $table->integer('idcst')->unsigned();
            $table->foreign('idcst')->references('id')->on('csts')->onDelete('cascade');

            $table->integer('idnatureza_operacao')->unsigned();
            $table->foreign('idnatureza_operacao')->references('id')->on('natureza_operacaos')->onDelete('cascade');

            $table->integer('idncm')->unsigned();
            $table->foreign('idncm')->references('idncm')->on('ncm')->onDelete('cascade');
            $table->foreign('idncm')->references('idncm')->on('ncm')->onDelete('cascade');

            $table->integer('cest')->unsigned();
            $table->decimal('icms_base_calculo', 5, 2)->default(0);
            $table->decimal('icms_valor_total', 5, 2)->default(0);
            $table->decimal('icms_base_calculo_st', 5, 2)->default(0);
            $table->decimal('icms_valor_total_st', 5, 2)->default(0);

            $table->decimal('valor_ipi', 5, 2)->default(0);
            $table->decimal('valor_unitario_tributavel', 5, 2)->default(0);
            $table->decimal('icms_situacao_tributaria', 5, 2)->default(0);
            $table->decimal('icms_origem', 5, 2)->default(0);
            $table->decimal('pis_situacao_tributaria', 5, 2)->default(0);

            $table->decimal('valor_frete', 11, 2)->default(0);
            $table->decimal('valor_seguro', 11, 2)->default(0);
            $table->decimal('custo_final', 11, 2);

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('peca_tributacaos');
    }
}

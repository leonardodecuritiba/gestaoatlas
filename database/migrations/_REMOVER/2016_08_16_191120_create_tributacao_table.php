<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTributacaoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tributacao', function (Blueprint $table) {
            $table->increments('idtributacao');

            $table->integer('idncm')->unsigned();
            $table->foreign('idncm')->references('idncm')->on('ncm')->onDelete('cascade');

            $table->integer('idcategoria_tributacao')->unsigned();
            $table->foreign('idcategoria_tributacao')->references('idcategoria_tributacao')->on('categoria_tributacao')->onDelete('cascade');

            $table->integer('idorigem_tributacao')->unsigned();
            $table->foreign('idorigem_tributacao')->references('idorigem_tributacao')->on('origem_tributacao')->onDelete('cascade');

            $table->integer('idcst_ipi')->unsigned();
            $table->foreign('idcst_ipi')->references('idcst_ipi')->on('cst_ipi')->onDelete('cascade');

            $table->decimal('peso_liquido',11,2);
            $table->decimal('peso_bruto',11,2);
            $table->decimal('ipi',5,2);

            $table->boolean('isencao_icms')->default(0);
            $table->boolean('ipi_venda')->default(0);
            $table->boolean('reducao_icms')->default(0);

            $table->decimal('icms',11,2);
            $table->decimal('reducao_bc_icms',5,2);
            $table->decimal('reducao_bc_icms_st',5,2);
            $table->decimal('aliquota_icms',5,2);


            $table->decimal('aliquota_ii',5,2)->nullable();
            $table->decimal('icms_importacao',11,2)->nullable();
            $table->decimal('aliquota_cofins_importacao',5,2)->nullable();
            $table->decimal('aliquota_pis_importacao',5,2)->nullable();

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
        Schema::drop('tributacao');
    }
}

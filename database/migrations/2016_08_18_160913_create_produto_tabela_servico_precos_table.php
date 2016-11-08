<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProdutoTabelaServicoPrecosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('produto_tabela_servico_precos', function (Blueprint $table) {
            $table->increments('idproduto_tabela_servico_precos');

            $table->integer('idtabela_preco')->unsigned();
            $table->foreign('idtabela_preco')->references('idtabela_preco')->on('tabela_precos')->onDelete('cascade');

            $table->integer('idservico')->unsigned();
            $table->foreign('idservico')->references('idservico')->on('servicos')->onDelete('cascade');

            $table->decimal('preco',11,2);
            $table->decimal('preco_minimo',11,2);
            $table->decimal('margem',11,2);
            $table->decimal('margem_minimo',11,2);
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
        Schema::drop('produto_tabela_servico_precos');
    }
}

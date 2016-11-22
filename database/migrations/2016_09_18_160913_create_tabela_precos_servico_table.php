<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTabelaPrecosServicoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tabela_precos_servico', function (Blueprint $table) {
            $table->increments('id');

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
        Schema::drop('tabela_precos_servico');
    }
}

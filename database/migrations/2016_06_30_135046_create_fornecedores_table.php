<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFornecedoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fornecedores', function (Blueprint $table) {
            $table->increments('idfornecedor');

            $table->integer('idcontato')->unsigned();
            $table->foreign('idcontato')->references('idcontato')->on('contatos')->onDelete('cascade');

            $table->integer('idpjuridica')->unsigned()->nullable();
            $table->foreign('idpjuridica')->references('idpjuridica')->on('pjuridicas')->onDelete('cascade');

            $table->integer('idpfisica')->unsigned()->nullable();
            $table->foreign('idpfisica')->references('idpfisica')->on('pfisicas')->onDelete('cascade');

            $table->integer('idsegmento_fornecedor')->unsigned()->nullable();
            $table->foreign('idsegmento_fornecedor')->references('idsegmento_fornecedor')->on('segmentos_fornecedores')->onDelete('cascade');

            $table->string('email_orcamento',60);
            $table->string('grupo',100);
            $table->string('nome_responsavel',100)->nullable();
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
        Schema::drop('fornecedores');
    }
}

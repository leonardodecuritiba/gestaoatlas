<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFaturamentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('faturamentos', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('idcliente')->unsigned();
            $table->foreign('idcliente')->references('idcliente')->on('clientes')->onDelete('cascade');

            $table->unsignedInteger('idstatus_faturamento')->nullable();
            $table->foreign('idstatus_faturamento')->references('id')->on('status_faturamento')->onDelete('SET NULL');

            $table->integer('idpagamento')->unsigned();
            $table->foreign('idpagamento')->references('id')->on('pagamentos')->onDelete('cascade');

            $table->integer('idnfe_homologacao')->unsigned()->nullable();
            $table->integer('idnfe_producao')->unsigned()->nullable();
            $table->integer('idnfse_homologacao')->unsigned()->nullable();
            $table->integer('idnfse_producao')->unsigned()->nullable();

            $table->dateTime('data_nfe_homologacao')->nullable();
            $table->dateTime('data_nfe_producao')->nullable();
            $table->dateTime('data_nfse_homologacao')->nullable();
            $table->dateTime('data_nfse_producao')->nullable();

            $table->string('link_nfe', 200)->nullable();
            $table->string('link_nfse', 200)->nullable();

            $table->boolean('centro_custo')->default(0);

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
        Schema::drop('faturamentos');
    }
}

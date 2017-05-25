<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFechamentosTable extends Migration
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

            $table->unsignedInteger('idstatus_fechamento')->nullable();
            $table->foreign('idstatus_fechamento')->references('id')->on('status_fechamento')->onDelete('SET NULL');

            $table->integer('idpagamento')->unsigned();
            $table->foreign('idpagamento')->references('id')->on('pagamentos')->onDelete('cascade');

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

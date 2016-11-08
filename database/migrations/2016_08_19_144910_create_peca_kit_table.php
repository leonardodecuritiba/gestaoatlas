<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePecaKitTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('peca_kit', function (Blueprint $table) {
            $table->increments('idpeca_kit');

            $table->integer('idkit')->unsigned();
            $table->foreign('idkit')->references('idkit')->on('kits')->onDelete('cascade');

            $table->integer('idpeca')->unsigned();
            $table->foreign('idpeca')->references('idpeca')->on('pecas')->onDelete('cascade');

            $table->integer('quantidade')->unsigned();
            $table->decimal('valor_unidade',11,2);
            $table->decimal('valor_total',11,2);
            $table->string('descricao_adicional',200)->nullable();
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
        Schema::drop('peca_kit');
    }
}

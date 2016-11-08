<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateColaboradoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('colaboradores', function (Blueprint $table) {
            $table->increments('idcolaborador');

            $table->integer('idcontato')->unsigned();
            $table->foreign('idcontato')->references('idcontato')->on('contatos')->onDelete('cascade');

            $table->integer('iduser')->unsigned();
            $table->foreign('iduser')->references('iduser')->on('users')->onDelete('cascade');

            $table->string('nome',100);
            $table->string('cpf',16);
            $table->string('rg',10);
            $table->date('data_nascimento');
            $table->string('cnh',60);
            $table->string('carteira_trabalho',60);
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
        Schema::drop('colaboradores');
    }
}

<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContatosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contatos', function (Blueprint $table) {
            $table->increments('idcontato');
            $table->string('telefone',11)->nullable();
            $table->string('celular',11)->nullable();
            $table->string('skype',100)->nullable();
            $table->string('cep',16)->nullable();
            $table->string('estado',72)->nullable();
            $table->string('cidade',60)->nullable();
            $table->string('bairro',72)->nullable();
            $table->string('logradouro',125)->nullable();
            $table->string('numero',7)->nullable();
            $table->string('complemento',50)->nullable();
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
        Schema::drop('contatos');
    }
}

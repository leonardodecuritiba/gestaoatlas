<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEquipamentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('equipamentos', function (Blueprint $table) {
            $table->increments('idequipamento');
            $table->integer('idcliente')->unsigned();
            $table->foreign('idcliente')->references('idcliente')->on('clientes')->onDelete('cascade');

            $table->integer('idmarca')->unsigned()->nullable();
            $table->foreign('idmarca')->references('idmarca')->on('marcas')->onDelete('cascade');

            $table->integer('idcolaborador_criador')->unsigned();
            $table->foreign('idcolaborador_criador')->references('idcolaborador')->on('colaboradores')->onDelete('cascade');

            $table->integer('idcolaborador_validador')->unsigned();
            $table->foreign('idcolaborador_validador')->references('idcolaborador')->on('colaboradores')->onDelete('cascade');

            $table->timestamp('validated_at')->nullable();
            $table->string('descricao',100);
            $table->string('foto',60)->nullable();
            $table->string('modelo',100);
            $table->string('numero_serie',50);
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
        Schema::drop('equipamentos');
    }
}

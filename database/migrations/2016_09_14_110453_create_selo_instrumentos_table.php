<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSeloInstrumentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('selo_instrumentos', function (Blueprint $table) {
            $table->increments('idselo_instrumento');

            $table->integer('idinstrumento')->unsigned();
            $table->foreign('idinstrumento')->references('idinstrumento')->on('instrumentos')->onDelete('cascade');

            $table->integer('idselo')->unsigned();
            $table->foreign('idselo')->references('idselo')->on('selos')->onDelete('cascade');

            $table->dateTime('afixado_em')->nullable();
            $table->dateTime('retirado_em')->nullable();
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
        Schema::drop('selo_instrumentos');
    }
}

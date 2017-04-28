<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInstrumentoBasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('instrumento_bases', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('idmodelo');
            $table->foreign('idmodelo')->references('id')->on('instrumento_modelos')->onDelete('cascade');

            $table->string('descricao', 100);
            $table->string('divisao', 100);
            $table->string('portaria', 100);
            $table->string('capacidade', 100);
            $table->string('foto', 60)->nullable();

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
        Schema::drop('instrumento_bases');
    }
}

<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInstrumentoModelosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('instrumento_modelos', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('idinstrumento_marca');
            $table->foreign('idinstrumento_marca')->references('id')->on('instrumento_marcas')->onDelete('cascade');
            $table->string('descricao', 100)->unique();
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
        Schema::drop('instrumento_modelos');
    }
}

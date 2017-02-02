<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLacreInstrumentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lacre_instrumentos', function (Blueprint $table) {
            $table->increments('idlacre_instrumento');
            $table->integer('idinstrumento')->unsigned();
            $table->foreign('idinstrumento')->references('idinstrumento')->on('instrumentos')->onDelete('cascade');

            $table->integer('idaparelho_manutencao')->unsigned();
            $table->foreign('idaparelho_manutencao')->references('idaparelho_manutencao')->on('aparelho_manutencaos')->onDelete('cascade');

            $table->integer('idlacre')->unsigned();
            $table->foreign('idlacre')->references('idlacre')->on('lacres')->onDelete('cascade');

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
        Schema::drop('lacre_instrumentos');
    }
}

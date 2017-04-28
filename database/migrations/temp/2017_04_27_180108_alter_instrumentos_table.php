<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterInstrumentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('instrumentos', function (Blueprint $table) {
            $table->unsignedInteger('idbase');
            $table->unsignedInteger('idprotecao');
            $table->unsignedInteger('idsetor');
            $table->foreign('idbase')->references('id')->on('instrumento_bases')->onDelete('cascade');
            $table->foreign('idprotecao')->references('id')->on('seguranca_criacaos')->onDelete('cascade');
            $table->foreign('idsetor')->references('id')->on('instrumento_setors')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('instrumentos', function (Blueprint $table) {
            $table->dropForeign('idbase');
            $table->dropForeign('idprotecao');
            $table->dropForeign('idsetor');
        });
    }
}

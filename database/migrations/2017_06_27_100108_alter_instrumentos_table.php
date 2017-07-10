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
            $table->unsignedInteger('idbase')->nullable()->default(NULL);
            $table->unsignedInteger('idsetor')->nullable()->default(NULL);
            $table->unsignedInteger('idprotecao')->nullable()->default(NULL);
            $table->foreign('idbase')->references('id')->on('instrumento_bases')->onDelete('cascade');
            $table->foreign('idsetor')->references('id')->on('instrumento_setors')->onDelete('cascade');
            $table->foreign('idprotecao')->references('id')->on('seguranca_criacaos')->onDelete('cascade');
            $table->string('etiqueta_inventario', 100)->nullable();
            $table->string('etiqueta_identificacao', 100)->nullable();
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
            $table->dropForeign('idsetor');
            $table->dropForeign('idprotecao');

        });
    }
}

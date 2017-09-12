<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSegurancaCriacaosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seguranca_criacaos', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('idcriador');
            $table->foreign('idcriador')->references('idcolaborador')->on('colaboradores')->onDelete('cascade');

            $table->unsignedInteger('idvalidador')->nullable();
            $table->foreign('idvalidador')->references('idcolaborador')->on('colaboradores')->onDelete('cascade');

	        $table->string( 'verb', 6 )->nullable();
            $table->timestamp('validated_at')->nullable();
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
        Schema::drop('seguranca_criacaos');
    }
}

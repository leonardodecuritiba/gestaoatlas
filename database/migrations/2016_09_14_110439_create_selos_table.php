<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSelosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('selos', function (Blueprint $table) {
            $table->increments('idselo');
            $table->unsignedInteger('idtecnico');

            $table->foreign('idtecnico')->references('idtecnico')->on('tecnicos')->onDelete('cascade');

            $table->string('numeracao',20)->unique()->nullable();
            $table->string('numeracao_externa',20)->nullable();
            $table->boolean('externo')->default(0);
            $table->boolean('used')->default(0);
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
        Schema::drop('selos');
    }
}

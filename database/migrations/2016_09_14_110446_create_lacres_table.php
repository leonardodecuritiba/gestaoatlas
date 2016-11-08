<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLacresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lacres', function (Blueprint $table) {
            $table->increments('idlacre');
            $table->unsignedInteger('idtecnico');

            $table->foreign('idtecnico')->references('idtecnico')->on('tecnicos')->onDelete('cascade');

            $table->string('numeracao',20)->unique()->nullable();
            $table->string('numeracao_externa',20)->nullable();
            $table->boolean('externo')->default(0);
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
        Schema::drop('lacres');
    }
}

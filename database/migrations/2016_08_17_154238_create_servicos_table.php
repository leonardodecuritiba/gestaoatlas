<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateServicosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('servicos', function (Blueprint $table) {
            $table->increments('idservico');

            $table->integer('idgrupo')->unsigned()->nullable();
            $table->foreign('idgrupo')->references('idgrupo')->on('grupos')->onDelete('cascade');

            $table->integer('idunidade')->unsigned()->nullable();
            $table->foreign('idunidade')->references('idunidade')->on('unidades')->onDelete('cascade');

            $table->string('nome',100)->unique();
            $table->string('descricao',255)->nullable();
            $table->decimal('valor',11,2);
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
        Schema::drop('servicos');
    }
}

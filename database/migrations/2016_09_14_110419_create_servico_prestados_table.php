<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateServicoPrestadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('servico_prestados', function (Blueprint $table) {
            $table->increments('idservico_prestado');

            $table->unsignedInteger('idaparelho_manutencao');
            $table->foreign('idaparelho_manutencao')->references('idaparelho_manutencao')->on('aparelho_manutencaos')->onDelete('cascade');

            $table->integer('idservico')->unsigned();
            $table->foreign('idservico')->references('idservico')->on('servicos')->onDelete('cascade');

            $table->decimal('valor',11,2);
            $table->smallInteger('quantidade');
            $table->decimal('desconto', 11, 2)->default(0);
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
        Schema::drop('servico_prestados');
    }
}

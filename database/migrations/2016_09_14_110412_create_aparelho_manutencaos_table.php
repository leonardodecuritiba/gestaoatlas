<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAparelhoManutencaosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('aparelho_manutencaos', function (Blueprint $table) {
            $table->increments('idaparelho_manutencao');

            $table->integer('idordem_servico')->unsigned();
            $table->foreign('idordem_servico')->references('idordem_servico')->on('ordem_servicos')->onDelete('cascade');

            $table->integer('idinstrumento')->unsigned()->nullable();
            $table->foreign('idinstrumento')->references('idinstrumento')->on('instrumentos')->onDelete('SET NULL');

            $table->integer('idequipamento')->unsigned()->nullable();
            $table->foreign('idequipamento')->references('idequipamento')->on('equipamentos')->onDelete('SET NULL');

//            $table->unique(array('idordem_servico', 'idinstrumento', 'idequipamento'));
            $table->string('defeito',500);
            $table->string('solucao',500);
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
        Schema::drop('aparelho_manutencaos');
    }
}

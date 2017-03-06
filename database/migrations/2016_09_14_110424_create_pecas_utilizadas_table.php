<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePecasUtilizadasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pecas_utilizadas', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('idaparelho_manutencao');
            $table->foreign('idaparelho_manutencao')->references('idaparelho_manutencao')->on('aparelho_manutencaos')->onDelete('cascade');

            $table->integer('idpeca')->unsigned()->nullable();
            $table->foreign('idpeca')->references('idpeca')->on('pecas')->onDelete('cascade');
            
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
        Schema::drop('insumos');
    }
}

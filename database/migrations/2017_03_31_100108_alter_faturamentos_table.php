<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterFaturamentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('faturamentos', function (Blueprint $table) {
            $table->integer('idnfe_homologacao')->unsigned()->nullable();
            $table->integer('idnfe_producao')->unsigned()->nullable();
            $table->integer('idnfse_homologacao')->unsigned()->nullable();
            $table->integer('idnfse_producao')->unsigned()->nullable();

            $table->dateTime('data_nfe_homologacao')->nullable();
            $table->dateTime('data_nfe_producao')->nullable();
            $table->dateTime('data_nfse_homologacao')->nullable();
            $table->dateTime('data_nfse_producao')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->dropForeign('idnfe_homologacao');
            $table->dropForeign('idnfe_producao');
            $table->dropForeign('idnfse_homologacao');
            $table->dropForeign('idnfse_producao');

            $table->dateTime('data_nfe_homologacao')->nullable();
            $table->dateTime('data_nfe_producao')->nullable();
            $table->dateTime('data_nfse_homologacao')->nullable();
            $table->dateTime('data_nfse_producao')->nullable();
        });
    }
}

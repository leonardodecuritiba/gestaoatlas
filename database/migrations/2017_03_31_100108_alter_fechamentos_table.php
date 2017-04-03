<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterFechamentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fechamentos', function (Blueprint $table) {
            $table->integer('idnfe_homologacao')->unsigned()->nullable();
            $table->integer('idnfe_producao')->unsigned()->nullable();
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

        });
    }
}

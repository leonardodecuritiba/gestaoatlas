<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStatusParcelasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('status_parcelas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('descricao', 100)->unique();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('parcelas', function (Blueprint $table) {
            $table->unsignedInteger('idstatus_parcela')->after('idpagamento')->nullable();
            $table->foreign('idstatus_parcela')->references('id')->on('status_parcelas')->onDelete('cascade');;
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('status_parcelas');
        Schema::table('parcelas', function (Blueprint $table) {
            $table->dropPrimary('idstatus_parcela');
        });
    }
}

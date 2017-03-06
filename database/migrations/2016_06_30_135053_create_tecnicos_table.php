<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTecnicosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tecnicos', function (Blueprint $table) {
            $table->increments('idtecnico');

            $table->integer('idcolaborador')->unsigned();
            $table->foreign('idcolaborador')->references('idcolaborador')->on('colaboradores')->onDelete('cascade');

            $table->string('carteira_imetro',60);
            $table->string('carteira_ipem',60);
            $table->decimal('desconto_max', 5, 2)->default(0);
            $table->decimal('acrescimo_max', 5, 2)->default(0);
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
        Schema::drop('tecnicos');
    }
}

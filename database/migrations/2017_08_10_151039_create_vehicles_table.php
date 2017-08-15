<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVehiclesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_api');
            $table->integer('ano_modelo');
            $table->string('tipo', 9);
            $table->string('marca', 50);
            $table->string('name', 100);
            $table->string('veiculo', 100);
            $table->decimal('preco', 20, 2);
            $table->string('combustivel', 50);
            $table->string('referencia', 50);
            $table->string('fipe_codigo', 50);
            $table->string('key', 50);
            $table->string('plate', 7)->unique();
            $table->string('renavam', 12);
            $table->integer('km');
            $table->integer('tires');
            $table->integer('oil');
            $table->integer('filter');
            $table->date('wash');
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
        Schema::drop('vehicles');
    }
}

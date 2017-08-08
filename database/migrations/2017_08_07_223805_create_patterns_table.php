<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePatternsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patterns', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('idunit');
            $table->foreign('idunit')->references('idunidade')->on('unidades')->onDelete('cascade');
            $table->string('description', 100);
            $table->string('brand', 50);
            $table->decimal('measure', 20, 2);
            $table->decimal('cost', 20, 2);
            $table->decimal('cost_certification', 20, 2);
            $table->string('certification', 20);
            $table->string('class', 20);
            $table->date('expiration');
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
        Schema::drop('patterns');
    }
}

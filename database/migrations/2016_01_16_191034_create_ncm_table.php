<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNcmTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ncm', function (Blueprint $table) {
            $table->increments('idncm');
            $table->string('codigo',50)->unique();
            $table->string('descricao',100)->nullable();
            $table->decimal('aliquota_ipi',5,2)->default(0);
            $table->decimal('aliquota_pis',5,2)->default(0);
            $table->decimal('aliquota_cofins',5,2)->default(0);
            $table->decimal('aliquota_nacional',5,2)->default(0);
            $table->decimal('aliquota_importacao',5,2)->default(0);
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
        Schema::drop('ncm');
    }
}

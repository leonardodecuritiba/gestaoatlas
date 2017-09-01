<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateToolsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tools', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('idbrand');
            $table->foreign('idbrand')->references('id')->on('brands')->onDelete('cascade');
            $table->unsignedInteger('idcategory');
            $table->foreign('idcategory')->references('id')->on('tool_categories')->onDelete('cascade');
            $table->unsignedInteger('idunit');
            $table->foreign('idunit')->references('idunidade')->on('unidades')->onDelete('cascade');
            $table->string('description', 100)->unique();
            $table->decimal('cost', 20, 2)->default(0);
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
        Schema::drop('tools');
    }
}

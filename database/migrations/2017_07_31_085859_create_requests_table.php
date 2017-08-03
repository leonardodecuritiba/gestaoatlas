<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('requests', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('idtype');
            $table->foreign('idtype')->references('id')->on('type_requests')->onDelete('cascade');
            $table->unsignedInteger('idstatus');
            $table->foreign('idstatus')->references('id')->on('status_requests')->onDelete('cascade');
            $table->unsignedInteger('idrequester');
            $table->foreign('idrequester')->references('idcolaborador')->on('colaboradores')->onDelete('cascade');
            $table->unsignedInteger('idmanager')->nullable();
            $table->foreign('idmanager')->references('idcolaborador')->on('colaboradores')->onDelete('SET NULL');
            $table->mediumText('parameters');
            $table->mediumText('response')->nullable();
            $table->dateTime('enddate')->nullable();
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
        Schema::drop('requests');
    }
}

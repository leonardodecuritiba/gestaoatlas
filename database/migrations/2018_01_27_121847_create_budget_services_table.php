<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBudgetServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('budget_services', function (Blueprint $table) {
            $table->increments('id');

	        $table->integer('budget_id')->unsigned();
	        $table->foreign('budget_id')->references('id')->on('budgets')->onDelete('cascade');

	        $table->integer('service_id')->unsigned()->nullable();
	        $table->foreign('service_id')->references('idservico')->on('servicos')->onDelete('cascade');

	        //Values
	        $table->decimal('value',11,2);
	        $table->smallInteger('quantity');
	        $table->decimal('discount', 11, 1)->default(0);

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
        Schema::drop('budget_services');
    }
}

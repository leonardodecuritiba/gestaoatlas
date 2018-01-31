<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBudgetPartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('budget_parts', function (Blueprint $table) {
	        $table->increments('id');

	        $table->integer('budget_id')->unsigned();
	        $table->foreign('budget_id')->references('id')->on('budgets')->onDelete('cascade');

	        $table->integer('part_id')->unsigned()->nullable();
	        $table->foreign('part_id')->references('idpeca')->on('pecas')->onDelete('cascade');

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
        Schema::drop('budget_parts');
    }
}

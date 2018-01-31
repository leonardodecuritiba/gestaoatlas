<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBudgetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('budgets', function (Blueprint $table) {
            $table->increments('id');

	        $table->integer('client_id')->unsigned();
	        $table->foreign('client_id')->references('idcliente')->on('clientes')->onDelete('cascade');

	        $table->integer('collaborator_id')->unsigned();
	        $table->foreign('collaborator_id')->references('idcolaborador')->on('colaboradores')->onDelete('cascade');

	        $table->integer('situation_id')->unsigned()->default(0);

	        //Responsible
	        $table->string('responsible', 100);
	        $table->string('responsible_cpf', 16);
	        $table->string('responsible_office', 50);

	        //Values
	        $table->decimal('value_total',11,2);
	        $table->decimal('value_end',11,2);

	        //Discounts
	        $table->decimal('discount_master', 5, 2)->default(0);
	        $table->decimal('discount_technician', 5, 2)->default(0);
	        //Increases
	        $table->decimal('increase_technician', 5, 2)->default(0);

	        //Costs
	        $table->decimal('cost_displacement',11,2)->default(0);
	        $table->decimal('cost_toll',11,2)->default(0);
	        $table->decimal('cost_other',11,2)->default(0);
	        $table->boolean('cost_exemption')->default(0);

	        $table->dateTime('closed_at')->nullable();

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
        Schema::drop('budgets');
    }
}

<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDeclaredToSelosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('selos', function (Blueprint $table) {
	        $table->dateTime( 'declared' )->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('selos', function (Blueprint $table) {
	        $table->dropColumn( 'declared' );
        });
    }
}

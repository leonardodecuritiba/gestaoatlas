<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddExternalToSeloLacreTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	    Schema::table('selo_instrumentos', function (Blueprint $table) {
		    $table->boolean( 'external' )->default(0);
	    });
	    Schema::table('lacre_instrumentos', function (Blueprint $table) {
		    $table->boolean( 'external' )->default(0);
	    });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
//	    Schema::table('selo_instrumentos', function (Blueprint $table) {
//		    $table->dropColumn('external');
//	    });
//	    Schema::table('lacre_instrumentos', function (Blueprint $table) {
//		    $table->dropColumn('external');
//	    });
    }
}

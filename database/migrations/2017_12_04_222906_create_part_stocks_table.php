<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePartStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('part_stocks', function (Blueprint $table) {
            $table->increments('id');
	        $table->unsignedInteger( 'security_id' )->nullable();
	        $table->foreign( 'security_id' )->references( 'id' )->on( 'seguranca_criacaos' )->onDelete( 'SET NULL' );
	        $table->unsignedInteger( 'part_id' );
	        $table->foreign( 'part_id' )->references( 'idpeca' )->on( 'pecas' )->onDelete( 'cascade' );
	        $table->unsignedInteger( 'owner_id' )->nullable();
	        $table->foreign( 'owner_id' )->references( 'idcolaborador' )->on( 'colaboradores' )->onDelete( 'SET NULL' );
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
        Schema::drop('part_stocks');
    }
}

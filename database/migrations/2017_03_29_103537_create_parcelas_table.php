<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateParcelasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parcelas', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('idpagamento')->unsigned();
            $table->foreign('idpagamento')->references('id')->on('pagamentos')->onDelete('cascade');

            $table->unsignedInteger('idforma_pagamento')->nullable();
            $table->foreign('idforma_pagamento')->references('idforma_pagamento')->on('formas_pagamentos')->onDelete('SET NULL');

            $table->date('data_vencimento');
            $table->date('data_pagamento')->nullable();
            $table->date('data_baixa')->nullable();
            $table->tinyInteger('numero_parcela');
            $table->boolean('status')->default(0);
            $table->decimal('valor_parcela', 11, 2);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('parcelas');
    }
}

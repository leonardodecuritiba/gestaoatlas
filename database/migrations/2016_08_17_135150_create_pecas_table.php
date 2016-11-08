<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePecasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pecas', function (Blueprint $table) {
            $table->increments('idpeca');

            $table->integer('idfornecedor')->unsigned()->nullable();
            $table->foreign('idfornecedor')->references('idfornecedor')->on('fornecedores')->onDelete('cascade');

//            $table->integer('idtributacao')->unsigned();
//            $table->foreign('idtributacao')->references('idtributacao')->on('tributacao')->onDelete('cascade');

            $table->integer('idmarca')->unsigned()->nullable();
            $table->foreign('idmarca')->references('idmarca')->on('marcas')->onDelete('cascade');

            $table->integer('idgrupo')->unsigned()->nullable();
            $table->foreign('idgrupo')->references('idgrupo')->on('grupos')->onDelete('cascade');

            $table->integer('idunidade')->unsigned()->nullable();
            $table->foreign('idunidade')->references('idunidade')->on('unidades')->onDelete('cascade');

            $table->enum('tipo', array('peca', 'produto'));

            $table->string('codigo',50);
            $table->string('codigo_auxiliar',50)->nullable();
            $table->string('codigo_barras',50)->nullable();
            $table->string('descricao',100);
            $table->string('descricao_tecnico',100);
            $table->string('foto',60)->nullable();
            $table->string('sub_grupo',50)->nullable();
            $table->tinyInteger('garantia')->nullable();
            $table->decimal('comissao_tecnico',5,2);
            $table->decimal('comissao_vendedor',5,2);

            //CUSTO REAL
            $table->decimal('custo_final',11,2);
            /*
            $table->decimal('custo_compra',11,2);
            $table->decimal('custo_frete',11,2);
            $table->decimal('custo_imposto',5,2);
            $table->decimal('custo_final',11,2);

            //CUSTO DOLAR
            $table->decimal('custo_dolar',11,2)->nullable();
            $table->decimal('custo_dolar_frete',11,2)->nullable();
            $table->decimal('custo_dolar_cambio',11,2)->nullable();
            $table->decimal('custo_dolar_imposto',11,2)->nullable();

            //PREÇO REAL
            $table->decimal('preco',11,2);
            $table->decimal('preco_frete',11,2);
            $table->decimal('preco_imposto',11,2);
            $table->decimal('preco_final',11,2);
            */

            /*
            $table->tinyInteger('garantia');
            $table->string('taxacao',50);
            $table->integer('estoque_min');
            $table->integer('estoque_med');
            $table->integer('estoque_max');

            */

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
        Schema::drop('pecas');
    }
}

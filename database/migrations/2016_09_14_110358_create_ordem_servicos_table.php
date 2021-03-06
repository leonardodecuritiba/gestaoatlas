<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdemServicosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ordem_servicos', function (Blueprint $table) {
            $table->increments('idordem_servico');

            $table->integer('idcliente')->unsigned();
            $table->foreign('idcliente')->references('idcliente')->on('clientes')->onDelete('cascade');

            $table->integer('idcolaborador')->unsigned();
            $table->foreign('idcolaborador')->references('idcolaborador')->on('colaboradores')->onDelete('cascade');

            $table->integer('idsituacao_ordem_servico')->unsigned()->nullable();
            $table->foreign('idsituacao_ordem_servico')->references('idsituacao_ordem_servico')->on('situacao_ordem_servicos')->onDelete('SET NULL');

            $table->integer('idcentro_custo')->unsigned()->nullable();
            $table->foreign('idcentro_custo')->references('idcliente')->on('clientes')->onDelete('SET NULL');

            $table->integer('idfaturamento')->unsigned()->nullable()->default(NULL);
            $table->foreign('idfaturamento')->references('id')->on('faturamentos')->onDelete('SET NULL');

            $table->dateTime('data_finalizada')->nullable();
            $table->dateTime('data_fechada')->nullable();
            $table->string('numero_chamado',50)->nullable();
            $table->string('responsavel', 100);
            $table->string('responsavel_cpf', 16);
            $table->string('responsavel_cargo', 50);
            $table->decimal('valor_total',11,2);
            $table->decimal('desconto_tec', 5, 2)->default(0);
            $table->decimal('acrescimo_tec', 5, 2)->default(0);
            $table->decimal('valor_final',11,2);

            $table->decimal('custos_deslocamento',11,2);
            $table->decimal('pedagios',11,2); //em R$
            $table->decimal('outros_custos',11,2); //em R$
            $table->boolean('custos_isento')->default(0);

            $table->boolean('validacao')->default(0);
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
        Schema::drop('ordem_servicos');
    }
}

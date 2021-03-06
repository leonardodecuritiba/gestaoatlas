    <?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->increments('idcliente');
            $table->integer('idcontato')->unsigned();
            $table->foreign('idcontato')->references('idcontato')->on('contatos')->onDelete('cascade');

            $table->integer('idcliente_centro_custo')->unsigned()->nullable();
            $table->foreign('idcliente_centro_custo')->references('idcliente')->on('clientes')->onDelete('SET NULL');

            $table->integer('idpjuridica')->unsigned()->nullable();
            $table->foreign('idpjuridica')->references('idpjuridica')->on('pjuridicas')->onDelete('cascade');

            $table->integer('idpfisica')->unsigned()->nullable();
            $table->foreign('idpfisica')->references('idpfisica')->on('pfisicas')->onDelete('cascade');

            $table->integer('idsegmento')->unsigned();
            $table->foreign('idsegmento')->references('idsegmento')->on('segmentos')->onDelete('cascade');

            $table->integer('idtabela_preco')->unsigned();
            $table->foreign('idtabela_preco')->references('idtabela_preco')->on('tabela_precos')->onDelete('cascade');

            $table->integer('idregiao')->unsigned();
            $table->foreign('idregiao')->references('idregiao')->on('regioes')->onDelete('cascade');

            $table->integer('idforma_pagamento')->unsigned();
            $table->foreign('idforma_pagamento')->references('idforma_pagamento')->on('formas_pagamentos')->onDelete('cascade');

            $table->integer('idcolaborador_criador')->unsigned();
            $table->foreign('idcolaborador_criador')->references('idcolaborador')->on('colaboradores')->onDelete('cascade');

            $table->integer('idcolaborador_validador')->unsigned()->nullable();
            $table->foreign('idcolaborador_validador')->references('idcolaborador')->on('colaboradores')->onDelete('cascade');

            //comercial
            $table->unsignedInteger('idforma_pagamento_comercial')->nullable()->default(NULL);
            $table->foreign('idforma_pagamento_comercial')->references('idforma_pagamento')->on('formas_pagamentos')->onDelete('SET NULL');

            $table->string('prazo_pagamento_comercial', 100)->nullable()->default(NULL);

            $table->unsignedInteger('idemissao_comercial')->nullable()->default(NULL);
            $table->foreign('idemissao_comercial')->references('id')->on('tipo_emissao_faturamentos')->onDelete('SET NULL');

            $table->decimal('limite_credito_comercial', 11, 2)->default(0);

            $table->unsignedInteger('idtabela_preco_comercial')->nullable()->default(3);
            $table->foreign('idtabela_preco_comercial')->references('idtabela_preco')->on('tabela_precos')->onDelete('cascade');

            //tecnica
            $table->renameColumn('idforma_pagamento', 'idforma_pagamento_tecnica');

            $table->string('prazo_pagamento_tecnica', 100)->nullable()->default(NULL);

            $table->unsignedInteger('idemissao_tecnica')->nullable()->default(NULL);
            $table->foreign('idemissao_tecnica')->references('id')->on('tipo_emissao_faturamentos')->onDelete('SET NULL');

            $table->renameColumn('limite_credito', 'limite_credito_tecnica');

            $table->renameColumn('idtabela_preco', 'idtabela_preco_tecnica');

            $table->timestamp('validated_at')->nullable();
            $table->boolean('centro_custo')->default(0);
            $table->string('email_orcamento',60);
            $table->string('email_nota', 60)->nullable();
            $table->string('foto',60)->nullable();
            $table->decimal('limite_credito',11,2);

            $table->decimal('distancia',11,2); //em km
            $table->decimal('pedagios', 11, 2)->default(0); //em R$
            $table->decimal('outros_custos', 11, 2)->default(0); //em R$
            $table->string('nome_responsavel',100)->nullable();
            $table->boolean('numero_chamado')->default(0);
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
        Schema::drop('clientes');
    }
}

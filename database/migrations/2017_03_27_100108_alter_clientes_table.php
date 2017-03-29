<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterClientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clientes', function (Blueprint $table) {

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
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->dropForeign('idforma_pagamento_comercial');
            $table->dropColumn('prazo_pagamento_comercial');
            $table->dropForeign('idemissao_comercial');
            $table->dropColumn('limite_credito_comercial');
            $table->dropForeign('idtabela_preco_comercial');

            $table->dropForeign('idforma_pagamento_comercial');
            $table->dropColumn('prazo_pagamento_comercial');
            $table->dropForeign('idemissao_comercial');
            $table->dropColumn('limite_credito_comercial');
            $table->dropForeign('idtabela_preco_tecnica');

        });
    }
}

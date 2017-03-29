<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB as DB;

class TipoEmissaoFaturamentoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
//    php artisan db:seed --class=TipoEmissaoFaturamentoSeeder
    public function run()
    {
        $start = microtime(true);
        echo "*** Iniciando os Seeders ***";
        $data = [
            ['descricao' => 'BOLETO, NFe, NFSe'],
            ['descricao' => 'BOLETO E NFe AGREGADO AO VALOR DE PEÇA'],
            ['descricao' => 'SOMENTE BOLETO']
        ];
        \App\Models\TipoEmissaoFaturamento::insert($data);

        DB::table('clientes')->whereNull('idemissao_tecnica')->update(['idemissao_tecnica' => 2]);
        DB::table('clientes')->whereNull('idemissao_comercial')->update(['idemissao_comercial' => 2]);

        echo "\n*** TipoEmissaoFaturamentoSeeder completo em " . round((microtime(true) - $start), 3) . "s ***";
    }
}

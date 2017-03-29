<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Role;
use App\Permission;

class FluxoFechamentoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //php artisan db:seed --class=FluxoFechamentoTableSeeder
        $start = microtime(true);
        echo "*** Iniciando os Seeders Extras ***";
        //JANEIRO
        DB::update("UPDATE ordem_servicos SET created_at = '2017-01-01 01:00:00', fechamento = '2017-01-02 01:00:00' WHERE idordem_servico BETWEEN 1 AND 29");
        DB::update("UPDATE ordem_servicos SET created_at = '2017-01-01 01:00:00', fechamento = '2017-01-02 01:00:00' WHERE idordem_servico BETWEEN 31 AND 37");
        DB::update("UPDATE ordem_servicos SET created_at = '2017-01-01 01:00:00', fechamento = '2017-01-02 01:00:00' WHERE idordem_servico = 39");

        //FEVEREIRO
        DB::update("UPDATE ordem_servicos SET created_at = '2017-01-02 01:00:00', fechamento = '2017-02-02 01:00:00' WHERE idordem_servico = 30");
        DB::update("UPDATE ordem_servicos SET created_at = '2017-01-02 01:00:00', fechamento = '2017-02-02 01:00:00' WHERE idordem_servico = 38");
        DB::update("UPDATE ordem_servicos SET created_at = '2017-01-02 01:00:00', fechamento = '2017-02-02 01:00:00' WHERE idordem_servico BETWEEN 40 AND 93");

        $this->call(TipoEmissaoFaturamentoSeeder::class);
        $this->call(StatusFechamentoTableSeeder::class);
        $this->call(ClientePrazoPagamentoSeeder::class);
        $this->call(NovoFechamentoTableSeeder::class);
        echo "\n*** Importacao realizada com sucesso em " . round((microtime(true) - $start), 3) . "s ***";

    }
}

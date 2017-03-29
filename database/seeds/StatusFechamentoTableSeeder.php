<?php

use Illuminate\Database\Seeder;

class StatusFechamentoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
//    php artisan db:seed --class=StatusFechamentoTableSeeder
    public function run()
    {
        $start = microtime(true);
        echo "*** Iniciando os Seeders ***";
        $data = [
            ['descricao' => 'FATURAMENTO PENDENTE'],
            ['descricao' => 'PAGAMENTO PENDENTE'],
            ['descricao' => 'FATURADO']
        ];
        \App\Models\StatusFechamento::insert($data);
        echo "\n*** StatusFechamentoTableSeeder completo em " . round((microtime(true) - $start), 3) . "s ***";
    }
}

<?php

use Illuminate\Database\Seeder;

class StatusParcelaTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
//    php artisan db:seed --class=StatusParcelaTableSeeder
    public function run()
    {
        $start = microtime(true);
        echo "*** Iniciando os Seeders ***";
        $data = [
            ['descricao' => 'ABERTO'],
            ['descricao' => 'PAGO'],
            ['descricao' => 'PAGO EM ATRASO'],
            ['descricao' => 'PAGO EM CARTÓRIO'],
            ['descricao' => 'EM CARTÓRIO'],
            ['descricao' => 'DESCONTADO'],
            ['descricao' => 'VENCIDO'],
        ];

        \App\Models\StatusParcela::insert($data);
        echo "\n*** StatusParcelaTableSeeder completo em " . round((microtime(true) - $start), 3) . "s ***";

        DB::table('PARCELAS')
            ->where('status', 0)
            ->update(['idstatus_parcela' => 1]);
        DB::table('PARCELAS')
            ->where('status', 1)
            ->update(['idstatus_parcela' => 2]);

        DB::raw('ALTER TABLE parcelas DROP status');

    }
}

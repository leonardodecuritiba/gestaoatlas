<?php

use Illuminate\Database\Seeder;

class ImportTabelaPrecoServico extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//    php artisan db:seed --class=ImportTabelaPrecoServico
        $start = microtime(true);
        $a = 'export_09_08_2017-10_20.xls';
        echo "*** Iniciando o Upload (" . $a . ") ***";
        $file = storage_path('uploads' . DIRECTORY_SEPARATOR . 'import' . DIRECTORY_SEPARATOR . $a); //servidor
        set_time_limit(3600);

        $reader = Excel::load($file, function ($sheet) {
            // Loop through all sheets
            $sheet->each(function ($row) {
                $custo_final = \App\Helpers\DataHelper::getReal2Float($row->valor);
                $preco = ($custo_final > 0) ? $row->abre_mercado_franca : 0;
                $margem = ($preco > 0) ? ((($preco / $custo_final) - 1) * 100) : 0;
                $data = [
                    'idtabela_preco' => 4,
                    'idservico' => $row->idservico,
                    'margem' => $margem,
                    'preco' => $preco,
                    'margem_minimo' => $margem,
                    'preco_minimo' => $preco
                ];
//                echo json_encode($data);
//                exit;
                \App\TabelaPrecoServico::create($data);
                echo "\n ****************** (" . $row->idservico . ") ****************** \n";
            });
        })->ignoreEmpty();

        echo "\n*** Importacao ImportTabelaPrecoServico (" . $a . ") realizada com sucesso em " . round((microtime(true) - $start), 3) . "s ***";

    }
}

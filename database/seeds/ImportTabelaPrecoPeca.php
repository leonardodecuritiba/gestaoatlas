<?php

use Illuminate\Database\Seeder;

class ImportTabelaPrecoPeca extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//    php artisan db:seed --class=ImportTabelaPrecoPeca
        $start = microtime(true);
        $a = 'export_07_08_2017-16_47.xls';
        echo "*** Iniciando o Upload (" . $a . ") ***";
        $file = storage_path('uploads' . DIRECTORY_SEPARATOR . 'import' . DIRECTORY_SEPARATOR . $a); //servidor
//        $file = storage_path('uploads') . '\import\export_21_02_2017-16_19.xls';
        set_time_limit(3600);

        $reader = Excel::load($file, function ($sheet) {
            // Loop through all sheets
            $sheet->each(function ($row) {
                $custo_final = \App\Helpers\DataHelper::getReal2Float($row->custo_final);
                $preco = ($custo_final > 0) ? \App\Helpers\DataHelper::getReal2Float($row->lopes) : 0;
                $margem = ($preco > 0) ? ((($preco / $custo_final) - 1) * 100) : 0;
                $data = [
                    'idtabela_preco' => 4,
                    'idpeca' => $row->idpeca,
                    'margem' => $margem,
                    'preco' => $preco,
                    'margem_minimo' => $margem,
                    'preco_minimo' => $preco
                ];
//                echo json_encode($data);
                \App\TabelaPrecoPeca::create($data);
                echo "\n ****************** (" . $row->idpeca . ") ****************** \n";
            });
        })->ignoreEmpty();

        echo "\n*** Importacao IMPORTSEEDER (" . $a . ") realizada com sucesso em " . round((microtime(true) - $start), 3) . "s ***";

    }
}

<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Role;
use App\Permission;

class ImportProdutoSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//    php artisan db:seed --class=ImportProdutoSeeder
        $start = microtime(true);
        echo "*** Iniciando o Upload (export_21_02_2017-16_19.xls) ***";
        $file = storage_path('uploads') . '\import\export_21_02_2017-16_19.xls';
        echo "\n*** Upload completo em " . round((microtime(true) - $start), 3) . "s ***";
        set_time_limit(3600);

        $reader = Excel::load($file, function ($sheet) {
            // Loop through all sheets
            $sheet->each(function ($row) {
                //				cfop venda	C S T venda	  	 gricki 	 savegnago 	 geral 	 porcentagem

                \App\Peca::find($row->idpeca)->peca_tributacao->update(['cest' => strval($row->cest)]);
                echo "****************** (" . $row->idpeca . ") ****************** \n";
            });
        })->ignoreEmpty();

        echo "\n*** Importacao IMPORTSEEDER (export_21_02_2017-16_19) realizada com sucesso em " . round((microtime(true) - $start), 3) . "s ***";

    }

}
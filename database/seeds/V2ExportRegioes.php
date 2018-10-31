<?php

use Illuminate\Database\Seeder;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Ajustes\RecursosHumanos\Clientes\Regiao;

class V2ExportRegioes extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

//	    php artisan db:seed --class=V2ExportRegioes
        $Data = Regiao::all();
        return Excel::create('regioes', function ($excel) use ($Data) {
            $excel->sheet('Sheet 1', function($sheet) use($Data) {
                $sheet->row(1, array(
                    'idregiao',
                    'description',
                ));

                $i = 2;

                foreach ($Data as $data) {
                    $data_export = [
                        'idregiao'      => $data->idregiao,
                        'description'   => $data->descricao,
                    ];

                    $sheet->row($i, $data_export);
                    $i++;
                }
            });

        })->store('xls');
    }
}

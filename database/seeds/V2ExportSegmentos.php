<?php

use Illuminate\Database\Seeder;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Ajustes\RecursosHumanos\Clientes\Segmento;

class V2ExportSegmentos extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

//	    php artisan db:seed --class=V2ExportRegioes
        $Data = Segmento::all();
        return Excel::create('segments', function ($excel) use ($Data) {
            $excel->sheet('Sheet 1', function($sheet) use($Data) {
                $sheet->row(1, array(
                    'idsegmento',
                    'description',
                ));

                $i = 2;

                foreach ($Data as $data) {
                    $data_export = [
                        'idsegmento'    => $data->idsegmento,
                        'description'   => $data->descricao,
                    ];

                    $sheet->row($i, $data_export);
                    $i++;
                }
            });

        })->store('xls');
    }
}

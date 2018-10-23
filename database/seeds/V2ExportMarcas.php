<?php

use Illuminate\Database\Seeder;
use Maatwebsite\Excel\Facades\Excel;
use App\Marca;

class V2ExportMarcas extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//	    php artisan db:seed --class=V2ExportMarcas
        $Data = Marca::all();
        return Excel::create('marcas', function ($excel) use ($Data) {
            $excel->sheet('Sheet 1', function($sheet) use($Data) {
                $sheet->row(1, array(
                    'idmarca',
                    'description',
                ));

                $i = 2;

                foreach ($Data as $data) {
                    $data_export = [

                        'idmarca'       => $data->idmarca,
                        'description'   => $data->descricao,
                    ];

                    $sheet->row($i, $data_export);
                    $i++;
                }
            });

        })->store('xls');


    }
}

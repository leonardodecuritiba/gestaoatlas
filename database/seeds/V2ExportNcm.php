<?php

use Illuminate\Database\Seeder;
use Maatwebsite\Excel\Facades\Excel;
use App\Ncm;

class V2ExportNcm extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

//	    php artisan db:seed --class=V2ExportNcm
        $Data = Ncm::all();
        return Excel::create('ncms', function ($excel) use ($Data) {
            $excel->sheet('Sheet 1', function($sheet) use($Data) {
                $sheet->row(1, array(
                    'idncm',
                    'codigo',
                    'descricao',
                    'aliquota_ipi',
                    'aliquota_pis',
                    'aliquota_cofins',
                    'aliquota_nacional',
                    'aliquota_importacao',
                ));

                $i = 2;

                foreach ($Data as $data) {
                    $data_export = [
                        'idncm'                 => $data->idncm,
                        'codigo'                => $data->codigo,
                        'descricao'             => $data->descricao,
                        'aliquota_ipi'          => $data->aliquota_ipi,
                        'aliquota_pis'          => $data->aliquota_pis,
                        'aliquota_cofins'       => $data->aliquota_cofins,
                        'aliquota_nacional'     => $data->aliquota_nacional,
                        'aliquota_importacao'   => $data->aliquota_importacao,
                    ];

                    $sheet->row($i, $data_export);
                    $i++;
                }
            });

        })->store('xls');
    }
}

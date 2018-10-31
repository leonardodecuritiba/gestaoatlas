<?php

use Illuminate\Database\Seeder;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Ajustes\RecursosHumanos\Fornecedores\SegmentoFornecedor;

class V2ExportSegmentosFornecedores extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

//	    php artisan db:seed --class=V2ExportSegmentosFornecedores
        $Data = SegmentoFornecedor::all();
        return Excel::create('segments_suppliers', function ($excel) use ($Data) {
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

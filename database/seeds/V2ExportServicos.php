<?php

use Illuminate\Database\Seeder;
use Maatwebsite\Excel\Facades\Excel;
use App\Servico;

class V2ExportServicos extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

//	    php artisan db:seed --class=V2ExportServicos
        $Data = Servico::all();
        return Excel::create('servicos', function ($excel) use ($Data) {
            $excel->sheet('Sheet 1', function($sheet) use($Data) {
                $sheet->row(1, array(
                    'idgrupo',
                    'idunidade',
                    'name',
                    'description',
                    'value',
                ));

                $i = 2;

                foreach ($Data as $data) {
                    $data_export = [

                        'idgrupo'       => $data->idgrupo,
                        'idunidade'     => $data->idunidade,
                        'name'          => $data->nome,
                        'description'   => $data->descricao,
                        'value'         => $data->valor,
                    ];

                    $sheet->row($i, $data_export);
                    $i++;
                }
            });

        })->store('xls');
    }
}

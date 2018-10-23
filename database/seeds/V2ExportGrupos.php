<?php

use Illuminate\Database\Seeder;
use Maatwebsite\Excel\Facades\Excel;
use App\Grupo;

class V2ExportGrupos extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//	    php artisan db:seed --class=V2ExportGrupos
	    $Data = Grupo::all();
	    return Excel::create('grupos', function ($excel) use ($Data) {
		    $excel->sheet('Sheet 1', function($sheet) use($Data) {
			    $sheet->row(1, array(
                    'idgrupo',
                    'description',
                ));

			    $i = 2;

			    foreach ($Data as $data) {
				    $data_export = [

					    'idgrupo'       => $data->idgrupo,
					    'description'   => $data->descricao,
				    ];

				    $sheet->row($i, $data_export);
				    $i++;
			    }
		    });

	    })->store('xls');

    }
}

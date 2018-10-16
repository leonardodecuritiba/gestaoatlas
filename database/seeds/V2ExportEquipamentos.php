<?php

use Illuminate\Database\Seeder;
use Maatwebsite\Excel\Facades\Excel;
use App\Equipamento;

class V2ExportEquipamentos extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//	    php artisan db:seed --class=V2ExportEquipamentos
	    $Equipamentos = Equipamento::all();
	    return Excel::create('fornecedores', function ($excel) use ($Equipamentos) {
		    $excel->sheet('Sheet 1', function($sheet) use($Equipamentos) {

			    $data_equipamento = array(
				    'idequipamento',
				    'idcliente',
				    'idmarca',
				    'brand_name',
				    'description',
				    'model',
				    'serial_number'

			    );

			    $sheet->row(1, $data_equipamento);

			    $i = 2;

			    foreach ($Equipamentos as $equipamento) {
				    $data_export = [

					    'idequipamento' => $equipamento->idequipamento,
					    'idcliente'     => $equipamento->idcliente,
					    'idmarca'       => $equipamento->idmarca,
					    'brand_name'    => $equipamento->marca->descricao,
					    'description'   => $equipamento->descricao,
					    'model'         => $equipamento->modelo,
					    'serial_number' => $equipamento->numero_serie,

				    ];

				    $sheet->row($i, $data_export);
				    $i++;
			    }
		    });

	    })->store('xls');

    }
}

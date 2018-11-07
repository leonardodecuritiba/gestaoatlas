<?php

namespace App\Console\Commands;

use App\Instrumento;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;

class ExportInstrumentos extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'command:export_instruments';
	protected $name = 'instruments';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Command description';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle()
	{
		$Data = Instrumento::all();
		return Excel::create($this->name, function ($excel) use ($Data) {
			$excel->sheet('Sheet 1', function($sheet) use($Data) {

				$sheet->row(1, array(
					'idinstrumento',
					'idcliente',
					'idinstrmento_base',
					'idinstrmento_setor',

					'serial_number',
					'inventory',
					'patrimony',
					'year',
					'ip',
					'address',
					'etiqueta_identificacao',
					'etiqueta_inventario'
				));
				$i = 2;
				foreach ($Data as $data) {

//                    verificar se foto existe
					$new_path = storage_path('exports/instruments/');
					if($data->etiqueta_identificacao != NULL){
						$path = public_path('uploads/instrumentos/' . $data->etiqueta_identificacao);
						if(File::exists($path)){
							if(!File::exists($new_path)) {
								// path does not exist
								File::makeDirectory($new_path, $mode = 0777, true, true);
							}
							$copy = File::copy($path, $new_path . $data->etiqueta_identificacao);
						} else {
							echo('NÃO EXISTE : ' . $data->etiqueta_identificacao . ', idinstrumento: ' . $data->idinstrumento);
							$data->etiqueta_identificacao = NULL;
						}
					}
					if($data->etiqueta_inventario != NULL){
						$path = public_path('uploads/instrumentos/' . $data->etiqueta_inventario);
						if(File::exists($path)){
							if(!File::exists($new_path)) {
								// path does not exist
								File::makeDirectory($new_path, $mode = 0777, true, true);
							}
							$copy = File::copy($path, $new_path . $data->etiqueta_inventario);
						} else {
							echo('NÃO EXISTE : ' . $data->etiqueta_inventario . ', idinstrumento: ' . $data->idinstrumento);
							$data->etiqueta_inventario = NULL;
						}
					}


					$data_export = [
						'idinstrumento'             => $data->idinstrumento,
						'idcliente'                 => $data->idcliente,
						'idinstrmento_base'         => $data->idbase,
						'idinstrmento_setor'        => $data->idsetor,

						'serial_number'             => $data->numero_serie,
						'inventory'                 => $data->inventario,
						'patrimony'                 => $data->patrimonio,
						'year'                      => $data->ano,
						'ip'                        => $data->ip,
						'address'                   => $data->endereco,

						'etiqueta_identificacao'    => $data->etiqueta_identificacao,
						'etiqueta_inventario'       => $data->etiqueta_inventario,
					];

					$sheet->row($i, $data_export);
					$i++;
				}
			});

		})->store('xls');

	}
}

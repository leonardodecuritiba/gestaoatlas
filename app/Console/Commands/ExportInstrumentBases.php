<?php

namespace App\Console\Commands;

use App\Models\Instrumentos\InstrumentoBase;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;

class ExportInstrumentBases extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'command:export_instrument_bases';
	protected $name = 'instrument_bases';

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
		$Data = InstrumentoBase::all();
		return Excel::create($this->name, function ($excel) use ($Data) {
			$excel->sheet('Sheet 1', function($sheet) use($Data) {

				$sheet->row(1, array(
					'created_at',
					'idinstrumento_base',
					'idinstrumento_modelo',
					'descricao',
					'divisao',
					'portaria',
					'capacidade',
					'foto'
				));
				$i = 2;
				foreach ($Data as $data) {

//                    verificar se foto existe
					$new_path = storage_path('exports/instrument_bases/');
					if($data->foto != NULL){
						$path = public_path('uploads/instrumento_bases/' . $data->foto);
						if(File::exists($path)){
							if(!File::exists($new_path)) {
								// path does not exist
								File::makeDirectory($new_path, $mode = 0777, true, true);
							}
							$copy = File::copy($path, $new_path . $data->foto);
						} else {
							echo('NÃO EXISTE : ' . $data->foto . ', idinstrumento_base: ' . $data->id);
							$data->foto = NULL;
						}
					}

					$data_export = [
						'created_at'    => $data->created_at,
						'idinstrumento_base'    => $data->id,
						'idinstrumento_modelo'  => $data->idmodelo,
						'description'           => $data->descricao,
						'division'              => $data->divisao,
						'ordinance'             => $data->portaria,
						'capacity'              => $data->capacidade,
						'foto'                  => $data->foto,
					];

					$sheet->row($i, $data_export);
					$i++;
				}
			});

		})->store('xls');

	}
}

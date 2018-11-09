<?php

namespace App\Console\Commands;

use App\Models\Instrumentos\InstrumentoModelo;
use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;

class ExportInstrumentModels extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'command:export_instrument_models';
	protected $name = 'instrument_models';

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
		$Data = InstrumentoModelo::all();
		return Excel::create($this->name, function ($excel) use ($Data) {
			$excel->sheet('Sheet 1', function($sheet) use($Data) {

				$sheet->row(1, array(
					'idinstrumento_modelo',
					'idinstrumento_marca',
					'description'
				));
				$i = 2;
				foreach ($Data as $data) {
					$data_export = [

						'idinstrumento_modelo'  => $data->id,
						'idinstrumento_marca'   => $data->idinstrumento_marca,
						'description'           => $data->descricao,
					];

					$sheet->row($i, $data_export);
					$i++;
				}
			});

		})->store('xls');

	}
}

<?php

namespace App\Console\Commands;

use App\Models\Instrumentos\InstrumentoSetor;
use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;

class ExportInstrumentSetors extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'command:export_instrument_setors';
	protected $name = 'instrument_setors';

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
		$Data = InstrumentoSetor::all();
		return Excel::create($this->name, function ($excel) use ($Data) {
			$excel->sheet('Sheet 1', function($sheet) use($Data) {

				$sheet->row(1, array(
					'created_at',
					'idinstrumento_setor',
					'description',
				));
				$i = 2;
				foreach ($Data as $data) {
					$data_export = [

						'created_at'   => $data->getOriginal('created_at'),
						'idinstrumento_setor'   => $data->id,
						'description'           => $data->descricao,
					];

					$sheet->row($i, $data_export);
					$i++;
				}
			});

		})->store('xls');

	}
}

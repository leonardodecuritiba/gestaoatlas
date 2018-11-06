<?php

namespace App\Console\Commands;

use App\TabelaPreco;
use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;

class ExportTabelaPreco extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'command:export_tabela_precos';

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
		$Data = TabelaPreco::all();
		return Excel::create('tabela_precos', function ($excel) use ($Data) {
			$excel->sheet('Sheet 1', function($sheet) use($Data) {
				$sheet->row(1, array(
					'idtabela_preco',
					'description',
				));

				$i = 2;

				foreach ($Data as $data) {
					$data_export = [

						'idtabela_preco'    => $data->idtabela_preco,
						'description'       => $data->descricao,
					];

					$sheet->row($i, $data_export);
					$i++;
				}
			});

		})->store('xls');
	}
}

<?php

namespace App\Console\Commands;

use App\TabelaPrecoPeca;
use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;

class ExportTabelaPrecoPeca extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'command:export_tabela_preco_pecas';

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
		$Data = TabelaPrecoPeca::all();
		return Excel::create('tabela_preco_pecas', function ($excel) use ($Data) {
			$excel->sheet('Sheet 1', function($sheet) use($Data) {
				$sheet->row(1, array(
					'created_at',
					'idtabela_preco',
					'idpeca',
					'range',
					'price',
					'range_min',
					'price_min',
				));

				$i = 2;

				foreach ($Data as $data) {
					$data_export = [

						'created_at'    => $data->getOriginal('created_at'),
						'idtabela_preco'    => $data->idtabela_preco,
						'idpeca'            => $data->idpeca,
						'range'             => $data->margem,
						'price'             => $data->preco,
						'range_min'         => $data->margem_minimo,
						'price_min'         => $data->preco_minimo,
					];

					$sheet->row($i, $data_export);
					$i++;
				}
			});

		})->store('xls');
	}
}

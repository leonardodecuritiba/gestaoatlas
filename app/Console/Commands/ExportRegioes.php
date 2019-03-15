<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Ajustes\RecursosHumanos\Clientes\Regiao;

class ExportRegioes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:export_regioes';

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
        $Data = Regiao::all();
        return Excel::create('regioes', function ($excel) use ($Data) {
            $excel->sheet('Sheet 1', function($sheet) use($Data) {
                $sheet->row(1, array(
	                'created_at',
                    'idregiao',
                    'description',
                ));

                $i = 2;

                foreach ($Data as $data) {
                    $data_export = [
                        'created_at'      => $data->getOriginal('created_at'),
                        'idregiao'      => $data->idregiao,
                        'description'   => $data->descricao,
                    ];

                    $sheet->row($i, $data_export);
                    $i++;
                }
            });

        })->store('xls');
    }
}

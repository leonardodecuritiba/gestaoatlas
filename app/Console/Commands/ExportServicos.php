<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;
use App\Servico;

class ExportServicos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:export_servicos';

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
        $Data = Servico::all();
        return Excel::create('servicos', function ($excel) use ($Data) {
            $excel->sheet('Sheet 1', function($sheet) use($Data) {
                $sheet->row(1, array(
	                'created_at',
                    'idservico',
                    'idgrupo',
                    'idunidade',
                    'name',
                    'description',
                    'value',
                ));

                $i = 2;

                foreach ($Data as $data) {
                    $data_export = [

                        'created_at'     => $data->created_at,
                        'idservico'     => $data->idservico,
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

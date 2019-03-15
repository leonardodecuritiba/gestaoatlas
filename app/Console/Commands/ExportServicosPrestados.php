<?php

namespace App\Console\Commands;

use App\ServicoPrestado;
use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;

class ExportServicosPrestados extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:export_servicos_prestados';

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
        $Data = ServicoPrestado::all();
        return Excel::create('servicos_prestados', function ($excel) use ($Data) {
            $excel->sheet('Sheet 1', function($sheet) use($Data) {
                $sheet->row(1, array(
	                'created_at',
                    'idservico_prestado',
	                'idaparelho_manutencao',
	                'idservico',
	                'valor',
	                'quantidade',
	                'desconto'
                ));

                $i = 2;

                foreach ($Data as $data) {
                    $data_export = [
                        'created_at'    => $data->getOriginal('created_at'),
                        'idservico_prestado'    => $data->idservico_prestado,
                        'idaparelho_manutencao' => $data->idaparelho_manutencao,
                        'idservico'             => $data->idservico,
                        'valor'                 => $data->valor,
                        'quantidade'            => $data->quantidade,
                        'desconto'              => $data->desconto,
                    ];

                    $sheet->row($i, $data_export);
                    $i++;
                }
            });

        })->store('xls');
    }
}

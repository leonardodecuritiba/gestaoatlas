<?php

namespace App\Console\Commands;

use App\PecasUtilizadas;
use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;

class ExportPecasUtilizadas extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:export_pecas_utilizadas';

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
        $Data = PecasUtilizadas::all();
        return Excel::create('pecas_utilizadas', function ($excel) use ($Data) {
            $excel->sheet('Sheet 1', function($sheet) use($Data) {
                $sheet->row(1, array(
	                'created_at',
                    'idpeca_utilizada',
	                'idaparelho_manutencao',
	                'idpeca',
	                'valor',
	                'quantidade',
	                'desconto'
                ));

                $i = 2;

                foreach ($Data as $data) {
                    $data_export = [
                        'created_at'      => $data->created_at,
                        'idpeca_utilizada'      => $data->id,
                        'idaparelho_manutencao' => $data->idaparelho_manutencao,
                        'idpeca'                => $data->idpeca,
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

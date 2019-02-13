<?php

namespace App\Console\Commands;

use App\Models\Faturamento;
use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;

class ExportFaturamentos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:export_faturamentos';

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
        $Data = Faturamento::all();
        return Excel::create('faturamentos', function ($excel) use ($Data) {
            $excel->sheet('Sheet 1', function($sheet) use($Data) {
                $sheet->row(1, array(
	                'created_at',
                    'idfaturamento',
                    'idcliente',
                    'idstatus_faturamento',
                    'idpagamento',
                    'idnfe_homologacao',
                    'data_nfe_homologacao',
                    'idnfe_producao',
                    'data_nfe_producao',
                    'idnfse_homologacao',
                    'data_nfse_homologacao',
                    'idnfse_producao',
                    'data_nfse_producao',
                    'centro_custo'
                ));

                $i = 2;

                foreach ($Data as $data) {
                    $data_export = [
                        'created_at'         => $data->created_at,
                        'idfaturamento'         => $data->id,
                        'idcliente'             => $data->idcliente,
                        'idstatus_faturamento'  => $data->idstatus_faturamento,
                        'idpagamento'           => $data->idpagamento,
                        'idnfe_homologacao'     => $data->idnfe_homologacao,
                        'data_nfe_homologacao'  => $data->data_nfe_homologacao,
                        'idnfe_producao'        => $data->idnfe_producao,
                        'data_nfe_producao'     => $data->data_nfe_producao,
                        'idnfse_homologacao'    => $data->idnfse_homologacao,
                        'data_nfse_homologacao' => $data->data_nfse_homologacao,
                        'idnfse_producao'       => $data->idnfse_producao,
                        'data_nfse_producao'    => $data->data_nfse_producao,
                        'centro_custo'          => $data->centro_custo,
                    ];

                    $sheet->row($i, $data_export);
                    $i++;
                }
            });

        })->store('xls');
    }
}

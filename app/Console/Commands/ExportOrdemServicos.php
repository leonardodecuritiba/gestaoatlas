<?php

namespace App\Console\Commands;

use App\OrdemServico;
use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;

class ExportOrdemServicos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:export_ordem_servicos';

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
        $Data = OrdemServico::all();
        return Excel::create('ordem_servicos', function ($excel) use ($Data) {
            $excel->sheet('Sheet 1', function($sheet) use($Data) {
                $sheet->row(1, array(
	                'created_at',
                    'idordem_servico',
                    'idcliente',
                    'idfaturamento',
                    'idcolaborador',
                    'idsituacao_ordem_servico',
                    'idcentro_custo',
                    'data_fechada',
                    'data_finalizada',
                    'numero_chamado',
                    'responsavel',
                    'responsavel_cpf',
                    'responsavel_cargo',
                    'valor_total',
                    'desconto_tecnico',
                    'acrescimo_tecnico',
                    'valor_final',
                    'custos_deslocamento',
                    'custos_isento',
                    'pedagios',
                    'outros_custos',
                    'validacao',
                ));

                $i = 2;

                foreach ($Data as $data) {
                    $data_export = [

                        'created_at'           => $data->getOriginal('created_at'),
                        'idordem_servico'           => $data->idordem_servico,
                        'idcliente'                 => $data->idcliente,
                        'idfaturamento'             => $data->idfaturamento,
                        'idcolaborador'             => $data->idcolaborador,
                        'idsituacao_ordem_servico'  => $data->idsituacao_ordem_servico,
                        'idcentro_custo'            => $data->idcentro_custo,
                        'data_fechada'              => $data->data_fechada,
                        'data_finalizada'           => $data->data_finalizada,
                        'numero_chamado'            => $data->numero_chamado,
                        'responsavel'               => $data->responsavel,
                        'responsavel_cpf'           => $data->responsavel_cpf,
                        'responsavel_cargo'         => $data->responsavel_cargo,
                        'valor_total'               => $data->valor_total,
                        'desconto_tecnico'          => $data->desconto_tecnico,
                        'acrescimo_tecnico'         => $data->acrescimo_tecnico,
                        'valor_final'               => $data->valor_final,
                        'custos_deslocamento'       => $data->custos_deslocamento,
                        'custos_isento'             => $data->custos_isento,
                        'pedagios'                  => $data->pedagios,
                        'outros_custos'             => $data->outros_custos,
                        'validacao'                 => $data->validacao,
                    ];

                    $sheet->row($i, $data_export);
                    $i++;
                }
            });

        })->store('xls');
    }
}

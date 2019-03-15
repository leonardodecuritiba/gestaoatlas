<?php

namespace App\Console\Commands;

use App\AparelhoManutencao;
use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;

class ExportAparelhoManutencao extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:export_aparelho_manutencao';

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
        $Data = AparelhoManutencao::all();
        return Excel::create('aparelho_manutencao', function ($excel) use ($Data) {
            $excel->sheet('Sheet 1', function($sheet) use($Data) {
                $sheet->row(1, array(
                    'created_at',
                    'idaparelho_manutencao',
                    'idordem_servico',
                    'idinstrumento',
                    'idequipamento',
                    'defeito',
                    'solucao',
                    'numero_chamado',
                ));

                $i = 2;

                foreach ($Data as $data) {
                    $data_export = [
                        'created_at'            => $data->getAttribute('created_at'),
                        'idaparelho_manutencao' => $data->idaparelho_manutencao,
                        'idordem_servico'       => $data->idordem_servico,
                        'idinstrumento'         => $data->idinstrumento,
                        'idequipamento'         => $data->idequipamento,
                        'defeito'               => $data->defeito,
                        'solucao'               => $data->solucao,
                        'numero_chamado'        => $data->numero_chamado,
                    ];

                    $sheet->row($i, $data_export);
                    $i++;
                }
            });

        })->store('xls');
    }
}

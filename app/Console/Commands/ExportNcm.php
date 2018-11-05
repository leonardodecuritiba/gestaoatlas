<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;
use App\Ncm;

class ExportNcm extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:export_ncm';

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
        $Data = Ncm::all();
        return Excel::create('ncms', function ($excel) use ($Data) {
            $excel->sheet('Sheet 1', function($sheet) use($Data) {
                $sheet->row(1, array(
                    'idncm',
                    'codigo',
                    'descricao',
                    'aliquota_ipi',
                    'aliquota_pis',
                    'aliquota_cofins',
                    'aliquota_nacional',
                    'aliquota_importacao',
                ));

                $i = 2;

                foreach ($Data as $data) {
                    $data_export = [
                        'idncm'                 => $data->idncm,
                        'codigo'                => $data->codigo,
                        'descricao'             => $data->descricao,
                        'aliquota_ipi'          => $data->aliquota_ipi,
                        'aliquota_pis'          => $data->aliquota_pis,
                        'aliquota_cofins'       => $data->aliquota_cofins,
                        'aliquota_nacional'     => $data->aliquota_nacional,
                        'aliquota_importacao'   => $data->aliquota_importacao,
                    ];

                    $sheet->row($i, $data_export);
                    $i++;
                }
            });

        })->store('xls');
    }
}

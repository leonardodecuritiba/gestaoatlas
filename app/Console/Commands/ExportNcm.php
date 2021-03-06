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
	                'created_at',
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
                        'created_at'                 => $data->getOriginal('created_at'),
                        'idncm'                 => $data->idncm,
                        'codigo'                => $data->codigo,
                        'descricao'             => $data->descricao,
                        'aliquota_ipi'          => $data->getOriginal('aliquota_ipi'),
                        'aliquota_pis'          => $data->getOriginal('aliquota_pis'),
                        'aliquota_cofins'       => $data->getOriginal('aliquota_cofins'),
                        'aliquota_nacional'     => $data->getOriginal('aliquota_nacional'),
                        'aliquota_importacao'   => $data->getOriginal('aliquota_importacao'),
                    ];

                    $sheet->row($i, $data_export);
                    $i++;
                }
            });

        })->store('xls');
    }
}

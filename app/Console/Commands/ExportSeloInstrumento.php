<?php

namespace App\Console\Commands;

use App\SeloInstrumento;
use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;

class ExportSeloInstrumento extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:export_selo_instrumentos';

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
        $Data = SeloInstrumento::all();
        return Excel::create('selo_instrumentos', function ($excel) use ($Data) {
            $excel->sheet('Sheet 1', function($sheet) use($Data) {
                $sheet->row(1, array(
	                'created_at',
                    'idselo_instrumento',
                    'idaparelho_set',
                    'idaparelho_unset',
                    'idinstrumento',
                    'idselo',
                    'afixado_em',
                    'retirado_em',
                    'external'
                ));

                $i = 2;

                foreach ($Data as $data) {
                    $data_export = [
                        'created_at'    => $data->getOriginal('created_at'),
                        'idselo_instrumento'    => $data->idselo_instrumento,
                        'idaparelho_set'        => $data->idaparelho_set,
                        'idaparelho_unset'      => $data->idaparelho_unset,
                        'idinstrumento'         => $data->idinstrumento,
                        'idselo'                => $data->idselo,
                        'afixado_em'            => $data->afixado_em,
                        'retirado_em'           => $data->retirado_em,
                        'external'              => $data->external,
                    ];

                    $sheet->row($i, $data_export);
                    $i++;
                }
            });

        })->store('xls');
    }
}

<?php

namespace App\Console\Commands;

use App\Selo;
use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;

class ExportSelos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:export_selos';

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
        $Data = Selo::all();
        return Excel::create('selos', function ($excel) use ($Data) {
            $excel->sheet('Sheet 1', function($sheet) use($Data) {
                $sheet->row(1, array(
	                'created_at',
                    'idselo',
                    'idtecnico',
                    'numeracao',
                    'numeracao_externa',
                    'externo',
                    'used',
                    'declared',
                ));

                $i = 2;

                foreach ($Data as $data) {
                    $data_export = [
                        'created_at'            => $data->getOriginal('created_at'),
                        'idselo'            => $data->idselo,
                        'idtecnico'         => $data->idtecnico,
                        'numeracao'         => $data->numeracao,
                        'numeracao_externa' => $data->numeracao_externa,
                        'externo'           => $data->externo,
                        'used'              => $data->used,
                        'declared'          => $data->declared,
                    ];

                    $sheet->row($i, $data_export);
                    $i++;
                }
            });

        })->store('xls');

    }
}

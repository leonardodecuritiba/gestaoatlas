<?php

namespace App\Console\Commands;

use App\Lacre;
use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;

class ExportLacres extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:export_lacres';

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
        $Data = Lacre::all();
        return Excel::create('lacres', function ($excel) use ($Data) {
            $excel->sheet('Sheet 1', function($sheet) use($Data) {
                $sheet->row(1, array(
                    'idlacre',
                    'idtecnico',
                    'numeracao',
                    'numeracao_externa',
                    'externo',
                    'used',
                ));

                $i = 2;

                foreach ($Data as $data) {
                    $data_export = [
                        'idlacre'           => $data->idlacre,
                        'idtecnico'         => $data->idtecnico,
                        'numeracao'         => $data->numeracao,
                        'numeracao_externa' => $data->numeracao_externa,
                        'externo'           => $data->externo,
                        'used'              => $data->used,
                    ];

                    $sheet->row($i, $data_export);
                    $i++;
                }
            });

        })->store('xls');

    }
}

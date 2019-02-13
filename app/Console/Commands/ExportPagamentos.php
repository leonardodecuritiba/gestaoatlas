<?php

namespace App\Console\Commands;

use App\Models\Pagamento;
use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;

class ExportPagamentos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:export_pagamentos';

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
        $Data = Pagamento::all();
        return Excel::create('pagamentos', function ($excel) use ($Data) {
            $excel->sheet('Sheet 1', function($sheet) use($Data) {
                $sheet->row(1, array(
	                'created_at',
                    'idpagamento',
                    'data_baixa',
                    'status',
                ));

                $i = 2;

                foreach ($Data as $data) {
                    $data_export = [
                        'created_at'   => $data->created_at,
                        'idpagamento'   => $data->id,
                        'data_baixa'    => $data->data_baixa,
                        'status'        => $data->status,
                    ];

                    $sheet->row($i, $data_export);
                    $i++;
                }
            });

        })->store('xls');
    }
}

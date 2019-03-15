<?php

namespace App\Console\Commands;

use App\Models\Parcela;
use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;

class ExportParcelas extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:export_parcelas';

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
        $Data = Parcela::all();
        return Excel::create('parcelas', function ($excel) use ($Data) {
            $excel->sheet('Sheet 1', function($sheet) use($Data) {
                $sheet->row(1, array(
	                'created_at',
	                'idpagamento',
	                'idstatus_parcela',
	                'idforma_pagamento',
	                'data_vencimento',
	                'data_pagamento',
	                'data_baixa',
	                'numero_parcela',
	                'valor_parcela',
                ));

                $i = 2;

                foreach ($Data as $data) {
                    $data_export = [
                        'created_at'        => $data->getOriginal('created_at'),
                        'idpagamento'       => $data->idpagamento,
                        'idstatus_parcela'  => $data->idstatus_parcela,
                        'idforma_pagamento' => $data->idforma_pagamento,
                        'data_vencimento'   => $data->getOriginal('data_vencimento'),
                        'data_pagamento'    => $data->getOriginal('data_pagamento'),
                        'data_baixa'        => $data->getOriginal('data_baixa'),
                        'numero_parcela'    => $data->numero_parcela,
                        'valor_parcela'     => $data->getOriginal('valor_parcela'),
                    ];

                    $sheet->row($i, $data_export);
                    $i++;
                }
            });

        })->store('xls');
    }
}

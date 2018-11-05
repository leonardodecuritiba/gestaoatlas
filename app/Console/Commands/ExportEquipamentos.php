<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\File;
use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;
use App\Equipamento;

class ExportEquipamentos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:export_equipamentos';

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
        $Equipamentos = Equipamento::all();
        return Excel::create('equipamentos', function ($excel) use ($Equipamentos) {
            $excel->sheet('Sheet 1', function($sheet) use($Equipamentos) {

                $data_equipamento = array(
                    'idequipamento',
                    'idcliente',
                    'idmarca',
                    'foto',
                    'brand_name',
                    'description',
                    'model',
                    'serial_number'

                );

                $sheet->row(1, $data_equipamento);

                $i = 2;

                foreach ($Equipamentos as $equipamento) {

//                    verificar se foto existe
                    $new_path = storage_path('exports/equipamentos/');
                    if($equipamento->foto != NULL){
                        $path = public_path('uploads/equipamentos/' . $equipamento->foto);
                        if(File::exists($path)){
                            if(!File::exists($new_path)) {
                                // path does not exist
                                File::makeDirectory($new_path, $mode = 0777, true, true);
                            }
                            $move = File::move($path, $new_path . $equipamento->foto);
                        } else {
                            $equipamento->foto = NULL;
                        }
                    }


                    $data_export = [

                        'idequipamento' => $equipamento->idequipamento,
                        'idcliente'     => $equipamento->idcliente,
                        'idmarca'       => $equipamento->idmarca,
                        'foto'          => $equipamento->foto,
                        'brand_name'    => $equipamento->marca->descricao,
                        'description'   => $equipamento->descricao,
                        'model'         => $equipamento->modelo,
                        'serial_number' => $equipamento->numero_serie,

                    ];

                    $sheet->row($i, $data_export);
                    $i++;
                }
            });

        })->store('xls');
    }
}

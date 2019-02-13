<?php

namespace App\Console\Commands;

use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;

class ExportUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:export_users';

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
        $Data = User::all();
        return Excel::create('users', function ($excel) use ($Data) {
            $excel->sheet('Sheet 1', function($sheet) use($Data) {
                $sheet->row(1, array(
	                'created_at',
                    'iduser',
                    'email',
                    'password',
                    'type',

                    'idcolaborador',
                    'nome',
                    'cpf',
                    'rg',
                    'cnh',
                    'carteira_trabalho',

//			    'address_id',
                    'state_name',
                    'city_name',
                    'zip',
                    'city_code',
                    'district',
                    'street',
                    'number',
                    'complement',


                    'idtecnico',
                    'carteira_imetro',
                    'carteira_ipem',
                    'desconto_max',
                    'acrescimo_max',


                ));

                $i = 2;
                $new_path = storage_path('exports/users/');

                foreach ($Data as $data) {

                    $data_export = [
	                    'created_at'        => $data->created_at,
                        'iduser'            => $data->iduser,
                        'email'             => $data->email,
                        'password'          => $data->password,
                        'type'              => $data->roles->first()->name,
                    ];

                    if($data->colaborador != NULL){

                        $c = $data->colaborador;

                        //cnh
                        if($c->cnh != NULL){
                            $path = public_path('uploads/colaboradores/' . $c->cnh);
                            if(File::exists($path)){
                                if(!File::exists($new_path)) {
                                    // path does not exist
                                    File::makeDirectory($new_path, $mode = 0777, true, true);
                                }
                                $copy = File::copy($path, $new_path . $c->cnh);
                            } else {
                                $c->cnh = NULL;
                            }
                        }

                        //carteira_trabalho
                        if($c->carteira_trabalho != NULL){
                            $path = public_path('uploads/colaboradores/' . $c->carteira_trabalho);
                            if(File::exists($path)){
                                if(!File::exists($new_path)) {
                                    // path does not exist
                                    File::makeDirectory($new_path, $mode = 0777, true, true);
                                }
                                $copy = File::copy($path, $new_path . $c->carteira_trabalho);
                            } else {
                                $c->carteira_trabalho = NULL;
                            }
                        }

                        $contato = $c->contato;

                        $data_export = array_merge($data_export, [
                            'idcolaborador'     => $c->idcolaborador,
                            'nome'              => $c->nome,
                            'cpf'               => $c->getOriginal('cpf'),
                            'rg'                => $c->getOriginal('rg'),
                            'cnh'               => $c->cnh,
                            'carteira_trabalho' => $c->carteira_trabalho,

                            //contato
                            'state_name'        => $contato->estado,
                            'city_name'         => $contato->cidade,
                            'zip'               => $contato->getCep(),
                            'city_code'         => $contato->codigo_municipio,
                            'district'          => $contato->bairro,
                            'street'            => $contato->logradouro,
                            'number'            => $contato->numero,
                            'complement'        => $contato->complemento,

                            //address
                            'state_name'        => $contato->estado,
                            'city_name'         => $contato->cidade,
                            'zip'               => $contato->getCep(),
                            'city_code'         => $contato->codigo_municipio,
                            'district'          => $contato->bairro,
                            'street'            => $contato->logradouro,
                            'number'            => $contato->numero,
                            'complement'        => $contato->complemento,
                        ]);

                        if($c->tecnico != NULL){

                            $t = $c->tecnico;

                            //carteira_imetro
                            if($t->carteira_imetro != NULL){
                                $path = public_path('uploads/colaboradores/' . $t->carteira_imetro);
                                if(File::exists($path)){
                                    if(!File::exists($new_path)) {
                                        // path does not exist
                                        File::makeDirectory($new_path, $mode = 0777, true, true);
                                    }
                                    $copy = File::copy($path, $new_path . $t->carteira_imetro);
                                } else {
                                    $t->carteira_imetro = NULL;
                                }
                            }

                            //carteira_imetro
                            if($t->carteira_ipem != NULL){
                                $path = public_path('uploads/colaboradores/' . $t->carteira_ipem);
                                if(File::exists($path)){
                                    if(!File::exists($new_path)) {
                                        // path does not exist
                                        File::makeDirectory($new_path, $mode = 0777, true, true);
                                    }
                                    $copy = File::copy($path, $new_path . $t->carteira_ipem);
                                } else {
                                    $t->carteira_ipem = NULL;
                                }
                            }


                            $data_export = array_merge($data_export, [
                                //tecnico
                                'idtecnico'         => $t->idtecnico,
                                'carteira_imetro'   => $t->carteira_imetro,
                                'carteira_ipem'     => $t->carteira_ipem,
                                'desconto_max'      => $t->desconto_max,
                                'acrescimo_max'     => $t->acrescimo_max,
                            ]);


                        }
                    }
                    $sheet->row($i, $data_export);
                    $i++;
                }
            });

        })->store('xls');

    }
}

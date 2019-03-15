<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;
use App\Cliente;
use Illuminate\Console\Command;

class ExportClientes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:export_clientes';

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
        $Clientes = Cliente::all();
        return Excel::create('clientes', function ($excel) use ($Clientes) {
            $excel->sheet('Sheet 1', function($sheet) use($Clientes) {


                $data_cliente = array(
                    'created_at',
                    'idcliente',
//				'cost_center_id',
                    'idcliente_centro_custo',
                    'foto',

//				'picture_id',

                    'idcontato',
//				'legal_person_id',
                    'idpjuridica',
                    'idpfisica',

//				'segment_id',
                    'idsegmento',
                    'segment_name',
                    'idregiao',
                    'region_name',

                    'email_budget',
                    'email_bill',
                    'responsible_name',

                    "cnpj",
                    "ie",
                    "exemption_ie",
                    "social_reason",
                    "fantasy_name",
                    "ativ_economica",
                    "sit_cad_vigente",
                    "sit_cad_status",
                    "data_sit_cad",
                    "reg_apuracao",
                    "data_credenciamento",
                    "ind_obrigatoriedade",
                    "data_ini_obrigatoriedade",

                    "cpf",
                    "type",


                    'technical_price_id',
                    'technical_form_payment_id',
                    'technical_billing_issue_type_id',
                    'technical_due_payment',
                    'technical_credit_limit',

                    'commercial_price_id',
                    'commercial_form_payment_id',
                    'commercial_billing_issue_type_id',
                    'commercial_due_payment',
                    'commercial_credit_limit',


                    'cost_center',

                    'distance',
                    'tolls',
                    'other_costs',
                    'called_number',
                    'active',


//			    'address_id',
                    'state_name',
                    'city_name',
                    'zip',
                    'city_code',
                    'district',
                    'street',
                    'number',
                    'complement',

//			    'contact_id',
                    'phone',
                    'cellphone',
                    'skype',
                    'email',


                );

                $sheet->row(1, $data_cliente);

                $i = 2;

                foreach ($Clientes as $cliente) {
                    $contato = $cliente->contato;
                    $segment = $cliente->segmento;
                    if($segment != NULL){
                        $segment = $segment->descricao;
                    }
                    $region = $cliente->regiao;
                    if($region != NULL){
                        $region = $region->descricao;
                    }
                    $pjuridica = $cliente->pessoa_juridica;
                    $pfisica = $cliente->pessoa_fisica;


//                    verificar se foto existe
                    $new_path = storage_path('exports/clientes/');
                    if($cliente->foto != NULL){
                        $path = public_path('uploads/clientes/' . $cliente->foto);
                        if(File::exists($path)){
                            if(!File::exists($new_path)) {
                                // path does not exist
                                File::makeDirectory($new_path, $mode = 0777, true, true);
                            }
                            $copy = File::copy($path, $new_path . $cliente->foto);
                        } else {
	                        echo('NÃO EXISTE : ' . $cliente->foto . ', idcliente: ' . $cliente->idcliente);
                            $cliente->foto = NULL;
                        }
                    }


                    $data_export = [

	                    'created_at'                => $cliente->getAttribute('created_at'),
                        'idcliente'                 => $cliente->idcliente,
                        'idcliente_centro_custo'    => $cliente->idcliente_centro_custo,
                        'foto'                      => $cliente->foto,


                        'idcontato'                 => $cliente->idcontato,
                        'idpjuridica'               => $cliente->idpjuridica,
                        'idpfisica'                 => $cliente->idpfisica,

                        'idsegmento'                => $cliente->idsegmento,
                        'segment_name'              => $segment,
                        'idregiao'                  => $cliente->idregiao,
                        'region_name'               => $region,

//		    'legal_person_id',
//		    'fisical_person_id',
                        'email_budget'              => $cliente->email_orcamento,
                        'email_bill'                => $cliente->email_nota,
                        'responsible_name'          => $cliente->nome_responsavel,

                        "cnpj"                      => ($pjuridica!=NULL) ? $pjuridica->getCnpj() : NULL,
                        "ie"                        => ($pjuridica!=NULL) ? $pjuridica->getIe() : NULL,
                        "exemption_ie"              => ($pjuridica!=NULL) ? $pjuridica->isencao_ie : NULL,
                        "social_reason"             => ($pjuridica!=NULL) ? $pjuridica->razao_social : NULL,
                        "fantasy_name"              => ($pjuridica!=NULL) ? $pjuridica->nome_fantasia : NULL,
                        "ativ_economica"            => ($pjuridica!=NULL) ? $pjuridica->ativ_economica : NULL,
                        "sit_cad_vigente"           => ($pjuridica!=NULL) ? $pjuridica->sit_cad_vigente : NULL,
                        "sit_cad_status"            => ($pjuridica!=NULL) ? $pjuridica->sit_cad_status : NULL,
                        "data_sit_cad"              => ($pjuridica!=NULL) ? $pjuridica->getDataSitCad() : NULL,
                        "reg_apuracao"              => ($pjuridica!=NULL) ? $pjuridica->reg_apuracao : NULL,
                        "data_credenciamento"       => ($pjuridica!=NULL) ? $pjuridica->getDataCredenciamento() : NULL,
                        "ind_obrigatoriedade"       => ($pjuridica!=NULL) ? $pjuridica->ind_obrigatoriedade : NULL,
                        "data_ini_obrigatoriedade"  => ($pjuridica!=NULL) ? $pjuridica->getDataIniObrigatoriedade() : NULL,

                        "cpf"                       => ($pjuridica==NULL) ? $pfisica->getCpf() : NULL,
                        "type"                      => ($pjuridica!=NULL) ? 'legal_person' : 'fisical_person',


                        'technical_price_id'                => $cliente->idtabela_preco_tecnica,
                        'technical_form_payment_id'         => $cliente->idforma_pagamento_tecnica,
                        'technical_billing_issue_type_id'   => $cliente->idemissao_tecnica,
                        'technical_due_payment'             => json_encode($cliente->prazo_pagamento_tecnica),
                        'technical_credit_limit'            => $cliente->limite_credito_tecnica,
//
                        'commercial_price_id'               => $cliente->idtabela_preco_comercial,
                        'commercial_form_payment_id'        => $cliente->idforma_pagamento_comercial,
                        'commercial_billing_issue_type_id'  => $cliente->idemissao_comercial,
                        'commercial_due_payment'            => json_encode($cliente->prazo_pagamento_comercial),
                        'commercial_credit_limit'           => $cliente->limite_credito_comercial,


                        'cost_center'                       => $cliente->centro_custo,

                        'distance'                          => $cliente->distancia,
                        'tolls'                             => $cliente->pedagios,
                        'other_costs'                       => $cliente->outros_custos,
                        'called_number'                     => $cliente->numero_chamado,
                        'active'                            => 1,




//			'address_id',
                        'state_name'        => $contato->estado,
                        'city_name'         => $contato->cidade,
                        'zip'               => $contato->getCep(),
                        'city_code'         => $contato->codigo_municipio,
                        'district'          => $contato->bairro,
                        'street'            => $contato->logradouro,
                        'number'            => $contato->numero,
                        'complement'        => $contato->complemento,

//			'contact_id',
                        'phone'             => $contato->getTelefone(),
                        'cellphone'         => $contato->getCelular(),
                        'skype'             => $contato->skype,
                        'email'             => $contato->email_orcamento,
                    ];

//				dd($data_export);

                    $sheet->row($i, $data_export);
                    $i++;
                }
            });

        })->store('xls');
    }
}

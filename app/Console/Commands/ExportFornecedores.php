<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;
use App\Fornecedor;
use Illuminate\Console\Command;

class ExportFornecedores extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:export_fornecedores';

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
        $Fornecedores = Fornecedor::all();
        return Excel::create('fornecedores', function ($excel) use ($Fornecedores) {
            $excel->sheet('Sheet 1', function($sheet) use($Fornecedores) {

                $data_fornecedor = array(
                    'idfornecedor',
                    'idcontato',
                    'idpjuridica',
                    'idpfisica',
                    'idsegmento_fornecedor',

                    'segment_name',

                    'budget_email',
                    'group',
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

                $sheet->row(1, $data_fornecedor);

                $i = 2;

                foreach ($Fornecedores as $fornecedor) {
                    $contato = $fornecedor->contato;
                    $segment = $fornecedor->segmento;
                    if($segment != NULL){
                        $segment = $segment->descricao;
                    }
                    $pjuridica = $fornecedor->pessoa_juridica;
                    $pfisica = $fornecedor->pessoa_fisica;

//                    verificar se foto existe
                    $new_path = storage_path('exports/fornecedores/');
                    if($fornecedor->foto != NULL){
                        $path = public_path('uploads/fornecedores/' . $fornecedor->foto);
                        if(File::exists($path)){
                            if(!File::exists($new_path)) {
                                // path does not exist
                                File::makeDirectory($new_path, $mode = 0777, true, true);
                            }
                            $move = File::move($path, $new_path . $fornecedor->foto);
                        } else {
                            $fornecedor->foto = NULL;
                        }
                    }

                    $data_export = [

                        'idfornecedor'          => $fornecedor->idfornecedor,
                        'idcontato'             => $fornecedor->idcontato,
                        'idpjuridica'           => $fornecedor->idpjuridica,
                        'idpfisica'             => $fornecedor->idpfisica,
                        'idsegmento_fornecedor' => $fornecedor->idsegmento_fornecedor,

                        'segment_name'      => $segment,

//		    'legal_person_id',
//		    'fisical_person_id',
                        'budget_email'      => $fornecedor->email_orcamento,
                        'group'             => $fornecedor->grupo,
                        'responsible_name'  => $fornecedor->nome_responsavel,

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


                    $sheet->row($i, $data_export);
                    $i++;
                }
            });

        })->store('xls');
    }
}

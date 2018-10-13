<?php

namespace App\Http\Controllers;

use App\FormaPagamento;
use App\Marca;
use App\Models\ExcelFile;
use App\Models\Instrumentos\InstrumentoBase;
use App\Models\Instrumentos\InstrumentoSetor;
use App\Models\PrazoPagamento;
use App\Models\TipoEmissaoFaturamento;
use App\Models\Ajustes\RecursosHumanos\Clientes\Regiao;
use App\Models\Ajustes\RecursosHumanos\Clientes\Segmento;
use App\TabelaPreco;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Maatwebsite\Excel\Facades\Excel;
use Validator;
use App\Cliente;
use App\PessoaJuridica;
use App\PessoaFisica;
use App\Contato;
use Illuminate\Http\Request;
use App\Http\Requests\ClientesRequest;

use App\Http\Requests;

class ClientesController extends Controller
{
    private $Page;
    private $colaborador;

    public function __construct()
    {
        $this->colaborador = (Auth::check()) ? Auth::user()->colaborador : NULL;
        $this->Page = (object)[
            'link'              => "clientes",
            'Target'            => "Cliente",
            'Targets'           => "Clientes",
            'Titulo'            => "Clientes",
            'titulo_primario'   => "",
            'titulo_secundario' => "",
            'search_no_results' => "Nenhum cliente encontrado!",
            'extras'            => NULL,
        ];
    }

    public function index(Request $request)
    {
        $Buscas = Cliente::findByText($request->get('busca'))->get()->map(function ($s) {
            return [
                'id' => $s->idcliente,
                'razao_social' => $s->getRazaoSocial(),
                'validated_color' => $s->getValidatedColor(),
                'validated_text' => $s->getValidatedText(),
                'name' => $s->getName(),
                'document' => $s->getDocument(),
                'responsible' => $s->getResponsibleName(),
                'phone' => $s->getPhone(),
                'created_at' => $s->getCreatedAtFormatted(),
                'created_at_time' => $s->getCreatedAtTime()
            ];
        });
        return view('pages.'.$this->Page->link.'.index')
            ->with('Page', $this->Page)
            ->with('Buscas',$Buscas);
    }

    public function create()
    {
        $this->Page->titulo_primario    = "Cadastrar ";
        $this->Page->titulo_secundario  = "Dados do Cliente";
        $this->Page->extras = [
            'centro_custo'      => Cliente::all(),
            'segmentos'         => Segmento::all(),
            'regioes'           => Regiao::all(),
            'tabela_precos'     => TabelaPreco::all(),
            'formas_pagamentos' => FormaPagamento::all(),
            'tipos_emissao_faturamento' => TipoEmissaoFaturamento::all(),
        ];
        return view('pages.'.$this->Page->link.'.master')
            ->with('Page', $this->Page);
    }

    public function show($id,$tab='sobre')
    {
        $this->Page->titulo_primario = "Visualização de ";
        $this->Page->tab = $tab;
	    $this->Page->extras = [
            'centro_custo'  => Cliente::all(),
            'segmentos'     => Segmento::all(),
            'regioes'       => Regiao::all(),
            'tabela_precos' => TabelaPreco::all(),
            'marcas'        => Marca::all(),

            'setors' => InstrumentoSetor::all(),
            'instrumentos_base' => InstrumentoBase::all(),

            'formas_pagamentos' => FormaPagamento::all(),
            'tipos_emissao_faturamento' => TipoEmissaoFaturamento::all(),
        ];
        $Cliente = Cliente::find($id);

//        return $Cliente->getMaxCentroCusto();
        return view('pages.'.$this->Page->link.'.show')
            ->with('Cliente', $Cliente)
            ->with('Page', $this->Page);
    }

    public function store(ClientesRequest $request)
    {
        $data = $request->all();

        $data['idcolaborador_criador'] = $this->colaborador->idcolaborador;
        if ($this->colaborador->hasRole('admin')) {
            $data['idcolaborador_validador'] = $this->colaborador->idcolaborador;
            $data['validated_at'] = Carbon::now()->toDateTimeString();
        }

        //store foto
        if ($request->hasfile('foto')) {
            $img = new ImageController();
            $data['foto'] = $img->store($request->file('foto'), $this->Page->link);
        } else {
            $data['foto'] = NULL;
        }


        //store CONTATO
        $Contato = Contato::create($data);
        $data['idcontato'] = $Contato->idcontato;

//            return $data;
        if ($data['tipo_cliente'] == "0") {
            //store física
            $TipoCliente = PessoaFisica::create($data);
            $data['idpfisica'] = $TipoCliente->idpfisica;
        } else {
            //store juridica
            $TipoCliente = PessoaJuridica::create($data);
            $data['idpjuridica'] = $TipoCliente->idpjuridica;
        }

        //store Cliente
        if ($data['centro_custo'] != '0') {
            $data['idcliente_centro_custo'] = ($data['idcliente_centro_custo'] == '') ? NULL : $data['idcliente_centro_custo'];
            $data['limite_credito_tecnica'] = NULL;
        } else {
            $data['idcliente_centro_custo'] = NULL;
        }

        foreach (['tecnica', 'comercial'] as $tipo) {
            $prazo_pagamento['id'] = $data['prazo_pagamento_' . $tipo];
            switch ($data['prazo_pagamento_' . $tipo]) {
                case PrazoPagamento::_STATUS_A_VISTA_:
                    $prazo_pagamento['extras'] = '';
                    break;
                case PrazoPagamento::_STATUS_PARCELADO_:
                    $prazo_pagamento['extras'] = $data['parcela_' . $tipo];
                    break;
            }
            $data['prazo_pagamento_' . $tipo] = json_encode($prazo_pagamento);
        }

        $Cliente = Cliente::create($data);

        session()->forget('mensagem');
        session(['mensagem' => $this->Page->Target . ' adicionado com sucesso!']);
        return Redirect::route('clientes.show', $Cliente->idcliente);

    }

    public function update(ClientesRequest $request, $id)
    {
        $Cliente = Cliente::find($id);
        $dataUpdate = $request->all();

        //update foto
        if ($request->hasfile('foto')) {
            $img = new ImageController();
            $dataUpdate['foto'] = $img->store($request->file('foto'), $this->Page->link);
        }

        if($Cliente->getType()->tipo_cliente==0){
            //update física
            $Cliente->pessoa_fisica->update($dataUpdate);
        } else {
            //update juridica
            $Cliente->pessoa_juridica->update($dataUpdate);
        }

        //update CLIENTE
        if ($dataUpdate['centro_custo'] != '0') {
            $dataUpdate['idcliente_centro_custo'] = ($dataUpdate['idcliente_centro_custo'] == '') ? NULL : $dataUpdate['idcliente_centro_custo'];
        } else {
            $dataUpdate['idcliente_centro_custo'] = NULL;
        }

        //prazo_pagamento_tecnica
        $Cliente->updatePrazo($dataUpdate);
        unset($dataUpdate['prazo_pagamento_comercial']);
        unset($dataUpdate['prazo_pagamento_tecnica']);

        $Cliente->update($dataUpdate);
        //update CONTATO
        $Cliente->contato->update($dataUpdate);

        session()->forget('mensagem');
        session(['mensagem' => ($this->Page->Target . ' atualizado com sucesso!')]);
        return Redirect::route('clientes.show', $Cliente->idcliente);
    }

    public function destroy($id)
    {
        $data = Cliente::find($id);
        $data->delete();
        return response()->json(['status' => '1',
            'response' => $this->Page->msg_rem]);
    }

    public function validar($id)
    {
        $Cliente = Cliente::find($id);
        if ($this->colaborador->hasRole('admin')) {
            $data['idcolaborador_validador'] = $this->colaborador->idcolaborador;
            $data['validated_at'] = Carbon::now()->toDateTimeString();
            $Cliente->update($data);
            session()->forget('mensagem');
            session(['mensagem' => ($this->Page->Target . ' atualizado com sucesso!')]);
        }
        return Redirect::route('clientes.show', $id);
    }

    public function exportarCodMunicipio(ExcelFile $export)
    {
        $Clientes = Cliente::all();
        return $export->sheet('sheetName', function ($sheet) use ($Clientes) {

            $data = array(
                'idcontato',
                'nome_principal',
                'razao_social',
                'cpf_cnpj',
                'estado',
                'municipio',
                'codigo_municipio',
            ); //porcentagem

            $sheet->row(1, $data);
            $i = 2;
            foreach ($Clientes as $cliente) {
                $cliente_tipo = $cliente->getType();
                $sheet->row($i, array(
                    $cliente->idcontato,
                    $cliente_tipo->nome_principal,
                    $cliente_tipo->razao_social,
                    $cliente_tipo->documento,
                    $cliente->contato->estado,
                    $cliente->contato->cidade,
                    '',
                ));
                $i++;
            }
        })->export('xls');
    }

    public function importarCodMunicipio(ExcelFile $export)
    {
        $file = storage_path('uploads' . DIRECTORY_SEPARATOR . 'import' . DIRECTORY_SEPARATOR . 'export_19_04_2017-15_26_cod_municipio.xls');
        $reader = Excel::load($file, function ($sheet) {
            // Loop through all sheets
            $sheet->each(function ($row) {
                $data_contato = ['idcontato', 'municipio', 'codigo_municipio'];
                foreach ($data_contato as $col) {
                    $contato[$col] = $row->$col;
                }
                $contato['municipio'] = strtoupper($row->municipio);
                $contato['codigo_municipio'] = $row->codigo_municipio;
                $Contato = Contato::find($row->idcontato);
                $Contato->update($contato);
                echo "<br>idcontato: " . $row->idcontato . " - municipio: " . $contato['municipio'] . " - codigo_municipio: " . $contato['codigo_municipio'];
            });

        })->ignoreEmpty();
    }

	public function exportar(ExcelFile $export)
	{
		$Clientes = Cliente::all();
		return $export->sheet('sheetName', function ($sheet) use ($Clientes) {

			$data_fornecedor = array(
				'idcliente',
				'razao_social',
				'nome_fantasia',
				'document',

				'email_orcamento',
				'email_nota',
				'limite_credito',
				'nome_responsavel',
				'distancia',
				'pedagios',
				'outros_custos',

				'numero_chamado',

			);

			$sheet->row(1, $data_fornecedor);

			$i = 2;

			foreach ($Clientes as $cliente) {
				$contato = $cliente->contato;
				$pjuridica = $cliente->pessoa_juridica;
				$pfisica = $cliente->pessoa_fisica;


				$data_export = [

					'idcliente' => $cliente->idcliente,
					'razao_social' => ($pjuridica != NULL) ? $pjuridica->razao_social : '',
					'nome_fantasia' => ($pjuridica != NULL) ? $pjuridica->nome_fantasia : '',
					'document' => ($pjuridica != NULL) ? $pjuridica->cnpj : $pfisica->cpf,

					'email_orcamento' => $cliente->email_orcamento,
					'email_nota' => $cliente->email_nota,
					'limite_credito' => $cliente->limite_credito,
					'nome_responsavel' => $cliente->nome_responsavel,
					'distancia' => $cliente->distancia,
					'pedagios' => $cliente->pedagios,
					'outros_custos' => $cliente->outros_custos,

					'numero_chamado' => $cliente->numero_chamado,
				];


				$sheet->row($i, $data_export);
				$i++;
			}
		})->export('xls');
	}

	public function exportarFile(ExcelFile $export)
	{
		$Clientes = Cliente::all();
		return $export->sheet('sheetName', function ($sheet) use ($Clientes) {

			$data_cliente = array(
				'idcliente',
//				'cost_center_id',
				'idcliente_centro_custo',

//				'picture_id',

				'idcontato',
//				'legal_person_id',
				'idpjuridica',
				'idpfisica',

//				'segment_id',
				'idsegmento',
				'segment_name',

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
				$pjuridica = $cliente->pessoa_juridica;
				$pfisica = $cliente->pessoa_fisica;



				$data_export = [

					'idcliente'                 => $cliente->idcliente,
					'idcliente_centro_custo'    => $cliente->idcliente_centro_custo,


					'idcontato'                 => $cliente->idcontato,
					'idpjuridica'               => $cliente->idpjuridica,
					'idpfisica'                 => $cliente->idpfisica,

					'idsegmento'                => $cliente->idsegmento,
					'segment_name'              => $segment,

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
		})->export('xls');
	}
}

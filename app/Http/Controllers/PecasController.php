<?php

namespace App\Http\Controllers;

use App\Cfop;
use App\Cst;
use App\Grupo;
use App\Helpers\DataHelper;
use App\Helpers\ExportHelper;
use App\Marca;
use App\Models\ExcelFile;
use App\NaturezaOperacao;
use App\Ncm;
use App\Peca;
use App\PecaTributacao;
use App\TabelaPreco;
use App\TabelaPrecoPeca;
use App\Unidade;
use App\Http\Requests\PecasRequest;
use Illuminate\Support\Facades\Redirect;
use App\Fornecedor;
use Illuminate\Http\Request;


class PecasController extends Controller
{
    public $required;
    private $Page;

    public function __construct()
    {
        /*
        $this->middleware('role:empresa');
        if(Auth::check()){
            $this->empresa_id = (Auth::user()->empresa == "")?'*':Auth::user()->empresa->EMP_ID;
            $this->Empresa = (Auth::user()->empresa == "")?'*':Auth::user()->empresa;
        }
        */
        $this->Page = (object)[
            'table'             => "pecas",
            'link'              => "pecas",
            'primaryKey'        => "idpeca",
            'Target'            => "Produto/Peça",
            'Targets'           => "Produtos/Peças",
            'Titulo'            => "Produtos/Peças",
            'search_no_results' => "Nenhuma Produto/Peça encontrada!",
            'msg_add'           => 'Produto/Peça adicionada com sucesso!',
            'msg_upd'           => 'Produto/Peça atualizada com sucesso!',
            'msg_rem'           => 'Produto/Peça removida com sucesso!',
            'titulo_primario'   => "",
            'titulo_secundario' => "",
        ];
    }

    public function index(Request $request)
    {
        $titulo = "Busca de ";
        if(isset($request['busca'])){
            $busca = $request['busca'];
            $Buscas = Peca::where('descricao', 'like', '%'.$busca.'%')
                ->paginate(10);
        } else {
            $Buscas = Peca::paginate(10);
        }
        return view('pages.'.$this->Page->link.'.index')
            ->with('Page', $this->Page)
            ->with('Buscas',$Buscas);
    }

    public function create()
    {
        $this->Page->titulo_primario    = "Cadastrar ";
        $this->Page->titulo_secundario  = "Dados da ".$this->Page->Target;
        $this->Page->extras = [
            'fornecedores'          => Fornecedor::all(),
            'marcas'                => Marca::all(),
            'unidades'              => Unidade::all(),
            'grupos'                => Grupo::all(),
            'ncm' => Ncm::get()->take(100),
            'cst' => Cst::all(),
            'cfop' => Cfop::all(),
            'natureza_operacao' => NaturezaOperacao::all(),
            'tabela_preco'          => TabelaPreco::all(),
        ];
        return view('pages.'.$this->Page->link.'.master')
            ->with('Page', $this->Page);
    }

    public function show($id)
    {
        $this->Page->titulo_primario = "Visualização de ";
        $Peca = Peca::find($id);
        $this->Page->extras = [
            'fornecedores' => Fornecedor::all(),
            'marcas' => Marca::all(),
            'unidades' => Unidade::all(),
            'grupos' => Grupo::all(),
            'ncm' => Ncm::get()->take(100),
            'cst' => Cst::all(),
            'cfop' => Cfop::all(),
            'natureza_operacao' => NaturezaOperacao::all(),
            'tabela_preco' => TabelaPreco::all(),
        ];
        return view('pages.' . $this->Page->link . '.show')
            ->with('Peca', $Peca)
            ->with('Page', $this->Page);
    }

    public function store(PecasRequest $request)
    {
        $data = $request->all();
//        return $data;
        $campos = ['comissao_tecnico', 'comissao_vendedor', 'custo_final'];
        foreach ($campos as $val) {
            if ($data[$val] == '') {
                $data[$val] = NULL;
            } else {
                $data[$val] = str_replace(',', '.', str_replace('.', '', $data[$val]));
            }
        }
        //store foto da peca
        if ($request->hasfile('foto')) {
            $img = new ImageController();
            $data['foto'] = $img->store($request->file('foto'), $this->Page->table);
        } else {
            $data['foto'] = NULL;
        }
        $PecaTributacao = PecaTributacao::create($data);
        $data['idpeca_tributacao'] = $PecaTributacao->id;
        $Peca = Peca::create($data);
        $dados = [
            'margens' => $request->get('margem'),
            'margem_minimo' => $request->get('margem_minimo'),
            'valor' => $request->get('valor'),
        ];
        $id['idpeca'] = $Peca->idpeca;
        TabelaPrecoPeca::insert(DataHelper::storePriceTable($id, $dados, TabelaPreco::all()));

        session()->forget('mensagem');
        session(['mensagem' => $this->Page->msg_add]);
        return Redirect::route('pecas.show', $Peca->idpeca);
    }

    public function update(PecasRequest $request, $id)
    {
        $Peca = Peca::find($id);
        $dataUpdate = $request->all();
        $campos = ['comissao_tecnico', 'comissao_vendedor', 'custo_final'];
        foreach ($campos as $val) {
            if ($dataUpdate[$val] == '') {
                $dataUpdate[$val] = NULL;
            }
        }

        //store da nova foto da peca
        if ($request->hasfile('foto')) {
            $img = new ImageController();
            $dataUpdate['foto'] = $img->update($request->file('foto'), $this->Page->table, $Peca->foto);
        }
        $Peca->update($dataUpdate);

        $Peca->peca_tributacao->update($dataUpdate);

        //ATUALIZANDO OS PREÇOS E MARGENS
        $dados = [
            'margens' => $request->get('margem'),
            'margem_minimo' => $request->get('margem_minimo'),
            'valor' => $Peca->peca_tributacao->custo_final_float(),
        ];
        $Tabelas_preco = $Peca->tabela_preco;
        DataHelper::updatePriceTable($dados, $Tabelas_preco);

        session()->forget('mensagem');
        session(['mensagem' => $this->Page->msg_upd]);
        return Redirect::route('pecas.show', $Peca->idpeca);
    }

    public function destroy($id)
    {
        return response()->json(['status' => '0',
            'response' => 'NÃO É POSSÍVEL REMOVER PEÇAS PELA ASSOCIAÇÃO COM OUTRAS TABELAS: CONTATE O ADMINISTRADOR!']);
        $data = Peca::find($id);
        $data->delete();
        return response()->json(['status' => '1',
            'response' => $this->Page->msg_rem]);
    }


	public function exportarTabelaPreco( ExcelFile $export ) {
		return ExportHelper::tabelaPrecoPecas( $export );
	}

    public function RedirectFornecedor($id,$tab='pecas')
    {
        $FornecedoresController = new FornecedoresController();
        return $FornecedoresController->show($id,$tab);
    }





	public function stocks() {
		$this->Page->extras['tools']         = Tool::getAlltoSelectList();
		$this->Page->extras['type_stock']    = 'tools';
		$this->Page->extras['voids']         = Voidx::unuseds()->pluck( 'number', 'id' );
		$this->Page->extras['colaboradores'] = Colaborador::getAlltoSelectList();
		$this->Page->titulo_primario         = "Listagem de Ferramentas";
		$Buscas                              = ToolStock::all();

		return view( 'pages.recursos.stocks.index' )
			->with( 'Page', $this->Page )
			->with( 'Buscas', $Buscas );
	}

	public function stocksStore( Request $request ) {
		ToolStock::createWithVoid( $request->all() );
		session()->forget( 'mensagem' );
		session( [ 'mensagem' => $this->Page->msg_stock ] );

		return redirect()->route( 'tools.stocks' );
	}






	//Tecnico
	public function getFormRequest( Request $request ) {
    	return $request->all();
		$Tecnico                       = $this->colaborador->tecnico;
		$this->Page->search_no_results = "Nenhuma Requisição encontrada!";
		$max_can_request               = $Tecnico->getMaxCanRequest('parts');
		$can_request                   = ( $Tecnico->waitingRequisicoes('parts')->count() < 1 );
		$this->Page->extras            = [
			'return'            => $Tecnico->parts(),
			'max_can_request'  => $max_can_request,
			'can_request'      => ( ( $max_can_request > 0 ) && ( $can_request ) ),
			'requisicoes'      => $Tecnico->requisicoes('parts'),
			'type'             => $this->route,
		];

		return view( 'pages.recursos.requests.tecnico.index' )
			->with( 'Page', $this->Page );
	}
	public function postFormRequest( Request $request ) {
		return $request->all();
		$mensagem = RequestTools::openToolsRequest( [
			'idrequester' => $this->colaborador->idcolaborador,
			'parameters'  => $request->only( [ 'opcao', 'quantidade' ] ),
			'reason'      => $request->get( 'reason' ),
		] );
		session()->forget( 'mensagem' );
		session( [ 'mensagem' => $mensagem ] );
		return redirect()->route( 'tools.requisicao' );
	}


	//Admin
	public function listRequests( Request $request ) {
		return $request->all();
		$this->Page->search_no_results  = "Nenhuma Requisição encontrada!";
		$this->Page->extras = [
			'requests'  => RequestTools::tools()->get(),
			'tecnicos'  => Tecnico::all(),
			'type'      => $this->route,
		];
		return view( 'pages.recursos.requests.admin.index' )
			->with( 'Page', $this->Page );
	}

	//Admin/Gestor
	public function deniedRequest( Request $request ) {
		return $request->all();
		$data              = $request->only( 'id', 'response' );
		$data['idmanager'] = $this->colaborador->idcolaborador;
		$mensagem          = RequestTools::deny( $data );
		session()->forget( 'mensagem' );
		session( [ 'mensagem' => $mensagem ] );
		return redirect()->route( 'tools.requisicoes' );
	}

	/*
		public function getReports( Request $request ) {
			$Buscas                         = null;
			$this->Page->search_no_results  = "Nenhuma Requisição encontrada!";
			$this->Page->extras['tecnicos'] = Tecnico::all();

			return view( 'pages.recursos.tools.admin.reports' )
				->with( 'Page', $this->Page )
				->with( 'Buscas', $Buscas );
		}
	*/

	public function postFormPassRequest( Request $request ) {
		RETURN $request->all();
		$data              = $request->only( [ 'id', 'valores' ] );
		$data['idmanager'] = $this->colaborador->idcolaborador;
		$mensagem          = RequestTools::sendSeloLacreRequest( $data );
		session()->forget( 'mensagem' );
		session( [ 'mensagem' => $mensagem ] );

		return redirect()->route( 'tools.requisicoes' );
	}



	public function exportar(ExcelFile $export)
	{
		$Pecas = Peca::all();
		return $export->sheet('sheetName', function ($sheet) use ($Pecas) {

			$data_peca = array(
				'idpeca',
				'idfornecedor',
				'idmarca',
				'idgrupo',
				'idunidade',
				'tipo',
				'codigo_auxiliar',
				'codigo_barras',
				'descricao',
				'descricao_tecnico',
				'sub_grupo',
				'garantia',
				'comissao_tecnico',
				'comissao_vendedor',
				'ncm',
				'cest',
				'icms_base_calculo',
				'icms_valor_total',
				'icms_base_calculo_st',
				'icms_valor_total_st',
				'valor_ipi',
				'valor_unitario_tributavel',
				'icms_situacao_tributaria',
				'icms_origem',
				'pis_situacao_tributaria',
				'valor_frete',
				'valor_seguro',
				'custo_final',
				'idnatureza_operacao',
				'idcfop_venda',
				'idcst_venda',
				'gricki',
				'savegnago',
				'geral',
			); //porcentagem

			$sheet->row(1, $data_peca);
			//'idpeca_tributacao',
//            dd($data_peca);

			$i = 2;
			foreach ($Pecas as $peca) {
				$sheet->row($i, array(
					$peca->idpeca,
					$peca->idfornecedor,
					$peca->idmarca,
					$peca->idgrupo,
					$peca->idunidade,
					$peca->tipo,
					$peca->codigo_auxiliar,
					$peca->codigo_barras,
					$peca->descricao,
					$peca->descricao_tecnico,
					$peca->sub_grupo,
					$peca->garantia,
					$peca->comissao_tecnico,
					$peca->comissao_vendedor,
					$peca->peca_tributacao->ncm->codigo,
					$peca->peca_tributacao->cest,
					$peca->peca_tributacao->icms_base_calculo,
					$peca->peca_tributacao->icms_valor_total,
					$peca->peca_tributacao->icms_base_calculo_st,
					$peca->peca_tributacao->icms_valor_total_st,
					$peca->peca_tributacao->valor_ipi,
					$peca->peca_tributacao->valor_unitario_tributavel,
					$peca->peca_tributacao->icms_situacao_tributaria,
					$peca->peca_tributacao->idncicms_origemm,
					$peca->peca_tributacao->pis_situacao_tributaria,
					$peca->peca_tributacao->valor_frete,
					$peca->peca_tributacao->valor_seguro,
					$peca->peca_tributacao->custo_final,
					$peca->peca_tributacao->idnatureza_operacao,
					$peca->peca_tributacao->idcfop,
					$peca->peca_tributacao->idcst,
					$peca->tabela_preco_by_name('TABELA GRICKI')->preco,
					$peca->tabela_preco_by_name('TABELA SAVEGNAGO')->preco,
					$peca->tabela_preco_by_name('TABELA GERAL')->preco
				));
				$i++;
			}
		})->export('xls');
	}


	public function exportarFile(ExcelFile $export)
	{
		$Pecas = Peca::all();
		return $export->sheet('sheetName', function ($sheet) use ($Pecas) {

			$data_peca = array(

				'idpeca',

				'idfornecedor',
				'brand_id',
				'group_id',
				'picture',
//				'idmarca',
//				'idgrupo',
//				'idunidade',

				'unity_name',
				'type',

				'auxiliar_code',
				'bar_code',
				'description',
				'technical_description',

				'sub_grupo',
				'warranty',
				'technical_commission',
				'seller_commission',

				//taxation
				'ncm_id',
				'cfop',
				'cst',
				'nature_operation',
				'cest',

				'icms_base_calculo',
				'icms_valor_total',
				'icms_base_calculo_st',
				'icms_valor_total_st',

				'icms_origem',
				'icms_situacao_tributaria',
				'pis_situacao_tributaria',
				'cofins_situacao_tributaria',

				'valor_unitario_comercial',
				'unidade_tributavel',
				'valor_unitario_tributavel',

				'valor_ipi',
				'valor_frete',
				'valor_seguro',
				'valor_total',
			); //porcentagem

			$sheet->row(1, $data_peca);

			$i = 2;

			foreach ($Pecas as $peca) {

				$peca_t = $peca->peca_tributacao;
//				dd($peca_t);

				$data_export = [

					'idpeca'                => $peca->idpeca,
					'idfornecedor'          => $peca->idfornecedor,
					'brand_id'              => $peca->idmarca,
					'group_id'              => $peca->idgrupo,
					'picture'               => $peca->foto,

					'unity_name'            => $peca->unidade->codigo,
					'type'                  => $peca->tipo,

					'auxiliar_code'         => $peca->codigo_auxiliar,
					'bar_code'              => $peca->codigo_barras,
					'description'           => $peca->descricao,
					'technical_description' => $peca->descricao_tecnico,

					'sub_grupo'             => $peca->sub_grupo,
					'warranty'              => $peca->garantia,
					'technical_commission'  => $peca->comissao_tecnico,
					'seller_commission'     => $peca->comissao_vendedor,

//taxation
					'ncm_id'                => $peca_t->idncm,
					'cfop'                  => $peca_t->cfop->numeracao,
					'cst'                   => $peca_t->cst->numeracao,
					'nature_operation'      => $peca_t->natureza_operacao->descricao,
					'cest'                  => $peca_t->cest,

					'icms_base_calculo'     => ($peca_t->icms_base_calculo),
					'icms_valor_total'      => ($peca_t->icms_valor_total),
					'icms_base_calculo_st'  => ($peca_t->icms_base_calculo_st),
					'icms_valor_total_st'   => ($peca_t->icms_valor_total_st),

					'icms_origem'                   => $peca_t->icms_origem,
					'icms_situacao_tributaria'      => $peca_t->icms_situacao_tributaria,
					'pis_situacao_tributaria'       => $peca_t->pis_situacao_tributaria,
					'cofins_situacao_tributaria'    => $peca_t->cofins_situacao_tributaria,

					'valor_unitario_comercial'      => ($peca_t->valor_unitario_comercial),
					'unidade_tributavel'            => ($peca_t->unidade_tributavel),
					'valor_unitario_tributavel'     => ($peca_t->valor_unitario_tributavel),

					'valor_ipi'                     => ($peca_t->valor_ipi),
					'valor_frete'                   => ($peca_t->valor_frete),
					'valor_seguro'                  => ($peca_t->valor_seguro),
					'valor_total'                   => ($peca_t->custo_final),

				];

				$sheet->row($i, $data_export);
				$i++;
			}
		})->export('xls');
	}
}

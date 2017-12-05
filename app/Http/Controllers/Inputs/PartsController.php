<?php

namespace App\Http\Controllers\Inputs;

use App\Colaborador;
use App\Models\Requests\Request as RequestPecas;
use App\Http\Controllers\Controller;
use App\Models\Inputs\Stocks\PartStock;
use App\Peca;
use App\Tecnico;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PartsController extends Controller {
	private $Page;
	private $colaborador;
	private $view_folder = "pages.recursos.models";
	private $route = "parts";

	public function __construct() {
		$this->colaborador = Auth::user()->colaborador;
		$this->Page        = (object) [
			'link'              => $this->route,
			'view_folder'       => $this->view_folder,
			'Target'            => "Peça",
			'Targets'           => "Peças",
			'Titulo'            => "Peças",
			'extras'            => [],
			'constraints'       => [],
			'search_results'    => "Peças encontrados!",
			'search_no_results' => "Nenhum Peça encontrado!",
			'msg_add'           => 'Peça adicionado com sucesso!',
			'msg_upd'           => 'Peça atualizado com sucesso!',
			'msg_rem'           => 'Peça removida com sucesso!',
			'msg_stock_add'     => 'Estoque de Peças adicionado com sucesso!',
			'titulo_primario'   => "",
			'titulo_secundario' => "",
		];
	}

//	public function index() {
//		$this->Page->extras['unities'] = Unidade::pluck( 'codigo', 'idunidade' );
//		$this->Page->extras['brands']  = Brand::pluck( 'description', 'id' );
//		$this->Page->extras['type']    = $this->route;
//		$this->Page->titulo_primario   = "Listagem de Peças";
//
//		$Buscas = Peca::all();
//
//		return view( $this->Page->view_folder . '.index' )
//			->with( 'Page', $this->Page )
//			->with( 'Buscas', $Buscas );
//	}

	// ADMIN - LISTAGEM DO ESTOQUE

	public function stocks() {
		$this->Page->extras['parts']         = Peca::getAlltoSelectList();
		$this->Page->extras['type_stock']    = 'parts';
		$this->Page->extras['colaboradores'] = Colaborador::getAlltoSelectList();
		$this->Page->titulo_primario         = "Listagem de Peças";
		$Buscas                              = PartStock::all();

		return view( 'pages.recursos.stocks.index' )
			->with( 'Page', $this->Page )
			->with( 'Buscas', $Buscas );
	}

	// ADMIN - ARMAZENAMENTO DO ESTOQUE

	public function stocksStore( Request $request ) {
		PartStock::create( $request->all() );
		session()->forget( 'mensagem' );
		session( [ 'mensagem' => $this->Page->msg_stock_add ] );

		return redirect()->route( $this->route . '.stocks' );
	}

	// ADMIN - LISTAGEM DAS REQUISIÇÕES

	public function listRequests( Request $request ) {
		$this->Page->search_no_results  = "Nenhuma Requisição encontrada!";
		$this->Page->extras = [
			'requests'  => RequestPecas::parts()->get(),
			'tecnicos'  => Tecnico::all(),
			'type'      => $this->route,
		];
		return view( 'pages.recursos.requests.admin.index' )
			->with( 'Page', $this->Page );
	}

	// ADMIN - ACEITAÇÃO DA REQUISIÇÃO

	public function postFormPassRequest( Request $request )
	{
		$data              = $request->only( [ 'id'] );
		$data['idmanager'] = $this->colaborador->idcolaborador;
		$mensagem          = RequestPecas::sendPartsRequest( $data );
		session()->forget( 'mensagem' );
		session( [ 'mensagem' => $mensagem ] );

		return redirect()->route( 'parts.requisicoes' );
	}


	public function deniedRequest( Request $request )
	{
		return $request->all();
		$data              = $request->only( 'id', 'response' );
		$data['idmanager'] = $this->colaborador->idcolaborador;
		$mensagem          = RequestPecas::deny( $data );
		session()->forget( 'mensagem' );
		session( [ 'mensagem' => $mensagem ] );
		return redirect()->route( 'parts.requisicoes' );
	}

//
//	public function getReports( Request $request ) {
//		$Buscas                         = null;
//		$this->Page->search_no_results  = "Nenhuma Requisição encontrada!";
//		$this->Page->extras['tecnicos'] = Tecnico::all();
//
//		return view( 'pages.recursos.parts.admin.reports' )
//			->with( 'Page', $this->Page )
//			->with( 'Buscas', $Buscas );
//	}





	// TÉCNICO - LISTAGEM DAS REQUISIÇÕES

	public function getFormRequest( Request $request )
	{
		$Tecnico                       = $this->colaborador->tecnico;
		$this->Page->search_no_results = "Nenhuma Requisição encontrada!";

//		$max_can_request               = $Tecnico->getMaxCanRequest('parts');
//		$can_request                   = ( $Tecnico->waitingRequisicoes('parts')->count() < 1 );
		$this->Page->extras            = [
			'pecas'            => PartStock::getAlltoSelectList(),
			'return'            => $Tecnico->parts(),
			'max_can_request'  => 1,
			'can_request'      => 1,
			'requisicoes'      => $Tecnico->requisicoes('parts'),
			'type'             => $this->route,
		];

		return view( 'pages.recursos.requests.tecnico.index' )
			->with( 'Page', $this->Page );
	}


	// TÉCNICO - LANÇAMENTO DE REQUISIÇÃO

	public function postFormRequest( Request $request )
	{
		$mensagem = RequestPecas::openPartsRequest( [
			'idrequester' => $this->colaborador->idcolaborador,
			'parameters'  => $request->only( [ 'opcao', 'id' ] ),
			'reason'      => $request->get( 'reason' ),
		] );
		session()->forget( 'mensagem' );
		session( [ 'mensagem' => $mensagem ] );
		return redirect()->route( 'parts.requisicao' );
	}







	public function store( Request $request )
	{
		return $request->all();
		Pecas::create( $request->all() );
		session()->forget( 'mensagem' );
		session( [ 'mensagem' => $this->Page->msg_add ] );

		return redirect()->route( $this->route . '.index' );

	}

	public function update( Request $request, $id ) {
		return $request->all();
		$Patern = Pecas::findOrFail( $id );
		$Patern->update( $request->all() );
		session()->forget( 'mensagem' );
		session( [ 'mensagem' => $this->Page->msg_upd ] );

		return redirect()->route( $this->route . '.index' );
	}


}

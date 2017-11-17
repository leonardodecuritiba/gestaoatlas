<?php

namespace App\Http\Controllers\Inputs;

use App\Colaborador;
use App\Models\Requests\Request as RequestPecas;
use App\Http\Controllers\Controller;
use App\Models\Commons\Brand;
use App\Models\Inputs\Pecas;
use App\Models\Inputs\Stocks\PecasStock;
use App\Peca;
use App\Unidade;
use App\Tecnico;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Zizaco\Entrust\EntrustFacade;

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

	public function index() {
		return 1;
		$this->Page->extras['unities'] = Unidade::pluck( 'codigo', 'idunidade' );
		$this->Page->extras['brands']  = Brand::pluck( 'description', 'id' );
		$this->Page->extras['type']    = $this->route;
		$this->Page->titulo_primario   = "Listagem de Peças";

		$Buscas = Pecas::with( 'brand', 'unity' )->get();

		return view( $this->Page->view_folder . '.index' )
			->with( 'Page', $this->Page )
			->with( 'Buscas', $Buscas );
	}

	public function show( $id )
	{
		$Pecas                         = Pecas::findOrFail( $id );
		$this->Page->extras['unities'] = Unidade::pluck( 'codigo', 'idunidade' );
		$this->Page->extras['brands']  = Brand::pluck( 'description', 'id' );
		$this->Page->extras['type']    = $this->route;
		$this->Page->titulo_primario   = "Editar ";
		$this->Page->titulo_secundario = "Dados do " . $this->Page->Target;

		return view( $this->Page->view_folder . '.master' )
			->with( 'Page', $this->Page )
			->with( 'Data', $Pecas );
	}


//	public function stocks() {
//		$this->Page->extras['parts']         = Pecas::getAlltoSelectList();
//		$this->Page->extras['type_stock']    = 'parts';
//		$this->Page->extras['voids']         = Voidx::unuseds()->pluck( 'number', 'id' );
//		$this->Page->extras['colaboradores'] = Colaborador::getAlltoSelectList();
//		$this->Page->titulo_primario         = "Listagem de Padrões";
//		$Buscas                              = PecasStock::all();
//
//		return view( 'pages.recursos.stocks.index' )
//			->with( 'Page', $this->Page )
//			->with( 'Buscas', $Buscas );
//	}
//
//	public function stocksStore( Request $request ) {
//		PecasStock::createWithVoid( $request->all() );
//		session()->forget( 'mensagem' );
//		session( [ 'mensagem' => $this->Page->msg_stock_add ] );
//
//		return redirect()->route( $this->route . '.stocks' );
//	}
//
//	public function store( Request $request ) {
//		Pecas::create( $request->all() );
//		session()->forget( 'mensagem' );
//		session( [ 'mensagem' => $this->Page->msg_add ] );
//
//		return redirect()->route( $this->route . '.index' );
//
//	}
//
//	public function update( Request $request, $id ) {
//		$Patern = Pecas::findOrFail( $id );
//		$Patern->update( $request->all() );
//		session()->forget( 'mensagem' );
//		session( [ 'mensagem' => $this->Page->msg_upd ] );
//
//		return redirect()->route( $this->route . '.index' );
//	}
//
//	public function destroy( $id ) {
//		$data = Pecas::find( $id );
//		$data->delete();
//
//		return response()->json( [
//			'status'   => '1',
//			'response' => $this->Page->msg_rem
//		] );
//	}


	//Tecnico
	public function getFormRequest( Request $request )
	{
		$Tecnico                       = $this->colaborador->tecnico;
		$this->Page->search_no_results = "Nenhuma Requisição encontrada!";

//		$max_can_request               = $Tecnico->getMaxCanRequest('parts');
//		$can_request                   = ( $Tecnico->waitingRequisicoes('parts')->count() < 1 );
		$this->Page->extras            = [
			'pecas'            => Peca::getAlltoSelectList(),
			'return'           => NULL,
			'max_can_request'  => 1,
			'can_request'      => 1,
			'requisicoes'      => $Tecnico->requisicoes('parts'),
			'type'             => $this->route,
		];

		return view( 'pages.recursos.requests.tecnico.index' )
			->with( 'Page', $this->Page );
	}

	public function postFormRequest( Request $request )
	{
		$mensagem = RequestPecas::openPartsRequest( [
			'idrequester' => $this->colaborador->idcolaborador,
			'parameters'  => $request->only( [ 'opcao', 'idpeca' ] ),
			'reason'      => $request->get( 'reason' ),
		] );
		session()->forget( 'mensagem' );
		session( [ 'mensagem' => $mensagem ] );
		return redirect()->route( 'parts.requisicao' );
	}


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


	//Admin/Gestor
	public function deniedRequest( Request $request )
	{
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

	public function postFormPassRequest( Request $request )
	{
		$data              = $request->only( [ 'id'] );
		$data['idmanager'] = $this->colaborador->idcolaborador;
		$mensagem          = RequestPecas::sendPartsRequest( $data );
		session()->forget( 'mensagem' );
		session( [ 'mensagem' => $mensagem ] );

		return redirect()->route( 'parts.requisicoes' );
	}



}

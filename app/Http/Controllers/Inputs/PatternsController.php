<?php

namespace App\Http\Controllers\Inputs;

use App\Colaborador;
use App\Models\Requests\Request as RequestPatterns;
use App\Http\Controllers\Controller;
use App\Models\Commons\Brand;
use App\Models\Inputs\Pattern;
use App\Models\Inputs\Stocks\PatternStock;
use App\Models\Inputs\Voids\Voidx;
use App\Unidade;
use App\Tecnico;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Zizaco\Entrust\EntrustFacade;

class PatternsController extends Controller {
	private $Page;
	private $colaborador;
	private $view_folder = "pages.recursos.models";
	private $route = "patterns";

	public function __construct() {
		$this->colaborador = Auth::user()->colaborador;
		$this->Page        = (object) [
			'link'              => $this->route,
			'view_folder'       => $this->view_folder,
			'Target'            => "Padrão",
			'Targets'           => "Padrões",
			'Titulo'            => "Padrões",
			'extras'            => [],
			'constraints'       => [],
			'search_results'    => "Padrões encontrados!",
			'search_no_results' => "Nenhum Padrão encontrado!",
			'msg_add'           => 'Padrão adicionado com sucesso!',
			'msg_upd'           => 'Padrão atualizado com sucesso!',
			'msg_rem'           => 'Padrão removida com sucesso!',
			'msg_stock_add'     => 'Estoque de Padrões adicionado com sucesso!',
			'titulo_primario'   => "",
			'titulo_secundario' => "",
		];
	}

	public function index() {
		$this->Page->extras['unities'] = Unidade::pluck( 'codigo', 'idunidade' );
		$this->Page->extras['brands']  = Brand::pluck( 'description', 'id' );
		$this->Page->extras['type']    = $this->route;
		$this->Page->titulo_primario   = "Listagem de Padrões";

		$Buscas = Pattern::with( 'brand', 'unity' )->get();

		return view( $this->Page->view_folder . '.index' )
			->with( 'Page', $this->Page )
			->with( 'Buscas', $Buscas );
	}

	public function show( $id ) {

		$Pattern                       = Pattern::findOrFail( $id );
		$this->Page->extras['unities'] = Unidade::pluck( 'codigo', 'idunidade' );
		$this->Page->extras['brands']  = Brand::pluck( 'description', 'id' );
		$this->Page->extras['type']    = $this->route;
		$this->Page->titulo_primario   = "Editar ";
		$this->Page->titulo_secundario = "Dados do " . $this->Page->Target;

		return view( $this->Page->view_folder . '.master' )
			->with( 'Page', $this->Page )
			->with( 'Data', $Pattern );
	}


	public function stocks() {
		$this->Page->extras['patterns']      = Pattern::getAlltoSelectList();
		$this->Page->extras['type_stock']    = 'patterns';
		$this->Page->extras['voids']         = Voidx::unuseds()->pluck( 'number', 'id' );
		$this->Page->extras['colaboradores'] = Colaborador::getAlltoSelectList();
		$this->Page->titulo_primario         = "Listagem de Padrões";
		$Buscas                              = PatternStock::all();

		return view( 'pages.recursos.stocks.index' )
			->with( 'Page', $this->Page )
			->with( 'Buscas', $Buscas );
	}

	public function stocksStore( Request $request ) {
		PatternStock::createWithVoid( $request->all() );
		session()->forget( 'mensagem' );
		session( [ 'mensagem' => $this->Page->msg_stock_add ] );

		return redirect()->route( $this->route . '.stocks' );
	}

	public function store( Request $request ) {
		Pattern::create( $request->all() );
		session()->forget( 'mensagem' );
		session( [ 'mensagem' => $this->Page->msg_add ] );

		return redirect()->route( $this->route . '.index' );

	}

	public function update( Request $request, $id ) {
		$Patern = Pattern::findOrFail( $id );
		$Patern->update( $request->all() );
		session()->forget( 'mensagem' );
		session( [ 'mensagem' => $this->Page->msg_upd ] );

		return redirect()->route( $this->route . '.index' );
	}

	public function destroy( $id ) {
		$data = Pattern::find( $id );
		$data->delete();

		return response()->json( [
			'status'   => '1',
			'response' => $this->Page->msg_rem
		] );
	}


	//Tecnico
	public function getFormRequest( Request $request ) {
		$Tecnico                       = $this->colaborador->tecnico;
		$this->Page->search_no_results = "Nenhuma Requisição encontrada!";
		$max_can_request               = $Tecnico->getMaxCanRequest('patterns');
		$can_request                   = ( $Tecnico->waitingRequisicoes('patterns')->count() < 1 );
		$this->Page->extras            = [
			'return'            => $Tecnico->patterns(),
			'max_can_request'  => $max_can_request,
			'can_request'      => ( ( $max_can_request > 0 ) && ( $can_request ) ),
			'requisicoes'      => $Tecnico->requisicoes('patterns'),
			'type'             => $this->route,
		];

		return view( 'pages.recursos.requests.tecnico.index' )
			->with( 'Page', $this->Page );
	}
	public function postFormRequest( Request $request ) {
		$mensagem = RequestPatterns::openPatternsRequest( [
			'idrequester' => $this->colaborador->idcolaborador,
			'parameters'  => $request->only( [ 'opcao', 'quantidade' ] ),
			'reason'      => $request->get( 'reason' ),
		] );
		session()->forget( 'mensagem' );
		session( [ 'mensagem' => $mensagem ] );
		return redirect()->route( 'patterns.requisicao' );
	}




	public function listRequests( Request $request ) {
		$this->Page->search_no_results  = "Nenhuma Requisição encontrada!";
		$this->Page->extras = [
			'requests'  => RequestPatterns::patterns()->get(),
			'tecnicos'  => Tecnico::all(),
			'type'      => $this->route,
		];
		return view( 'pages.recursos.requests.admin.index' )
			->with( 'Page', $this->Page );
	}


	//Admin/Gestor
	public function deniedRequest( Request $request ) {
		$data              = $request->only( 'id', 'response' );
		$data['idmanager'] = $this->colaborador->idcolaborador;
		$mensagem          = RequestPatterns::deny( $data );
		session()->forget( 'mensagem' );
		session( [ 'mensagem' => $mensagem ] );
		return redirect()->route( 'tools.requisicoes' );
	}

//
//	public function getReports( Request $request ) {
//		$Buscas                         = null;
//		$this->Page->search_no_results  = "Nenhuma Requisição encontrada!";
//		$this->Page->extras['tecnicos'] = Tecnico::all();
//
//		return view( 'pages.recursos.patterns.admin.reports' )
//			->with( 'Page', $this->Page )
//			->with( 'Buscas', $Buscas );
//	}

	public function postFormPassRequest( Request $request ) {
		RETURN $request->all();
		$data              = $request->only( [ 'id', 'valores' ] );
		$data['idmanager'] = $this->colaborador->idcolaborador;
		$mensagem          = RequestPatterns::sendSeloLacreRequest( $data );
		session()->forget( 'mensagem' );
		session( [ 'mensagem' => $mensagem ] );

		return redirect()->route( 'patterns.requisicoes' );
	}



}

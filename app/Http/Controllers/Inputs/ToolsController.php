<?php

namespace App\Http\Controllers\Inputs;

use App\Colaborador;
use App\Models\Requests\Request as RequestTools;
use App\Http\Controllers\Controller;
use App\Models\Commons\Brand;
use App\Models\Inputs\Stocks\ToolStock;
use App\Models\Inputs\Tool;
use App\Models\Inputs\Tool\ToolCategory as Category;
use App\Models\Inputs\Voids\Voidx;
use App\Tecnico;
use App\Unidade;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Zizaco\Entrust\EntrustFacade;

class ToolsController extends Controller
{
	private $Page;
	private $colaborador;
	private $view_folder = "pages.recursos.models";
	private $route = "tools";

	public function __construct() {
		$this->colaborador = Auth::user()->colaborador;
		$this->Page        = (object)[
			'link'              => $this->route,
			'view_folder'       => $this->view_folder,
			'Target'            => "Ferramenta",
			'Targets'           => "Ferramentas",
			'Titulo'            => "Ferramentas",
			'extras'            => [],
			'constraints'       => [],
			'search_results'    => "Ferramentas encontradas!",
			'search_no_results' => "Nenhuma Ferramenta encontrada!",
			'msg_add'           => 'Ferramenta adicionada com sucesso!',
			'msg_upd'           => 'Ferramenta atualizada com sucesso!',
			'msg_rem'           => 'Ferramenta removida com sucesso!',
			'msg_stock'         => 'Estoque de Ferramenta adicionado com sucesso!',
			'titulo_primario'   => "",
			'titulo_secundario' => "",
		];
	}

	public function index() {
		$this->Page->extras['categories'] = Category::pluck( 'description', 'id' );
		$this->Page->extras['unities']    = Unidade::pluck( 'codigo', 'idunidade' );
		$this->Page->extras['brands']     = Brand::pluck( 'description', 'id' );

		$this->Page->view_folder     = "pages.recursos.models";
		$this->Page->extras['type']  = 'tools';
		$this->Page->titulo_primario = "Listagem de Ferramentas";

		$Buscas = Tool::with( 'unity', 'brand', 'category' )->get();

		return view( $this->Page->view_folder . '.index' )
			->with( 'Page', $this->Page )
			->with( 'Buscas', $Buscas );
	}

	public function show($id) {
		$Pattern                          = Tool::findOrFail($id);
		$this->Page->extras['categories'] = Category::pluck('description', 'id');
		$this->Page->extras['unities']    = Unidade::pluck('codigo', 'idunidade');
		$this->Page->extras['brands']     = Brand::pluck('description', 'id');
		$this->Page->extras['type']       = 'tools';
		$this->Page->titulo_primario      = "Editar ";
		$this->Page->titulo_secundario    = "Dados do " . $this->Page->Target;

		return view( $this->Page->view_folder . '.master' )
			->with('Page', $this->Page)
			->with('Data', $Pattern);
	}

	public function store(Request $request) {
		Tool::create($request->all());
		session()->forget('mensagem');
		session([ 'mensagem' => $this->Page->msg_add]);

		return redirect()->route( $this->route . '.index');

	}

	public function update(Request $request, $id) {
		$Data = Tool::findOrFail($id);
		$Data->update($request->all());
		session()->forget('mensagem');
		session([ 'mensagem' => $this->Page->msg_upd]);

		return redirect()->route( $this->route . '.index');

	}

	public function destroy($id) {
		$data = Tool::findOrFail($id);
		$data->delete();
		return response()->json([ 'status'   => '1',
		                          'response' => $this->Page->msg_rem]);
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
		$Tecnico                       = $this->colaborador->tecnico;
		$this->Page->search_no_results = "Nenhuma Requisição encontrada!";
		$max_can_request               = $Tecnico->getMaxCanRequest('tools');
		$can_request                   = ( $Tecnico->waitingRequisicoes('tools')->count() < 1 );
		$this->Page->extras            = [
			'return'            => $Tecnico->tools(),
			'max_can_request'  => $max_can_request,
			'can_request'      => ( ( $max_can_request > 0 ) && ( $can_request ) ),
			'requisicoes'      => $Tecnico->requisicoes('tools'),
			'type'             => $this->route,
		];

		return view( 'pages.recursos.requests.tecnico.index' )
			->with( 'Page', $this->Page );
	}
	public function postFormRequest( Request $request ) {
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


}

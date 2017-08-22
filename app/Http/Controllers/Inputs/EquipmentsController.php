<?php

namespace App\Http\Controllers\Inputs;

use App\Helpers\ImageHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Inputs\EquipmentRequest;
use App\Marca;
use App\Models\Inputs\Equipment;
use App\Models\Instrumentos\InstrumentoMarca;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EquipmentsController extends Controller {
	private $Page;
	private $colaborador;
	private $view_folder = "pages.recursos.equipments";
	private $route = "equipments";

	public function __construct() {
		$this->colaborador = Auth::user()->colaborador;
		$this->Page        = (object) [
			'link'              => $this->route,
			'view_folder'       => $this->view_folder,
			'Target'            => "Equipamento",
			'Targets'           => "Equipamentos",
			'Titulo'            => "Equipamentos",
			'extras'            => [],
			'constraints'       => [],
			'search_no_results' => "Nenhum Equipamento encontrado!",
			'msg_add'           => 'Equipamento adicionado com sucesso!',
			'msg_upd'           => 'Equipamento atualizado com sucesso!',
			'msg_rem'           => 'Equipamento removido com sucesso!',
			'titulo_primario'   => "",
			'titulo_secundario' => "",
		];
	}

	public function index() {
		$this->Page->extras['equipments'] = Equipment::with( 'base' )->get();
		$this->Page->titulo_primario      = "Listagem de " . $this->Page->Targets;

		return view( $this->view_folder . '.admin.index' )
			->with( 'Page', $this->Page );
	}

	public function create() {
		$this->Page->extras['brands']  = Marca::getAlltoSelectList();
		$this->Page->titulo_primario   = "Cadastrar ";
		$this->Page->titulo_secundario = "Dados do " . $this->Page->Target;

		return view( $this->view_folder . '.admin.master' )
			->with( 'Page', $this->Page );
	}

	public function show( $id ) {
		$Data                          = Equipment::findOrFail( $id );
		$this->Page->extras['brands']  = Marca::getAlltoSelectList();
		$this->Page->titulo_primario   = "Editar ";
		$this->Page->titulo_secundario = "Dados do " . $this->Page->Target;

		return view( $this->view_folder . '.admin.master' )
			->with( 'Page', $this->Page )
			->with( 'Data', $Data );
	}

	public function store( EquipmentRequest $request ) {
		$data = $request->all();
		if ( $request->hasfile( 'photo' ) ) {
			$ImageHelper   = new ImageHelper();
			$data['photo'] = $ImageHelper->store( $request->file( 'photo' ), $this->Page->link );
		} else {
			$data['photo'] = null;
		}
		Equipment::create( $data );
		session()->forget( 'mensagem' );
		session( [ 'mensagem' => $this->Page->msg_add ] );

		return redirect()->route( $this->route . '.index' );
	}

	public function update( EquipmentRequest $request, $id ) {
		$Data = Equipment::findOrFail( $id );
		$data = $request->all();
		if ( $request->hasfile( 'photo' ) ) {
			$ImageHelper   = new ImageHelper();
			$data['photo'] = $ImageHelper->update( $request->file( 'photo' ), $this->Page->link, $Data->photo );
		}
		$Data->update( $data );
		session()->forget( 'mensagem' );
		session( [ 'mensagem' => $this->Page->msg_upd ] );

		return redirect()->route( $this->route . '.index' );

	}

	public function destroy( $id ) {
		$data = Equipment::findOrFail( $id );
		$data->delete();

		return response()->json( [
			'status'   => '1',
			'response' => $this->Page->msg_rem
		] );
	}


	public function listRequests( Request $request ) {
		return redirect()->route( 'patterns.index' );

		$this->Page->extras['requests'] = RequestSeloLacre::seloLacres()->get();
		$this->Page->search_no_results  = "Nenhuma Requisição encontrada!";
		$this->Page->extras['tecnicos'] = Tecnico::all();

		return view( 'pages.recursos.selolacres.admin.requests' )
			->with( 'Page', $this->Page );
	}

	public function getReports( Request $request ) {
		$Buscas                         = null;
		$this->Page->search_no_results  = "Nenhuma Requisição encontrada!";
		$this->Page->extras['tecnicos'] = Tecnico::all();

		return view( 'pages.recursos.selolacres.admin.reports' )
			->with( 'Page', $this->Page )
			->with( 'Buscas', $Buscas );
	}

	//Admin/Gestor

	public function deniedRequest( Request $request ) {
		$data              = $request->only( 'id', 'response' );
		$data['idmanager'] = $this->colaborador->idcolaborador;
		$mensagem          = RequestSeloLacre::deny( $data );
		session()->forget( 'mensagem' );
		session( [ 'mensagem' => $mensagem ] );

		return redirect()->route( 'selolacres.requisicoes' );
	}

	public function postFormPassRequest( Request $request ) {
		$data              = $request->only( [ 'id', 'valores' ] );
		$data['idmanager'] = $this->colaborador->idcolaborador;
		$mensagem          = RequestSeloLacre::sendSeloLacreRequest( $data );
		session()->forget( 'mensagem' );
		session( [ 'mensagem' => $mensagem ] );

		return redirect()->route( 'selolacres.requisicoes' );
	}

	//Tecnico

	public function getFormRequest( Request $request ) {
		$Tecnico                       = $this->colaborador->tecnico;
		$this->Page->search_no_results = "Nenhuma Requisição encontrada!";
		$max_selos_can_request         = $Tecnico->getMaxSelosCanRequest();
		$max_lacres_can_request        = $Tecnico->getMaxLacresCanRequest();
		$can_request                   = ( $Tecnico->waitingRequisicoesSeloLacre()->count() < 1 );
		$this->Page->extras            = [
			'selos'                  => $Tecnico->selos,
			'lacres'                 => $Tecnico->lacres,
			'max_selos_can_request'  => $max_selos_can_request,
			'max_lacres_can_request' => $max_lacres_can_request,
			'can_request_selos'      => ( ( $max_selos_can_request > 0 ) && ( $can_request ) ),
			'can_request_lacres'     => ( ( $max_lacres_can_request > 0 ) && ( $can_request ) ),
			'requisicoes'            => $Tecnico->requisicoesSeloLacre(),
		];

		return view( 'pages.recursos.selolacres.tecnico.requisition' )
			->with( 'Page', $this->Page );
	}

	public function postFormRequest( Request $request ) {
		$mensagem = RequestSeloLacre::openSeloLacreRequest( [
			'idrequester' => $this->colaborador->idcolaborador,
			'parameters'  => $request->only( [ 'opcao', 'quantidade' ] ),
			'reason'      => $request->get( 'reason' ),
		] );
		session()->forget( 'mensagem' );
		session( [ 'mensagem' => $mensagem ] );

		return redirect()->route( 'selolacres.requisicao' );
	}


}

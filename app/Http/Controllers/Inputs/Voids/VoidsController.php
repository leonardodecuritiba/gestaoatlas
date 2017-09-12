<?php

namespace App\Http\Controllers\Inputs\Voids;

use App\Colaborador;
use App\Http\Controllers\Controller;
use App\Models\Inputs\Voids\Voidx;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VoidsController extends Controller {
	private $Page;
	private $colaborador;
	private $view_folder = "pages.recursos.models";
	private $route = "voids";

	public function __construct() {
		$this->colaborador = Auth::user()->colaborador;
		$this->Page        = (object) [
			'view_folder'       => $this->view_folder,
			'link'              => $this->route,
			'Target'            => "Etiqueta Void",
			'Targets'           => "Etiquetas Void",
			'Titulo'            => "Etiquetas Void",
			'extras'            => [],
			'constraints'       => [],
			'search_no_results' => "Nenhuma Etiqueta Void encontrada!",
			'msg_add'           => 'Etiqueta Void adicionada com sucesso!',
			'msg_upd'           => 'Etiqueta Void atualizada com sucesso!',
			'msg_rem'           => 'Etiqueta Void removida com sucesso!',
			'titulo_primario'   => "",
			'titulo_secundario' => "",
		];
	}

	public function index() {
		$this->Page->extras['type']  = 'voids';
		$this->Page->titulo_primario = "Listagem de " . $this->Page->Targets;
		$Buscas                      = Voidx::all();

		return view( $this->view_folder . '.index' )
			->with( 'Page', $this->Page )
			->with( 'Buscas', $Buscas );
	}

	public function store( Request $request ) {
		$inputs  = range( $request->get( 'ni' ), $request->get( 'nf' ) );
		$already = Voidx::whereIn( 'number', $inputs )->pluck( 'number' )->toArray();
		$numbers = array_diff( $inputs, $already );
		Voidx::start( $numbers );
		session()->forget( 'mensagem' );
		session( [ 'mensagem' => $this->Page->msg_add . " Voids inseridos: " . implode( '; ', $numbers ) ] );

		return redirect()->route( $this->route . '.index' );
	}

	public function destroy( $id ) {
		$data = Voidx::findOrFail( $id );
		$data->delete();

		return response()->json( [
			'status'   => '1',
			'response' => $this->Page->msg_rem
		] );
	}

}

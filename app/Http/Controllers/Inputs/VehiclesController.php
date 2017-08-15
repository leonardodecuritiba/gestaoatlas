<?php

namespace App\Http\Controllers\Inputs;

use App\Helpers\DataHelper;
use App\Http\Controllers\Controller;
use App\Models\Inputs\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Zizaco\Entrust\EntrustFacade;

class VehiclesController extends Controller
{
    private $Page;
    private $colaborador;
    private $view_folder = "pages.recursos.vehicles";
    private $route = "vehicles";

    public function __construct()
    {
        $this->colaborador = Auth::user()->colaborador;
        $this->Page = (object)[
            'link' => $this->route,
            'folder' => $this->view_folder,
            'Target' => "Veículo",
            'Targets' => "Veículos",
            'Titulo' => "Veículos",
            'extras' => [],
            'constraints' => [],
            'search_no_results' => "Nenhum Veículo encontrado!",
            'msg_add' => 'Veículo adicionado com sucesso!',
            'msg_upd' => 'Veículo atualizado com sucesso!',
            'msg_rem' => 'Veículo removido com sucesso!',
            'titulo_primario' => "",
            'titulo_secundario' => "",
        ];
    }

    public function index()
    {
        $this->Page->extras['vehicles'] = Vehicle::all();
        $this->Page->titulo_primario = "Listagem de " . $this->Page->Targets;
        return view($this->view_folder . '.admin.index')
            ->with('Page', $this->Page);
    }

    public function create()
    {
        $this->Page->extras['tipos'] = Vehicle::$_API_OPTIONS_;
//        return $this->Page->extras['tipos'];
        $this->Page->titulo_primario = "Cadastrar ";
        $this->Page->titulo_secundario = "Dados do " . $this->Page->Target;
        return view($this->view_folder . '.admin.master')
            ->with('Page', $this->Page);
    }

    public function store(Request $request)
    {
        Vehicle::create($request->all());
        session()->forget('mensagem');
        session(['mensagem' => $this->Page->msg_add]);
        return redirect()->route($this->route . '.index');

    }

    public function show($id)
    {
        $Vehicle = Vehicle::findOrFail($id);
        $this->Page->extras['tipos'] = Vehicle::$_API_OPTIONS_;
        $this->Page->titulo_primario = "Editar ";
        $this->Page->titulo_secundario = "Dados do " . $this->Page->Target;
        return view($this->view_folder . '.admin.master')
            ->with('Page', $this->Page)
            ->with('Data', $Vehicle);
    }

    public function update(Request $request, $id)
    {
        $Vehicle = Vehicle::findOrFail($id);
        $Vehicle->update($request->all());
        session()->forget('mensagem');
        session(['mensagem' => $this->Page->msg_upd]);
        return redirect()->route($this->route . '.index');

    }

    public function destroy($id)
    {
        $data = Vehicle::findOrFail($id);
        $data->delete();
        return response()->json(['status' => '1',
            'response' => $this->Page->msg_rem]);
    }


    public function listRequests(Request $request)
    {
        return redirect()->route('patterns.index');

        $this->Page->extras['requests'] = RequestSeloLacre::seloLacres()->get();
        $this->Page->search_no_results = "Nenhuma Requisição encontrada!";
        $this->Page->extras['tecnicos'] = Tecnico::all();
        return view('pages.recursos.selolacres.admin.requests')
            ->with('Page', $this->Page);
    }

    public function getReports(Request $request)
    {
        $Buscas = NULL;
        $this->Page->search_no_results = "Nenhuma Requisição encontrada!";
        $this->Page->extras['tecnicos'] = Tecnico::all();
        return view('pages.recursos.selolacres.admin.reports')
            ->with('Page', $this->Page)
            ->with('Buscas', $Buscas);
    }

    //Admin/Gestor

    public function deniedRequest(Request $request)
    {
        $data = $request->only('id', 'response');
        $data['idmanager'] = $this->colaborador->idcolaborador;
        $mensagem = RequestSeloLacre::deny($data);
        session()->forget('mensagem');
        session(['mensagem' => $mensagem]);
        return redirect()->route('selolacres.requisicoes');
    }

    public function postFormPassRequest(Request $request)
    {
        $data = $request->only(['id', 'valores']);
        $data['idmanager'] = $this->colaborador->idcolaborador;
        $mensagem = RequestSeloLacre::sendSeloLacreRequest($data);
        session()->forget('mensagem');
        session(['mensagem' => $mensagem]);
        return redirect()->route('selolacres.requisicoes');
    }

    //Tecnico

    public function getFormRequest(Request $request)
    {
        $Tecnico = $this->colaborador->tecnico;
        $this->Page->search_no_results = "Nenhuma Requisição encontrada!";
        $max_selos_can_request = $Tecnico->getMaxSelosCanRequest();
        $max_lacres_can_request = $Tecnico->getMaxLacresCanRequest();
        $can_request = ($Tecnico->waitingRequisicoesSeloLacre()->count() < 1);
        $this->Page->extras = [
            'selos' => $Tecnico->selos,
            'lacres' => $Tecnico->lacres,
            'max_selos_can_request' => $max_selos_can_request,
            'max_lacres_can_request' => $max_lacres_can_request,
            'can_request_selos' => (($max_selos_can_request > 0) && ($can_request)),
            'can_request_lacres' => (($max_lacres_can_request > 0) && ($can_request)),
            'requisicoes' => $Tecnico->requisicoesSeloLacre(),
        ];
        return view('pages.recursos.selolacres.tecnico.requisition')
            ->with('Page', $this->Page);
    }

    public function postFormRequest(Request $request)
    {
        $mensagem = RequestSeloLacre::openSeloLacreRequest([
            'idrequester' => $this->colaborador->idcolaborador,
            'parameters' => $request->only(['opcao', 'quantidade']),
            'reason' => $request->get('reason'),
        ]);
        session()->forget('mensagem');
        session(['mensagem' => $mensagem]);
        return redirect()->route('selolacres.requisicao');
    }


}

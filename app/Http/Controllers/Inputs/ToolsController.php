<?php

namespace App\Http\Controllers\Inputs;

use App\Http\Controllers\Controller;
use App\Models\Commons\Brand;
use App\Models\Inputs\Tool;
use App\Models\Inputs\Tool\ToolCategory as Category;
use App\Unidade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Zizaco\Entrust\EntrustFacade;

class ToolsController extends Controller
{
    private $Page;
    private $colaborador;
    private $view_folder = "pages.recursos.tools";
    private $route = "tools";

    public function __construct()
    {
        $this->colaborador = Auth::user()->colaborador;
        $this->Page = (object)[
            'link' => $this->route,
            'Target' => "Ferramenta",
            'Targets' => "Ferramentas",
            'Titulo' => "Ferramentas",
            'extras' => [],
            'constraints' => [],
            'search_no_results' => "Nenhuma Ferramenta encontrada!",
            'msg_add' => 'Ferramenta adicionada com sucesso!',
            'msg_upd' => 'Ferramenta atualizada com sucesso!',
            'msg_rem' => 'Ferramenta removida com sucesso!',
            'titulo_primario' => "",
            'titulo_secundario' => "",
        ];
    }

    public function index()
    {
        $this->Page->extras['tools'] = Tool::with('unity', 'brand', 'category')->get();
        $this->Page->titulo_primario = "Listagem de Padrões";
        return view($this->view_folder . '.admin.index')
            ->with('Page', $this->Page);
    }

    public function create()
    {
        $this->Page->extras['categories'] = Category::pluck('description', 'id');
        $this->Page->extras['unities'] = Unidade::pluck('codigo', 'idunidade');
        $this->Page->extras['brands'] = Brand::pluck('description', 'id');
        $this->Page->titulo_primario = "Cadastrar ";
        $this->Page->titulo_secundario = "Dados do " . $this->Page->Target;
        return view($this->view_folder . '.admin.master')
            ->with('Page', $this->Page);
    }

    public function show($id)
    {
        $Pattern = Tool::findOrFail($id);
        $this->Page->extras['categories'] = Category::pluck('description', 'id');
        $this->Page->extras['unities'] = Unidade::pluck('codigo', 'idunidade');
        $this->Page->extras['brands'] = Brand::pluck('description', 'id');
        $this->Page->titulo_primario = "Editar ";
        $this->Page->titulo_secundario = "Dados do " . $this->Page->Target;
        return view($this->view_folder . '.admin.master')
            ->with('Page', $this->Page)
            ->with('Data', $Pattern);
    }

    public function store(Request $request)
    {
        Tool::create($request->all());
        session()->forget('mensagem');
        session(['mensagem' => $this->Page->msg_add]);
        return redirect()->route($this->route . '.index');

    }

    public function update(Request $request, $id)
    {
        $Data = Tool::findOrFail($id);
        $Data->update($request->all());
        session()->forget('mensagem');
        session(['mensagem' => $this->Page->msg_upd]);
        return redirect()->route($this->route . '.index');

    }

    public function destroy($id)
    {
        $data = Tool::findOrFail($id);
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

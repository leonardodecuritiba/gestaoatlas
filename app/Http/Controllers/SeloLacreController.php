<?php

namespace App\Http\Controllers;

use \App\Models\Requests\Request as RequestSeloLacre;
use App\Http\Requests\Recursos\SelosRequest;
use App\Http\Requests\Recursos\LacresRequest;
use App\Lacre;
use App\Selo;
use App\Tecnico;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Zizaco\Entrust\EntrustFacade;

class SeloLacreController extends Controller
{
    private $Page;
    private $colaborador;

    public function __construct()
    {
        $this->colaborador = Auth::user()->colaborador;
        $this->Page = (object)[
            'link' => "selolacres",
            'Target' => "Selos ou Lacres",
            'Targets' => "Selos ou Lacres",
            'Titulo' => "Selos ou Lacres",
            'extras' => [],
            'constraints' => [],
            'search_no_results' => "Nenhum Selo ou Lacre encontrado!",
            'msg_add' => 'Selo ou Lacre adicionado com sucesso!',
            'msg_upd' => 'Selo ou Lacre atualizado com sucesso!',
            'msg_rem' => 'Selo ou Lacre removida com sucesso!',
            'titulo_primario' => "",
            'titulo_secundario' => "",
        ];
    }


    public function create()
    {
        if (EntrustFacade::hasRole('admin')) {
            $this->Page->extras['tecnicos'] = Tecnico::all();
        }
        $this->Page->titulo_primario = "Cadastrar ";
        $this->Page->titulo_secundario = "Dados do " . $this->Page->Target;
        return view('pages.recursos.selolacres.master')
            ->with('Page', $this->Page);
    }

    public function lancarSelos(SelosRequest $request)
    {
        $ini = $request->get('numeracao_inicial');
        $end = $request->get('numeracao_final');
        $idtecnico = $request->get('idtecnico');
        $qtd = 0;
        $Selos = [];
        for ($i = $ini; $i <= $end; $i++) {
            if (!Selo::where('numeracao', $i)->exists()) {
                $qtd++;
                $Selos[] = Selo::create([
                    'idtecnico' => $idtecnico,
                    'numeracao' => $i
                ]);
            }
        }
        if ($qtd == 0) {
            return Redirect::back()
                ->withErrors(['Já existem Selos com essa numeração'])
                ->withInput($request->all());
        } else {
            session()->forget('mensagem');
            session(['mensagem' => 'Foram adicionados ' . $qtd . ' Selos!']);
            return redirect()->route('selolacres.create')->with(['Selos' => $Selos]);
        }
    }

    public function lancarLacres(LacresRequest $request)
    {
        $ini = $request->get('numeracao_inicial');
        $end = $request->get('numeracao_final');
        $idtecnico = $request->get('idtecnico');
        $qtd = 0;
        $Lacres = [];
        for ($i = $ini; $i <= $end; $i++) {
            if (!Lacre::where('numeracao', $i)->exists()) {
                $qtd++;
                $Lacres[] = Lacre::create([
                    'idtecnico' => $idtecnico,
                    'numeracao' => $i
                ]);
            }
        }
        if ($qtd == 0) {
            return Redirect::back()
                ->withErrors(['Já existem Lacres com essa numeração'])
                ->withInput($request->all());
        } else {
            session()->forget('mensagem');
            session(['mensagem' => 'Foram adicionados ' . $qtd . ' Lacres!']);
            return redirect()->route('selolacres.create')->with(['Lacres' => $Lacres]);
        }
    }

    public function listRequests(Request $request)
    {
//        if (isset($request['busca'])) {
//            $busca = $request['busca'];
//            $Buscas = Cfop::where('numeracao', 'like', '%' . $busca . '%')
//                ->paginate(10);
//        } else {
//            $Buscas = Cfop::paginate(10);
//        }
        $Buscas = RequestSeloLacre::selos()->get();
        $this->Page->search_no_results = "Nenhuma Requisição encontrada!";
        $this->Page->extras['tecnicos'] = Tecnico::all();
        return view('pages.recursos.selolacres.admin.requests')
            ->with('Page', $this->Page)
            ->with('Buscas', $Buscas);
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

    public function deniedRequest($id)
    {
        $data['id'] = $id;
        $data['idmanager'] = $this->colaborador->idcolaborador;
        $mensagem = RequestSeloLacre::deny($data);
        session()->forget('mensagem');
        session(['mensagem' => $mensagem]);
        return redirect()->route('selolacres.requisicoes');
    }

    public function getFormRequest(Request $request)
    {
        $Tecnico = $this->colaborador->tecnico;
        $this->Page->search_no_results = "Nenhuma Requisição encontrada!";
        $this->Page->extras = [
            'selos' => $Tecnico->selos,
            'lacres' => $Tecnico->lacres,
            'requisicoes' => $Tecnico->requisicoesSeloLacre(),
        ];
        return view('pages.recursos.selolacres.tecnico.requisition')
            ->with('Page', $this->Page);
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

    public function postFormRequest(Request $request)
    {
        $data['idrequester'] = $this->colaborador->idcolaborador;
        $data['parameters'] = $request->only(['opcao', 'quantidade']);
        $mensagem = RequestSeloLacre::openSeloLacreRequest($data);
        session()->forget('mensagem');
        session(['mensagem' => $mensagem]);
        return redirect()->route('selolacres.requisicao');
    }


}

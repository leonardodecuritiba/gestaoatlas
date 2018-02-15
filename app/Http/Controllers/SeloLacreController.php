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
use Illuminate\Support\Facades\Redirect;
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

    public function index(Request $request)
    {
	    $this->Page->extras['selos'] = NULL;
	    $this->Page->extras['lacres'] = NULL;
    	if($request->has('tipo')){
	        if($request->get('tipo')){
	        	$lacres = Lacre::getAllListagem($request->all());
			    $this->Page->extras['lacres'] = $lacres->paginate(10)->map(function($s){
				    $x_instrumento = $s->lacre_instrumento;

				    if($x_instrumento!=NULL){
					    $instrumento = $x_instrumento->instrumento;
					    $cliente = $instrumento->cliente->getType();

					    $s->idos_set            = ($x_instrumento->idaparelho_set != NULL) ? $x_instrumento->aparelho_set->idordem_servico : NULL;
					    $s->idos_unset          = ($x_instrumento->idaparelho_unset != NULL) ? $x_instrumento->aparelho_unset->idordem_servico : NULL;
					    $s->n_serie             = $instrumento->numero_serie;
					    $s->n_inventario        = $instrumento->inventario;
					    $s->cliente_documento   = $cliente->documento;
				    }

				    $s->nome_tecnico    = $s->getNomeTecnico();
				    $s->numero_formatado= $s->numeracao;
				    $s->status_color    = $s->getStatusColor();
				    $s->status_text     = $s->getStatusText();
				    return $s;
			    });
		        $this->Page->titulo_primario = "Listagem de Lacres";
		        $this->Page->search_no_results =  "Nenhum Lacre encontrado!";
		    } else {
	        	$selos = Selo::getAllListagem($request->all());

		        $this->Page->extras['selos'] = $selos->get()->map(function($s){
			        $x_instrumento = $s->selo_instrumento;
			        $se = new \stdClass();

			        if($x_instrumento!=NULL){
				        $instrumento = $x_instrumento->instrumento;
				        $cliente = $instrumento->cliente->getType();

				        $se->cliente_documento   = $cliente->documento;
				        $se->idos_set            = ($x_instrumento->idaparelho_set != NULL) ? $x_instrumento->aparelho_set->idordem_servico : NULL;
				        $se->idos_unset          = ($x_instrumento->idaparelho_unset != NULL) ? $x_instrumento->aparelho_unset->idordem_servico : NULL;
				        $se->n_serie             = $instrumento->numero_serie;
				        $se->n_inventario        = $instrumento->inventario;
			        } else {
				        $se->cliente_documento   = NULL;
				        $se->idos_set            = NULL;
				        $se->idos_unset          = NULL;
				        $se->n_serie             = NULL;
				        $se->n_inventario        = NULL;
			        }

			        $se->idselo                  = $s->idselo;
			        $se->created_at              = $s->getCreatedAtTime();
			        $se->created_at_formatted    = $s->getCreatedAtFormatted();
			        $se->nome_tecnico            = $s->getNomeTecnico();
			        $se->numero_formatado        = $s->getFormatedSeloDV();
			        $se->numeracao_externa       = $s->numeracao_externa;
			        $se->status_color            = $s->getStatusColor();
			        $se->status_text             = $s->getStatusText();
			        return $se;
		        });

//		        return $this->Page->extras['selos'];

		        $this->Page->titulo_primario = "Listagem de Selos";
		        $this->Page->search_no_results =  "Nenhum Selo encontrado!";
	        }
	    }
	    $this->Page->extras['tecnicos'] = Tecnico::getAlltoSelectList();
	    $this->Page->extras['tecnicos']->prepend("Todos");
	    $this->Page->extras['status'] = [0=>'Disponíveis',1=>'Usados',2=>'Todos'];
        return view('pages.recursos.selolacres.admin.index')
            ->with('Page', $this->Page);
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
                ->withErrors(['Já existem Selos com essa numeração. Ou nenhum Selo foi lançado!'])
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

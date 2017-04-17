<?php

namespace App\Http\Controllers;

use App\Cliente;
use App\FormaPagamento;
use App\Models\Fechamento;
use App\Models\Nfe;
use App\OrdemServico;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class FechamentoController extends Controller
{
    private $Page;
    private $colaborador;
    private $tecnico;

    public function __construct()
    {
        $this->Page = (object)[
            'table' => "fechamentos",
            'link' => "fechamentos",
            'primaryKey' => "id",
            'Target' => "Fechamentos",
            'Search' => "Buscar por CPF, CNPJ, Nome Fantasia ou Razão Social...",
            'Targets' => "Fechamentos",
            'Titulo' => "Fechamentos",
            'search_no_results' => "Nenhumo Fechamento encontrado!",
            'msg_abr' => 'Fechamento aberto com sucesso!',
            'msg_upd' => 'Fechamento atualizado com sucesso!',
            'msg_rem' => 'Fechamento removido com sucesso!',
            'msg_rea' => 'Fechamento reaberto com sucesso!',
            'titulo_primario' => "",
            'titulo_secundario' => "",
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $status)
    {
        $Buscas = Fechamento::filter_status($status)->get();
        return view('pages.' . $this->Page->link . '.index')
            ->with('Page', $this->Page)
            ->with('Buscas', $Buscas);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function run_temp()
    {
        Carbon::setTestNow('2017-02-01 00:00:00');                        // set the mock (of course this could be a real mock object)
        $this->run();
        Carbon::setTestNow('2017-03-01 00:00:00');                        // set the mock (of course this could be a real mock object)
        $this->run();
    }


    public function run()
    {
        $DATA_INICIO = Carbon::parse('first day of last month')->format('Y-m-d 00:00:00');//'2017-01-01 00:00:00' (1º dia do mês anterior)
        $DATA_FIM = Carbon::parse('last day of last month')->format('Y-m-d 23:59:59');// '2017-01-31 23:59:59' (1º dia do mês vigente)
        $ordem_servicos = OrdemServico::whereBetween('fechamento', [$DATA_INICIO, $DATA_FIM])
            ->whereNull('idfechamento')
            ->orderBy('idcentro_custo', 'desc')
            ->get();
//            ->get(['idordem_servico','idcentro_custo','idcliente']);

        $fechamento_cc = []; //fechamento centro de custos
        $fechamento_cl = []; //fechamento clientes
        foreach ($ordem_servicos as $ordem_servico) {
            if ($ordem_servico->idcentro_custo != NULL) {
                $idcentro_custo = $ordem_servico->idcentro_custo;
                $fechamento_cc[$idcentro_custo][] = $ordem_servico;
            } else {
                $idcliente = $ordem_servico->idcliente;
                $fechamento_cl[$idcliente][] = $ordem_servico;
            }
        }

        //fechamentos CLIENTES
        foreach ($fechamento_cl as $ordem_servicos) {
            Fechamento::geraFechamento($ordem_servicos, 0);
        }

        //fechamentos CENTRO DE CUSTO
        foreach ($fechamento_cc as $ordem_servicos) {
            Fechamento::geraFechamento($ordem_servicos, 1);
        }

        return Fechamento::lastCreated()->first();
    }

    public function runByID($id = NULL)
    {
        if ($id == NULL) return $id;
        $ordem_servico = OrdemServico::find($id);
        $centro_custo = ($ordem_servico->idcentro_custo != NULL);
        Fechamento::geraFechamento($ordem_servico, $centro_custo);
        session()->forget('mensagem');
        session(['mensagem' => $this->Page->msg_abr]);
        return Redirect::route('fechamentos.index', 'todas');

    }

    public function remover($id)
    {
        Fechamento::remover($id);
        session()->forget('mensagem');
        session(['mensagem' => $this->Page->msg_rea]);
        return Redirect::route('fechamentos.index', 'todas');
    }

    // ============= NFSe ==================
    public function getNFSeTeste($id)
    {
        $Fechamento = Fechamento::find($id);
        $responseNF = $Fechamento->setNFSe($debug = true);
        session()->forget('responseNF');
        session(['responseNF' => $responseNF]);
        return Redirect::route('fechamentos.show', $id);
    }

    public function getNFSe($id)
    {
        $Fechamento = Fechamento::find($id);
        $responseNF = $Fechamento->setNFSe($debug = false);
        session()->forget('responseNF');
        session(['responseNF' => $responseNF]);
        return Redirect::route('fechamentos.show', $id);
    }

    public function consultaNFSe($id, $debug = true)
    {
        $Fechamento = Fechamento::find($id);
        return $Fechamento->getDataNFSe($debug);
    }


    // ============= NFe ==================
    public function getNfeTeste($id)
    {
        $Fechamento = Fechamento::find($id);
        $responseNF = $Fechamento->setNfe($debug = true);
        session()->forget('responseNF');
        session(['responseNF' => $responseNF]);
        return Redirect::route('fechamentos.show', $id);
    }

    public function getNfe($id)
    {
        $Fechamento = Fechamento::find($id);
        $responseNF = $Fechamento->setNfe($debug = false);
        session()->forget('responseNF');
        session(['responseNF' => $responseNF]);
        return Redirect::route('fechamentos.show', $id);
    }

    public function consultaNfe($id, $debug = true)
    {
        $Fechamento = Fechamento::find($id);
        return $Fechamento->getDataNfe($debug);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $Fechamento = Fechamento::find($id);
        return view('pages.' . $this->Page->link . '.show')
            ->with('Page', $this->Page)
            ->with('Fechamento', $Fechamento);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

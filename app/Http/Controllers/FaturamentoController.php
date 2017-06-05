<?php

namespace App\Http\Controllers;

use App\Cliente;
use App\FormaPagamento;
use App\Models\Faturamento;
use App\Models\Nfe;
use App\Models\PrazoPagamento;
use App\Models\StatusFechamento;
use App\OrdemServico;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class FaturamentoController extends Controller
{
    private $Page;
    private $colaborador;
    private $tecnico;

    public function __construct()
    {
        $this->Page = (object)[
            'table' => "fechamentos",
            'link' => "faturamentos",
            'primaryKey' => "id",
            'Target' => "Faturamentos",
            'Search' => "Buscar por CPF, CNPJ, Nome Fantasia ou Razão Social...",
            'Targets' => "Faturamentos",
            'Titulo' => "Faturamentos",
            'search_results' => "",
            'search_no_results' => "Nenhum Faturamento encontrado!",
            'msg_abr' => 'Faturamento aberto com sucesso!',
            'msg_upd' => 'Faturamento atualizado com sucesso!',
            'msg_rem' => 'Faturamento removido com sucesso!',
            'msg_rea' => 'Faturamento reaberto com sucesso!',
            'titulo_primario' => "",
            'titulo_secundario' => "",
        ];
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $now = Carbon::now();

        if ($request->has('idfaturamento')) {
            $Buscas = Faturamento::where('id', $request->get('idfaturamento'))->with('cliente')->get();
        } else {
            $Buscas = Faturamento::filter_layout($request->all())->get();
        }
        $this->Page->extras['status_fechamento'] = StatusFechamento::whereIn('id', $Buscas->pluck('idstatus_fechamento'))->get();
        $this->Page->extras['clientes'] = Cliente::whereIn('idcliente', $Buscas->pluck('idcliente'))->get();

        return view('pages.' . $this->Page->link . '.index')
            ->with('Page', $this->Page)
            ->with('Buscas', $Buscas);
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @param int $centro_custo
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function faturar(Request $request, $centro_custo, $id)
    {
        $request->merge(['situacao' => OrdemServico::_STATUS_FINALIZADA_]);
        $request->merge(['centro_custo' => $centro_custo]);
        $query = OrdemServico::filter_layout($request->all())
            ->whereNull('idfaturamento');

//        $query->update(['data_fechada', Carbon::now()]);

        if ($request->get('centro_custo')) {
            $query = $query->where('idcentro_custo', $id);
        } else {
            $query = $query->where('idcliente', $id);
        }

        return $query->get();;

        $Faturamento = Faturamento::geraFaturamento($OrdemServicos, $request->get('centro_custo'));

        session()->forget('mensagem');
        session(['mensagem' => $this->Page->msg_abr]);
        return Redirect::route('faturamentos.show', $Faturamento->id);
    }

    public function faturar_pos(Request $request, $centro_custo, $id)
    {
        $request->merge(['situacao' => OrdemServico::_STATUS_FATURAMENTO_PENDENTE_]);
        $request->merge(['centro_custo' => $centro_custo]);
        $query = OrdemServico::filter_layout($request->all())
            ->whereNull('idfaturamento');

        if ($request->get('centro_custo')) {
            $query = $query->where('idcentro_custo', $id);
            $OrdemServicos = $query->get();
        } else {
            $OrdemServicos = $query->where('idcliente', $id)->get();
        }

        $Faturamento = Faturamento::geraFaturamento($OrdemServicos, $request->get('centro_custo'));

        session()->forget('mensagem');
        session(['mensagem' => $this->Page->msg_abr]);
        return Redirect::route('faturamentos.show', $Faturamento->id);
    }

    public function faturarPeriodo(Request $request)
    {
        $DATA_INICIO = Carbon::createFromFormat('d/m/Y', $request->get('data_inicial'))->format('Y-m-d 23:59:59');//'2017-01-01 00:00:00' (1º dia do mês anterior)
        $DATA_FIM = Carbon::createFromFormat('d/m/Y', $request->get('data_final'))->format('Y-m-d 23:59:59');// '2017-01-31 23:59:59' (1º dia do mês vigente)

        $OrdemServicos = OrdemServico::whereBetween('data_finalizada', [$DATA_INICIO, $DATA_FIM])
            ->whereNull('idfaturamento')
            ->orderBy('idcentro_custo', 'desc')
            ->get();
//            ->get(['idordem_servico','idcentro_custo','idcliente']);
        Faturamento::faturaPeriodo($OrdemServicos);

        session()->forget('mensagem');
        session(['mensagem' => 'Faturamneto realizado']);
        return Redirect::route('faturamentos.periodo_index');
    }

    public function runByOrdemServicoID($id = NULL)
    {
        if ($id == NULL) return $id;
        $OrdemServicos = OrdemServico::find($id);
        $centro_custo = ($OrdemServicos->idcentro_custo != NULL);
        Faturamento::geraFaturamento($OrdemServicos, $centro_custo);
        session()->forget('mensagem');
        session(['mensagem' => $this->Page->msg_abr]);
        return Redirect::route($this->Page->link . '.index', 'todas');
    }

    public function run()
    {
        $DATA_INICIO = Carbon::parse('first day of last month')->format('Y-m-d 00:00:00');//'2017-01-01 00:00:00' (1º dia do mês anterior)
        $DATA_FIM = Carbon::parse('last day of last month')->format('Y-m-d 23:59:59');// '2017-01-31 23:59:59' (1º dia do mês vigente)
        $OrdemServicos = OrdemServico::whereBetween('data_finalizada', [$DATA_INICIO, $DATA_FIM])
            ->whereNull('idfaturamento')
            ->orderBy('idcentro_custo', 'desc')
            ->get();
//            ->get(['idordem_servico','idcentro_custo','idcliente']);
        return Faturamento::faturaPeriodo($OrdemServicos);
    }

    public function remover($id)
    {
        Faturamento::remover($id);
        session()->forget('mensagem');
        session(['mensagem' => $this->Page->msg_rea]);
        return Redirect::route($this->Page->link . '.index', 'todas');
    }

    // ============= NF ==================

    public function sendNF($id, $debug, $type)
    {
        $Faturamento = Faturamento::find($id);
        $responseNF = $Faturamento->sendNF($debug, $type);
        session()->forget('responseNF');
        session(['responseNF' => $responseNF]);
        return Redirect::route($this->Page->link . '.show', $id);
    }

    public function resendNF($id, $debug, $type)
    {
        $Faturamento = Faturamento::find($id);
        $responseNF = $Faturamento->resendNF($debug, $type);
        session()->forget('responseNF');
        session(['responseNF' => $responseNF]);
        return Redirect::route($this->Page->link . '.show', $id);
    }

    public function getNF($id, $debug, $type)
    {
        $Faturamento = Faturamento::find($id);
        return $Faturamento->getNF($debug, $type);
    }

    // ============= /NF ==================

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $Faturamento = Faturamento::find($id);
        return view('pages.' . $this->Page->link . '.show')
            ->with('Page', $this->Page)
            ->with('Faturamento', $Faturamento);
    }

}

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
            'search_results' => "",
            'search_no_results' => "Nenhum Fechamento encontrado!",
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
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $centro_custo)
    {
        $request->merge(['centro_custo' => $centro_custo]);
        $request->merge(['situacao' => OrdemServico::_STATUS_FINALIZADA_]);
        $query = OrdemServico::filter_layout($request->all())
            ->whereNull('idfaturamento')
            ->whereNull('data_fechada')
            ->select('*', DB::raw('count(*) as qtd_os'));

        if ($request->get('centro_custo')) {
            $Fechamentos = $query->groupBy('idcentro_custo')
                ->with('centro_custo')
                ->get();
            $Faturamentos = Faturamento::centroCustos()
                ->abertos()
                ->get();
            $this->Page->search_results = "Centro de Custos Não Fechados";
        } else {
            $Fechamentos = $query->groupBy('idcliente')
                ->with('cliente')
                ->get();
            $Faturamentos = Faturamento::clientes()
                ->abertos()
                ->get();
            $this->Page->search_results = "Clientes Não Fechados";
        }

        return view('pages.' . $this->Page->link . '.index')
            ->with('Page', $this->Page)
            ->with('Fechamentos', $Fechamentos)
            ->with('Faturamentos', $Faturamentos);
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @param int $centro_custo
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $centro_custo, $id)
    {
        $request->merge(['centro_custo' => $centro_custo]);
        $request->merge(['situacao' => OrdemServico::_STATUS_FINALIZADA_]);
        $query = OrdemServico::filter_layout($request->all())
            ->whereNull('data_fechada')
            ->whereNull('idfaturamento');

        if ($request->get('centro_custo')) {
            $query = $query->where('idcentro_custo', $id);
            $Buscas = $query->orderBy('idcliente')->get();
            $Valores = OrdemServico::getValoresFechamentoReal($Buscas);

//            $Fechamentos = $query->select('*', DB::raw('count(*) as qtd_os'))
//                ->get();
        } else {
            $Buscas = $query->where('idcliente', $id)->get();
            $Valores = OrdemServico::getValoresFechamentoReal($Buscas);
        }

        return view('pages.' . $this->Page->link . '.show')
            ->with('Page', $this->Page)
            ->with('Valores', $Valores)
            ->with('Buscas', $Buscas);
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index_pos_fechamento(Request $request)
    {
        $request->merge(['situacao' => OrdemServico::_STATUS_FATURAMENTO_PENDENTE_]);
        $query = OrdemServico::filter_layout($request->all())
            ->whereNull('idfaturamento')
            ->select('*', DB::raw('count(*) as qtd_os'));

        if ($request->get('centro_custo')) {
            $Fechamentos = $query->groupBy('idcentro_custo')
                ->with('centro_custo')
                ->get();
            $Faturamentos = Faturamento::centroCustos()
                ->abertos()
                ->get();
            $this->Page->search_results = "Centro de Custos Fechados";
        } else {
            $Fechamentos = $query->groupBy('idcliente')
                ->with('cliente')
                ->get();
            $Faturamentos = Faturamento::clientes()
                ->abertos()
                ->get();
            $this->Page->search_results = "Clientes Fechados";
        }

        return view('pages.' . $this->Page->link . '.pos_fechamento.index')
            ->with('Page', $this->Page)
            ->with('Fechamentos', $Fechamentos)
            ->with('Faturamentos', $Faturamentos);
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @param int $centro_custo
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show_pos_fechamento(Request $request, $centro_custo, $id)
    {
        $request->merge(['centro_custo' => $centro_custo]);
        $request->merge(['situacao' => OrdemServico::_STATUS_FATURAMENTO_PENDENTE_]);
        $query = OrdemServico::filter_layout($request->all())
            ->whereNull('idfaturamento');

        if ($request->get('centro_custo')) {
            $query = $query->where('idcentro_custo', $id);
            $Valores = OrdemServico::getValoresPosFatoramento($query->get());
            $Fechamentos = $query->orderBy('idcliente')
                ->get();

//            $Fechamentos = $query->select('*', DB::raw('count(*) as qtd_os'))
//                ->get();
        } else {
            $Fechamentos = $query->where('idcliente', $id)->get();
            $Valores = OrdemServico::getValoresPosFatoramento($Fechamentos);
        }

        return view('pages.' . $this->Page->link . '.pos_fechamento.show')
            ->with('Page', $this->Page)
            ->with('Valores', $Valores)
            ->with('Fechamentos', $Fechamentos);
    }


    public function indexFecharPeriodo(Request $request)
    {
        return view('pages.' . $this->Page->link . '.period')
            ->with('Page', $this->Page);
    }

    public function fecharPeriodo(Request $request)
    {
        $DATA_INICIO = Carbon::createFromFormat('d/m/Y', $request->get('data_inicial'))->format('Y-m-d 23:59:59');//'2017-01-01 00:00:00' (1º dia do mês anterior)
        $DATA_FIM = Carbon::createFromFormat('d/m/Y', $request->get('data_final'))->format('Y-m-d 23:59:59');// '2017-01-31 23:59:59' (1º dia do mês vigente)

        $OrdemServicos = OrdemServico::whereBetween('data_finalizada', [$DATA_INICIO, $DATA_FIM])
            ->where('idsituacao_ordem_servico', OrdemServico::_STATUS_FINALIZADA_)
            ->orderBy('idcentro_custo', 'desc')
            ->get();

        foreach ($OrdemServicos as $ordem_servico) {
            $ordem_servico->fechar();
        }

        session()->forget('mensagem');
        session(['mensagem' => 'Fechamento realizado']);
        return Redirect::route('fechamentos.periodo_index');
    }

//
//
//
//    /**
//     * Display a listing of the resource.
//     * @param Request $request
//     * @param int $centro_custo
//     * @param int $id
//     * @return \Illuminate\Http\Response
//     */
//    public function faturar_pos(Request $request, $centro_custo, $id)
//    {
//        $request->merge(['centro_custo' => $centro_custo]);
//        $request->merge(['situacao' => OrdemServico::_STATUS_FINALIZADA_]);
//        $query = OrdemServico::filter_layout($request->all())
//            ->whereNull('idfaturamento');
//
//        if ($request->get('centro_custo')) {
//            $query = $query->where('idcentro_custo', $id);
//            $OrdemServicos = $query->get();
//        } else {
//            $OrdemServicos = $query->where('idcliente', $id)->get();
//        }
//
//        $Faturamento = Faturamento::geraFaturamento($OrdemServicos, $request->get('centro_custo'));
//
//        session()->forget('mensagem');
//        session(['mensagem' => $this->Page->msg_abr]);
//        return Redirect::route('faturamentos.show', $Faturamento->id);
//    }
//
//    public function runByOrdemServicoID($id = NULL)
//    {
//        if ($id == NULL) return $id;
//        $OrdemServicos = OrdemServico::find($id);
//        $centro_custo = ($OrdemServicos->idcentro_custo != NULL);
//        Faturamento::geraFaturamento($OrdemServicos, $centro_custo);
//        session()->forget('mensagem');
//        session(['mensagem' => $this->Page->msg_abr]);
//        return Redirect::route($this->Page->link . '.index', 'todas');
//    }
//
//
//    /**
//     * Display a listing of the resource.
//     *
//     * @return \Illuminate\Http\Response
//     */
//    public function run_temp()
//    {
//        Carbon::setTestNow('2017-02-01 00:00:00');                        // set the mock (of course this could be a real mock object)
//        $this->run();
//        Carbon::setTestNow('2017-03-01 00:00:00');                        // set the mock (of course this could be a real mock object)
//        $this->run();
//    }
//
//
//    public function run()
//    {
//        $DATA_INICIO = Carbon::parse('first day of last month')->format('Y-m-d 00:00:00');//'2017-01-01 00:00:00' (1º dia do mês anterior)
//        $DATA_FIM = Carbon::parse('last day of last month')->format('Y-m-d 23:59:59');// '2017-01-31 23:59:59' (1º dia do mês vigente)
//        $OrdemServicos = OrdemServico::whereBetween('data_finalizada', [$DATA_INICIO, $DATA_FIM])
//            ->whereNull('idfaturamento')
//            ->orderBy('idcentro_custo', 'desc')
//            ->get();
////            ->get(['idordem_servico','idcentro_custo','idcliente']);
//        return Faturamento::faturaPeriodo($OrdemServicos);
//    }
//
//
//    public function remover($id)
//    {
//        Faturamento::remover($id);
//        session()->forget('mensagem');
//        session(['mensagem' => $this->Page->msg_rea]);
//        return Redirect::route($this->Page->link . '.index', 'todas');
//    }

}

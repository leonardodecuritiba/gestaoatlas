<?php

namespace App\Http\Controllers;

use App\Cliente;
use App\FormaPagamento;
use App\Helpers\DataHelper;
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

class NotasFiscaisController extends Controller
{
    private $Page;
    private $colaborador;
    private $tecnico;

    public function __construct()
    {
        $this->Page = (object)[
            'link' => "notas_fiscais",
            'Target' => "Notas Fiscais",
            'Search' => "Buscar por CPF, CNPJ, Nome Fantasia ou Razão Social...",
            'Targets' => "Notas Fiscais",
            'Titulo' => "Nota Fiscal",
            'search_results' => "",
            'search_no_results' => "Nenhuma Nota Fiscal encontrada!",
            'titulo_primario' => "",
            'titulo_secundario' => "",
        ];
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $tipo)
    {
        $now = Carbon::now();
        $data_final = NULL;
        $data_inicial = NULL;
        if ($request->has('data_inicial')) {
            $data_inicial = DataHelper::getPrettyToCorrectDateTime($request->get('data_inicial'));
        }
        if ($request->has('data_final')) {
            $data_final = DataHelper::getPrettyToCorrectDateTime($request->get('data_final'));
        }

        if (strcmp($tipo, 'nfe') == 0) {
            $this->Page->Targets = 'NFe';
            $this->Page->extras['tipo_nf'] = 'nfe';
            $this->Page->extras['data_nf'] = 'data_nfe_producao';
            $this->Page->extras['nome_nf'] = 'NFe';
            $this->Page->extras['ref'] = 'idnfe_producao';
        } else {
            $this->Page->Targets = 'NFSe';
            $this->Page->extras['tipo_nf'] = 'nfse';
            $this->Page->extras['data_nf'] = 'data_nfse_producao';
            $this->Page->extras['nome_nf'] = 'NFSe';
            $this->Page->extras['ref'] = 'idnfse_producao';
        }

        if ($request->has('ref')) {
            $Buscas = Faturamento::where($this->Page->extras['ref'], $request->get('ref'))->first();
        } else {
//                $Buscas = Faturamento::whereNotNull('idnfe_producao')->get();
            $Buscas = Faturamento::whereBetween($this->Page->extras['data_nf'],
                [$data_inicial, $data_final])
                ->get();
        }
//
//        if ($request->has('ref')) {
//            $Buscas = Faturamento::where('id', $request->get('idfaturamento'))->with('cliente')->get();
//        } else {
//            $Buscas = Faturamento::filter_layout($request->all())->get();
//        }
//        $this->Page->extras['status_faturamento'] = StatusFechamento::whereIn('id', $Buscas->pluck('idstatus_faturamento'))->get();
//        $this->Page->extras['clientes'] = Cliente::whereIn('idcliente', $Buscas->pluck('idcliente'))->get();

        return view('pages.' . $this->Page->link . '.index')
            ->with('Page', $this->Page)
            ->with('Buscas', $Buscas);
    }
}

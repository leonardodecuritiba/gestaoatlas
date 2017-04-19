<?php

namespace App\Http\Controllers;

use App\Colaborador;
use App\Helpers\DataHelper;
use App\OrdemServico;
use App\Tecnico;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
//        $DATA = '2017-02-01 00:00:00';//'2017-01-01 00:00:00' (1º dia do mês anterior)
        $Resultados = [];
        $Score = [
            'pendentes' => 0,
            'finalizadas' => 0,
            'abertas' => 0,
            'total' => 0,
        ];


        if ($request->has('data_final')) {

            $Tecnicos = Tecnico::all();
            $data_final = Carbon::createFromFormat('d/m/Y', $request->get('data_final'));
            foreach ($Tecnicos as $tecnico) {
                $Colaborador = $tecnico->colaborador;
                $data_inicial = Carbon::createFromFormat('d/m/Y', $request->get('data_inicial'));
                $ordens = OrdemServico::whereBetween('created_at', [$data_inicial->toDateTimeString(), $data_final->toDateTimeString()])
                    ->where('idcolaborador', $Colaborador->idcolaborador);
                $pendentes = OrdemServico::where('created_at', '<', $data_inicial->toDateTimeString())
                    ->where('idcolaborador', $Colaborador->idcolaborador)
                    ->whereIn('idsituacao_ordem_servico', [
                        OrdemServico::_STATUS_ABERTA_,
                        OrdemServico::_STATUS_ATENDIMENTO_EM_ANDAMENTO_,
                        OrdemServico::_STATUS_EQUIPAMENTO_NA_OFICINA_])
                    ->sum('valor_final');

                $finalizadas = clone $ordens;
                $abertas = clone $ordens;

                $abertas = $abertas->where('idsituacao_ordem_servico', OrdemServico::_STATUS_ABERTA_)
                    ->sum('valor_final');
                $finalizadas = $finalizadas->where('idsituacao_ordem_servico', OrdemServico::_STATUS_FINALIZADA_)
                    ->sum('valor_final');


                $Resultados[] = (object)[
                    'pendentes' => 'R$ ' . DataHelper::getFloat2Real($pendentes),
                    'finalizadas' => 'R$ ' . DataHelper::getFloat2Real($finalizadas),
                    'abertas' => 'R$ ' . DataHelper::getFloat2Real($abertas),
                    'total' => 'R$ ' . DataHelper::getFloat2Real($finalizadas + $abertas + $pendentes),
                    'colaborador' => $Colaborador,
                ];

                $Score['pendentes'] += $pendentes;
                $Score['finalizadas'] += $finalizadas;
                $Score['abertas'] += $abertas;
                $Score['total'] += $finalizadas + $abertas + $pendentes;
            }

            $Score = (object)[
                'pendentes' => 'R$ ' . DataHelper::getFloat2Real($Score['pendentes']),
                'finalizadas' => 'R$ ' . DataHelper::getFloat2Real($Score['finalizadas']),
                'abertas' => 'R$ ' . DataHelper::getFloat2Real($Score['abertas']),
                'total' => 'R$ ' . DataHelper::getFloat2Real($Score['total']),
            ];
        }
        return view('index')
            ->with('Resultados', $Resultados)
            ->with('Score', $Score);
    }
}

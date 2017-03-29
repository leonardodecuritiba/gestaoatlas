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
            'finalizadas' => 0,
            'abertas' => 0,
            'total' => 0,
        ];
        if ($request->has('data')) {
            $Tecnicos = Tecnico::all();
            $data = Carbon::createFromFormat('d/m/Y', $request->get('data'));
            foreach ($Tecnicos as $tecnico) {
                $Colaborador = $tecnico->colaborador;
                $finalizadas = OrdemServico::where('created_at', '>', $data->toDateTimeString())
                    ->where('idcolaborador', $Colaborador->idcolaborador)
                    ->where('idsituacao_ordem_servico', OrdemServico::_STATUS_FINALIZADA_)
                    ->sum('valor_final');
                $abertas = OrdemServico::where('created_at', '>', $data->toDateTimeString())
                    ->where('idcolaborador', $Colaborador->idcolaborador)
                    ->where('idsituacao_ordem_servico', OrdemServico::_STATUS_ABERTA_)
                    ->sum('valor_final');
                $Resultados[] = (object)[
                    'finalizadas' => 'R$ ' . DataHelper::getFloat2Real($finalizadas),
                    'abertas' => 'R$ ' . DataHelper::getFloat2Real($abertas),
                    'total' => 'R$ ' . DataHelper::getFloat2Real($finalizadas + $abertas),
                    'colaborador' => $Colaborador,
                ];
                $Score['finalizadas'] += $finalizadas;
                $Score['abertas'] += $abertas;
                $Score['total'] += $finalizadas + $abertas;
            }

            $Score = (object)[
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

<?php

namespace App\Http\Controllers;

use App\AparelhoManutencao;
use App\OrdemServico;
use App\Tecnico;
use Illuminate\Http\Request;

class RelatoriosController extends Controller
{

    private $Page;

    public function __construct()
    {
        $this->Page = (object)[
            'link' => "relatorios",
            'Target' => "Relatório",
            'Targets' => "Relatórios",
            'Titulo' => "Relatórios",
            'search_no_results' => "Nenhuma Relatório encontrado!",
            'titulo_primario' => "",
            'titulo_secundario' => "",
            'extras' => [],
        ];
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function ipem(Request $request)
    {
//        return $request->all();
//        $DATA = '2017-02-01 00:00:00';//'2017-01-01 00:00:00' (1º dia do mês anterior)
        $Buscas = AparelhoManutencao::whereNotNull('idinstrumento');
        if ($request->has('idtecnico')) {
            $OS = OrdemServico::filterByIdTecnicoDate($request->all());
            $Buscas->whereIn('idordem_servico', $OS->pluck('idordem_servico'));
        }

        $this->Page->Targets = 'Instrumentos';
        $this->Page->extras['tecnicos'] = Tecnico::all();
        return view('pages.relatorios.ipem')
            ->with('Buscas', $Buscas->get())
            ->with('Page', $this->Page);
    }
}

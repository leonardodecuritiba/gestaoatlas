<?php

namespace App\Http\Controllers;

use App\AparelhoManutencao;
use App\Http\Exports\IpemList\IpemListExport;
use App\Tecnico;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

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
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function ipem(Request $request)
    {
        $Buscas = AparelhoManutencao::getRelatorioIpem($request);
//        return $Buscas;
        $this->Page->Targets = 'Instrumentos';
        $this->Page->extras['tecnicos'] = Tecnico::all();
        return view('pages.relatorios.ipem')
            ->with('Buscas', $Buscas)
            ->with('Page', $this->Page);
    }

    /**
     * Imprime relatório Ipem.
     *
     * @param  Request $request
     * @param  IpemListExport $export
     * @return \Illuminate\Http\Response
     */
    public function ipemPrint(Request $request, IpemListExport $export)
    {
        $Buscas = AparelhoManutencao::getRelatorioIpem($request);
        return $export->exportXls($Buscas);
    }
}

<?php

namespace App\Http\Controllers;

use App\AparelhoManutencao;
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
//        $DATA = '2017-02-01 00:00:00';//'2017-01-01 00:00:00' (1º dia do mês anterior)
        $Buscas = AparelhoManutencao::whereNotNull('idinstrumento')->get();

        return view('pages.relatorios.ipem')
            ->with('Buscas', $Buscas)
            ->with('Page', $this->Page);
    }
}

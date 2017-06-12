<?php

namespace App\Http\Controllers;

use App\Helpers\DataHelper;
use App\Models\Faturamento;
use Illuminate\Http\Request;

class NotasFiscaisController extends Controller
{
    private $Page;

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
            $Buscas = Faturamento::whereBetween($this->Page->extras['data_nf'],
                [$data_inicial, $data_final])
                ->get();
        }
        return view('pages.' . $this->Page->link . '.index')
            ->with('Page', $this->Page)
            ->with('Buscas', $Buscas);
    }
}

<?php

namespace App\Http\Controllers;

use App\AparelhoManutencao;
use App\Http\Exports\IpemList\IpemListExport;
use App\Tecnico;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use niklasravnsborg\LaravelPdf\Facades\Pdf as PDF;

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
        // work on the export
        $Buscas = AparelhoManutencao::getRelatorioIpem($request);
        $export->setFilename('teste');
        return $export->sheet('sheetName', function ($sheet) use ($export, $Buscas) {
            $sheet->setOrientation('landscape');
            $cabecalho = [
                'Razão Social',
                'Nome Fantasia',
                'Documento',
                'Nº O.S.',
                'Data do Reparo',
                'Técnico',
                'Descrição O.S.',
                'Nº de Série',
                'Nº do Inventario',
                'Marca de reparo',
                'Carga'
            ];
            $sheet->cells('A1:K1', function ($cells) use ($export) {
                // manipulate the cell
                $fonts = $export->getFonts();
                $cells->setFont($fonts->cabecalho_font);
                $cells->setFontColor($fonts->cabecalho_font_color);
                $cells->setBackground($fonts->cabecalho_background_color);
            });
            $sheet->row(1, $cabecalho);
            $i = 2;
            foreach ($Buscas as $Aparelho_manutencao) {
                $Ordem_servico = $Aparelho_manutencao->ordem_servico;
                $Cliente = $Ordem_servico->cliente->getType();
                $Instrumento = $Aparelho_manutencao->instrumento;

                $sheet->row($i, array(
                    $Cliente->razao_social,
                    $Cliente->nome_principal,
                    $Cliente->documento,
                    $Ordem_servico->idordem_servico,
                    $Ordem_servico->created_at,
                    $Ordem_servico->colaborador->nome . ' - ' . $Ordem_servico->colaborador->rg,
                    $Aparelho_manutencao->defeito . ' / ' . $Aparelho_manutencao->solucao,
                    $Instrumento->numero_serie,
                    $Instrumento->inventario,
                    $Instrumento->selo_afixado_numeracao(),
                    $Instrumento->capacidade
                ));
                $i++;
            }
        })->export('xls');
    }
}

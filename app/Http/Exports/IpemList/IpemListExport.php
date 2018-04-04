<?php

namespace App\Http\Exports\IpemList;

use Carbon\Carbon;
use \Maatwebsite\Excel\Files\NewExcelFile;

class IpemListExport extends NewExcelFile
{
    public function getFilename()
    {
        return 'relatorio_ipem_' . Carbon::now()->format('H-i_d-m-Y');
    }

    public function exportXls($Buscas)
    {
        $export = $this->export($Buscas);
        return $export->export('xls');
    }

    public function export($Buscas)
    {
        return $this->sheet('sheetName', function ($sheet) use ($Buscas) {
            $sheet->setOrientation('landscape');
            $cabecalho = [
                'Razão Social',
                'Nome Fantasia',
                'CNPJ / CPF',
                'Nº O.S.',
                'Nº do Inventario',
                'Nº de Série',
                'Marca de reparo',
                'Data do Reparo',
                'Técnico',
                'Descrição O.S.',
                'Carga'
            ];
            $sheet->cells('A1:K1', function ($cells) {
                // manipulate the cell
                $fonts = $this->getFonts();
                $cells->setFont($fonts->cabecalho_font);
                $cells->setFontColor($fonts->cabecalho_font_color);
                $cells->setBackground($fonts->cabecalho_background_color);
            });
            $sheet->row(1, $cabecalho);
            $i = 2;
            foreach ($Buscas as $sel) {
                $sheet->row($i, array(
                    $sel->cliente->razao_social,
                    $sel->cliente->nome_principal,
                    $sel->cliente->documento,
                    $sel->ordem_servico->idordem_servico,
                    $sel->instrumento->inventario,
                    $sel->instrumento->numero_serie,
                    (($sel->selo_numeracao!=NULL) ? $sel->selo_numeracao : 'sem reparo'),
                    $sel->ordem_servico->getDataAbertura(),
                    $sel->colaborador->nome.' - '.$sel->colaborador->rg,
                    $sel->defeito . ' / ' . $sel->solucao,
                    $sel->instrumento->capacidade
                ));
                $i++;
            }
        });
    }

    public function getFonts()
    {
        $default_size = '13';
        return (object)[
            'cabecalho_font' => array(
                'size' => $default_size,
                'bold' => true
            ),
            'cabecalho_font_color' => '#FFFFFF',
            'cabecalho_background_color' => '#000000',
            'nome' => array(
                'family' => 'Bookman Old Style',
                'size' => '16',
            ),
            'descricao' => array(
                'size' => $default_size,
                'bold' => true
            ),
            'endereco' => array(
                'size' => $default_size,
            ),
            'quebra' => array(
                'size' => $default_size,
                'bold' => true
            ),
            'normal' => array(
                'size' => $default_size,
            )
        ];
    }

    public function exportPdf($Buscas)
    {
        $export = $this->export($Buscas);
        return $export->export('pdf');
    }


}
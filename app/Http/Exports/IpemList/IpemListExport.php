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
//            $cabecalho = [
//                'Razão Social',
//                'Nome Fantasia',
//                'Documento',
//                'Nº O.S.',
//                'Data do Reparo',
//                'Técnico',
//                'Descrição O.S.',
//                'Nº de Série',
//                'Nº do Inventario',
//                'Marca de reparo',
//                'Carga'
//            ];
	        $cabecalho = [
		        'Razão Social',
		        'Nome Fantasia',
		        'CNPJ / CPF',
		        'Nº O.S.',
		        'Nº de Série',
		        'Marca de reparo',
                'Data do Reparo',
		        'Técnico'
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
            foreach ($Buscas as $Aparelho_manutencao) {
                $Ordem_servico = $Aparelho_manutencao->ordem_servico;
                $Cliente = $Ordem_servico->cliente->getType();
                $Instrumento = $Aparelho_manutencao->instrumento;

//                $sheet->row($i, array(
//                    $Cliente->razao_social,
//                    $Cliente->nome_principal,
//                    $Cliente->documento,
//                    $Ordem_servico->idordem_servico,
//                    $Ordem_servico->created_at,
//                    $Ordem_servico->colaborador->nome . ' - ' . $Ordem_servico->colaborador->rg,
//                    $Aparelho_manutencao->defeito . ' / ' . $Aparelho_manutencao->solucao,
//                    $Instrumento->numero_serie,
//                    $Instrumento->inventario,
//	                $Instrumento->numeracao_selo_afixado(),
//                    $Instrumento->capacidade
//                ));
	            $data_row = array(
		            $Cliente->razao_social,
		            $Cliente->nome_principal,
		            $Cliente->documento,
		            $Ordem_servico->idordem_servico,
		            $Instrumento->numero_serie,
		            $Instrumento->numeracao_selo_afixado()['text'],
		            $Ordem_servico->created_at_formatted,
		            $Ordem_servico->colaborador->nome . ' - ' . $Ordem_servico->colaborador->rg,
	            );

	            $sheet->row($i, $data_row);
                $i++;
            }
        });
    }

    public function getFonts()
    {
        $default_size = '14';
        return (object)[
            'cabecalho_font' => array(
                'size' => $default_size,
                'bold' => true
            ),
            'cabecalho_font_color' => '#FFFFFF',
            'cabecalho_background_color' => '#000000',
            'nome' => array(
                'family' => 'Bookman Old Style',
                'size' => '18',
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
<?php

namespace App\Http\Exports\IpemList;

use App\AparelhoManutencao;
use \Maatwebsite\Excel\Files\ExportHandler;

class IpemListExportHandler implements ExportHandler
{


    public function handle($export, AparelhoManutencao $Buscas)
    {
        // work on the export
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
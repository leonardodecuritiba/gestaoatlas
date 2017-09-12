<?php

namespace App\Helpers;

use App\Models\ExcelFile;
use App\Peca;
use App\Servico;

class ExportHelper {
	// ******************** FUNCTIONS ******************************
	static public function tabelaPrecoPecas( ExcelFile $export ) {
		$Pecas = Peca::all();

		return $export->sheet( 'sheetName', function ( $sheet ) use ( $Pecas ) {

			$data_peca = array(
				'idpeca',
				'descricao',
				'custo',
				'TABELA GRICKI',
				'TABELA SAVEGNAGO',
				'ABRE MERCADO FRANCA',
				'TABELA GERAL',
				'TONIN',
			);

			$sheet->row( 1, $data_peca );
			$i = 2;
			foreach ( $Pecas as $peca ) {
				$sheet->row( $i, array(
					$peca->idpeca,
					$peca->descricao,
					$peca->peca_tributacao->custo_final_float(),
					$peca->tabela_preco_by_name( 'TABELA GRICKI' )->preco_float(),
					$peca->tabela_preco_by_name( 'TABELA SAVEGNAGO' )->preco_float(),
					$peca->tabela_preco_by_name( 'ABRE MERCADO FRANCA' )->preco_float(),
					$peca->tabela_preco_by_name( 'TABELA GERAL' )->preco_float(),
					$peca->tabela_preco_by_name( 'TONIN' )->preco_float(),
				) );
				$i ++;
			}
		} )->export( 'xls' );
	}

	static public function tabelaPrecoServicos( ExcelFile $export ) {
		$Servicos = Servico::all();

		return $export->sheet( 'sheetName', function ( $sheet ) use ( $Servicos ) {

			$data = array(
				'idservico',
				'nome',
				'descricao',
				'valor',
				'TABELA GRICKI',
				'TABELA SAVEGNAGO',
				'ABRE MERCADO FRANCA',
				'TABELA GERAL',
				'TONIN',
			);

			$sheet->row( 1, $data );
			$i = 2;
			foreach ( $Servicos as $sel ) {
				$sheet->row( $i, array(
					$sel->idservico,
					$sel->nome,
					$sel->descricao,
					$sel->valor_float(),
					$sel->tabela_preco_by_name( 'TABELA GRICKI' )->preco_float(),
					$sel->tabela_preco_by_name( 'TABELA SAVEGNAGO' )->preco_float(),
					$sel->tabela_preco_by_name( 'ABRE MERCADO FRANCA' )->preco_float(),
					$sel->tabela_preco_by_name( 'TABELA GERAL' )->preco_float(),
					$sel->tabela_preco_by_name( 'TONIN' )->preco_float(),
				) );
				$i ++;
			}
		} )->export( 'xls' );
	}
}

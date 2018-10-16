<?php

use Illuminate\Database\Seeder;
use Maatwebsite\Excel\Facades\Excel;
use App\Peca;

class V2ExportPecas extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
//	    php artisan db:seed --class=V2ExportPecas
		$Pecas = Peca::all();
		return Excel::create('pecas', function ($excel) use ($Pecas) {
			$excel->sheet('Sheet 1', function($sheet) use($Pecas) {
				$data_peca = array(

					'idpeca',

					'idfornecedor',
					'brand_id',
					'group_id',
					'picture',
//				'idmarca',
//				'idgrupo',
//				'idunidade',

					'unity_name',
					'type',

					'auxiliar_code',
					'bar_code',
					'description',
					'technical_description',

					'sub_grupo',
					'warranty',
					'technical_commission',
					'seller_commission',

					//taxation
					'ncm_id',
					'cfop',
					'cst',
					'nature_operation',
					'cest',

					'icms_base_calculo',
					'icms_valor_total',
					'icms_base_calculo_st',
					'icms_valor_total_st',

					'icms_origem',
					'icms_situacao_tributaria',
					'pis_situacao_tributaria',
					'cofins_situacao_tributaria',

					'valor_unitario_comercial',
					'unidade_tributavel',
					'valor_unitario_tributavel',

					'valor_ipi',
					'valor_frete',
					'valor_seguro',
					'valor_total',
				); //porcentagem

				$sheet->row(1, $data_peca);

				$i = 2;

				foreach ($Pecas as $peca) {

					$peca_t = $peca->peca_tributacao;
					$data_export = [

						'idpeca'                => $peca->idpeca,
						'idfornecedor'          => $peca->idfornecedor,
						'brand_id'              => $peca->idmarca,
						'group_id'              => $peca->idgrupo,
						'picture'               => $peca->foto,

						'unity_name'            => $peca->unidade->codigo,
						'type'                  => $peca->tipo,

						'auxiliar_code'         => $peca->codigo_auxiliar,
						'bar_code'              => $peca->codigo_barras,
						'description'           => $peca->descricao,
						'technical_description' => $peca->descricao_tecnico,

						'sub_grupo'             => $peca->sub_grupo,
						'warranty'              => $peca->garantia,
						'technical_commission'  => $peca->comissao_tecnico,
						'seller_commission'     => $peca->comissao_vendedor,

//taxation
						'ncm_id'                => $peca_t->idncm,
						'cfop'                  => $peca_t->cfop->numeracao,
						'cst'                   => $peca_t->cst->numeracao,
						'nature_operation'      => $peca_t->natureza_operacao->descricao,
						'cest'                  => $peca_t->cest,

						'icms_base_calculo'     => ($peca_t->icms_base_calculo),
						'icms_valor_total'      => ($peca_t->icms_valor_total),
						'icms_base_calculo_st'  => ($peca_t->icms_base_calculo_st),
						'icms_valor_total_st'   => ($peca_t->icms_valor_total_st),

						'icms_origem'                   => $peca_t->icms_origem,
						'icms_situacao_tributaria'      => $peca_t->icms_situacao_tributaria,
						'pis_situacao_tributaria'       => $peca_t->pis_situacao_tributaria,
						'cofins_situacao_tributaria'    => $peca_t->cofins_situacao_tributaria,

						'valor_unitario_comercial'      => ($peca_t->valor_unitario_comercial),
						'unidade_tributavel'            => ($peca_t->unidade_tributavel),
						'valor_unitario_tributavel'     => ($peca_t->valor_unitario_tributavel),

						'valor_ipi'                     => ($peca_t->valor_ipi),
						'valor_frete'                   => ($peca_t->valor_frete),
						'valor_seguro'                  => ($peca_t->valor_seguro),
						'valor_total'                   => ($peca_t->custo_final),

					];

					$sheet->row($i, $data_export);
					$i++;
				}
			});

		})->store('xls');

	}
}

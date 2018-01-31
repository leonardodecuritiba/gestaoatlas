<?php

namespace App\Http\Controllers\Budgets;

use App\Cliente;
use App\Fornecedor;
use App\Http\Controllers\Controller;
use App\Http\Requests\Inputs\InstrumentRequest;
use App\Kit;
use App\Models\Budgets\Budget;
use App\Models\Budgets\BudgetPart;
use App\Models\Instrumentos\InstrumentoBase as BaseInstruments;
use App\Models\Inputs\Instrument;
use App\Peca;
use App\Servico;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BudgetsController extends Controller {
	private $Page;
	private $colaborador;
	private $view_folder = "pages.activities.budgets";
	private $route = "budgets";

	public function __construct() {
		$this->colaborador = Auth::user()->colaborador;
		$this->Page        = (object) [
			'link'              => $this->route,
			'view_folder'       => $this->view_folder,
			'Target'            => "Orçamento",
			'Targets'           => "Orçamentos",
			'Titulo'            => "Orçamentos",
			'extras'            => [],
			'constraints'       => [],
			'search_no_results' => "Nenhum Orçamento encontrado!",
			'msg_add'           => 'Orçamento adicionado com sucesso!',
			'msg_upd'           => 'Orçamento atualizado com sucesso!',
			'msg_rem'           => 'Orçamento removido com sucesso!',
			'titulo_primario'   => "",
			'titulo_secundario' => "",

			'response'          => array(),
		];
	}

	public function select(Request $request) {
		if($request->has('type')){
			if($request->get('type')) {
				$this->Page->response = Fornecedor::get()->map( function ( $s ) {
					return [
						'type'            => 1,
						'id'              => $s->idfornecedor,
						'name'            => $s->getName(),
						'document'        => $s->getDocument(),
						'responsible'     => $s->getResponsibleName(),
						'phone'           => $s->getPhone(),
						'created_at'      => $s->getCreatedAtFormatted(),
						'created_at_time' => $s->getCreatedAtTime()
					];
				} );
			} else {
				$this->Page->response = Cliente::get()->map( function ( $s ) {
					return [
						'type'            => 0,
						'id'              => $s->idcliente,
						'name'            => $s->getName(),
						'document'        => $s->getDocument(),
						'responsible'     => $s->getResponsibleName(),
						'phone'           => $s->getPhone(),
						'created_at'      => $s->getCreatedAtFormatted(),
						'created_at_time' => $s->getCreatedAtTime()
					];
				} );
			}
		}
		$this->Page->extras['types'] =['0'=>'Cliente', '1'=>'Fornecedor'];
		$this->Page->titulo_primario       = "Abrir Orçamento ";

		return view( $this->view_folder . '.select' )
			->with( 'Page', $this->Page );
	}

	public function open($type, $id) {
		$Data = NULL;
		if($type) {
			dd(1);
			$supplier = Fornecedor::findOrFail($id);
			$parts  = $supplier->pecas->map( function ( $s ) {
				return [
					'id'                => $s->idpeca,
					'name'              => $s->getName(),
					'price'             => $s->getCost(),
					'price_formatted'   => $s->getCostFormatted(),
				];
			} );
//			$services = Servico::get()->map( function ( $s ) {
//				return [
//					'id'                => $s->idservico,
//					'name'              => $s->getName(),
//					'price'             => $s->getCost(),
//					'price_formatted'   => $s->getCostFormatted(),
//				];
//			} );
//			$kits = Kit::get()->map( function ( $s ) {
//				return [
//					'id'                => $s->idservico,
//					'name'              => $s->getName(),
//					'price'             => $s->getCost(),
//					'price_formatted'   => $s->getCostFormatted(),
//				];
//			} );
			$this->Page->extras = [
				'response'  => $supplier,
				'parts'     => $parts,
//				'service'   => $services,
//				'kits'      => $kits
				];
		} else {
			$Data = Budget::create(['client_id' => $id]);
		}
		return redirect()->route('budgets.show', $Data->id);
	}

	public function show($id)
	{
		$Data = Budget::findOrFail($id);
//		return $Data->getPartsFormatted();
		$parts  = Peca::get()->map( function ( $s ) {
			return [
				'id'                => $s->idpeca,
				'name'              => $s->getName(),
				'price'             => $s->getCost(),
				'price_formatted'   => $s->getCostFormatted(),
			];
		} );
		$this->Page->extras = [
			'parts'     => $parts,
		];
		$this->Page->titulo_primario       = "Abrir Orçamento ";

		return view( $this->view_folder . '.show' )
			->with( 'Page', $this->Page )
			->with( 'Data', $Data );
	}

	public function addInputs(Request $request, $id)
	{
		$Budget = Budget::findOrFail($id);
		if ($request->has('part_id')) {


			$ids = $request->get('part_id');
			$values = $request->get('part_value');
			$quantities = $request->get('part_quantity');
			$discounts = $request->get('part_discount');
			foreach ($ids as $i => $id) {

				$budgetPart = $Budget->getPartById($id);
				if($budgetPart != NULL){
					$budgetPart->update([
						'quantity' => $budgetPart->quantity + $quantities[$i],
						'discount' => $budgetPart->discount + $discounts[$i],
					]);
				} else {
					$data = [
						'part_id'   => $id,
						'value'     => $values[$i],
						'quantity'  => $quantities[$i],
						'discount'  => $discounts[$i],
					];
					$Budget->addPart($data);
				}
			}
		}
		return redirect()->route('budgets.show', $Budget->id);
	}


//	public function add_insumos(Request $request, $idordem_servico)
//	{
//		$idaparelho_manutencao = $request->get('idaparelho_manutencao');
//		$AparelhoManutencao = AparelhoManutencao::find($idaparelho_manutencao);
//		if ($request->has('idservico_id')) {
//			$id = $request->get('idservico_id');
//			$valor = $request->get('idservico_valor');
//			$quantidade = $request->get('idservico_quantidade');
//			$desconto = $request->get('idservico_desconto');
//			foreach ($id as $i => $v) {
//				$idinsumo = $id[$i];
//				$insumoUtilizado = $AparelhoManutencao->hasInsumoUtilizadoId($idinsumo, 'servicos');
//				if ($insumoUtilizado != NULL) {
//					$insumoUtilizado->update([
//						'quantidade' => $insumoUtilizado->quantidade + $quantidade[$i],
//						'desconto' => $insumoUtilizado->desconto + $desconto[$i],
//					]);
//				} else {
//					$data = [
//						'idaparelho_manutencao' => $idaparelho_manutencao,
//						'idservico' => $id[$i],
//						'valor' => $valor[$i],
//						'quantidade' => $quantidade[$i],
//						'desconto' => $desconto[$i],
//					];
//					ServicoPrestado::create($data);
//				}
//			}
//		}
//		if ($request->has('idpeca_id')) {
//			$id = $request->get('idpeca_id');
//			$valor = $request->get('idpeca_valor');
//			$quantidade = $request->get('idpeca_quantidade');
//			$desconto = $request->get('idpeca_desconto');
//			foreach ($id as $i => $v) {
//				$idinsumo = $id[$i];
//				$insumoUtilizado = $AparelhoManutencao->hasInsumoUtilizadoId($idinsumo, 'pecas');
//				if ($insumoUtilizado != NULL) {
//					$insumoUtilizado->update([
//						'quantidade' => $insumoUtilizado->quantidade + $quantidade[$i],
//						'desconto' => $insumoUtilizado->desconto + $desconto[$i],
//					]);
//				} else {
//					$data = [
//						'idaparelho_manutencao' => $idaparelho_manutencao,
//						'idpeca' => $id[$i],
//						'valor' => $valor[$i],
//						'quantidade' => $quantidade[$i],
//						'desconto' => $desconto[$i],
//					];
//					PecasUtilizadas::create($data);
//				}
////                $total += DataHelper::getReal2Float($valor[$i]);
//			}
//		}
//		if ($request->has('idkit_id')) {
//			$id = $request->get('idkit_id');
//			$valor = $request->get('idkit_valor');
//			$quantidade = $request->get('idkit_quantidade');
//			$desconto = $request->get('idkit_desconto');
//			foreach ($id as $i => $v) {
//				$idinsumo = $id[$i];
//				$insumoUtilizado = $AparelhoManutencao->hasInsumoUtilizadoId($idinsumo, 'kits');
//				if ($insumoUtilizado != NULL) {
//					$insumoUtilizado->update([
//						'quantidade' => $insumoUtilizado->quantidade + $quantidade[$i],
//						'desconto' => $insumoUtilizado->desconto + $desconto[$i],
//					]);
//				} else {
//					$data = [
//						'idaparelho_manutencao' => $idaparelho_manutencao,
//						'idkit' => $id[$i],
//						'valor' => $valor[$i],
//						'quantidade' => $quantidade[$i],
//						'desconto' => $desconto[$i],
//					];
//					KitsUtilizados::create($data);
//				}
////                $total += DataHelper::getReal2Float($valor[$i]);
//			}
//		}
//		return Redirect::route('ordem_servicos.show', $idordem_servico);
//	}

	public function summary($id)
	{
		return $id;
	}

	public function reopen($id)
	{
		return $id;
	}

	public function index() {
		return 1;
		$this->Page->extras['instruments'] = Instrument::with( 'base' )->get();
		$this->Page->titulo_primario       = "Listagem de " . $this->Page->Targets;

		return view( $this->view_folder . '.admin.index' )
			->with( 'Page', $this->Page );
	}



}

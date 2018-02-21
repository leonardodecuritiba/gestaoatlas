<?php

namespace App\Http\Controllers\Budgets;

use App\Cliente;
use App\Fornecedor;
use App\Http\Controllers\Controller;
use App\Http\Requests\Budgets\BudgetCloseRequest;
use App\Models\Budgets\Budget;
use App\Models\Inputs\Instrument;
use App\Peca;
use Carbon\Carbon;
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


	public function index(Request $request) {
		$this->Page->titulo_primario        = "Listagem de " . $this->Page->Targets;
		$this->Page->extras['situation']    = [''=>'Todos','0'=>'Aberto', '1'=>'Fechado'];


		if ($request->has('id')) {
			$response = Budget::filterById($request->get('id'));
			if($response != NULL){
				$client = $response->first()->client;
				$this->Page->extras['clients']  = array([
					'id'                => $client->idcliente,
					'name'              => $client->getType()->nome_principal,
				]);
			}
		} else {
			if (!$request->has('date')) {
				$request->merge(['date' => Carbon::now()->format('d/m/Y')]);
			}
			$response = Budget::filter($request->all())->get();
//			return $response;
			if($response != NULL){
				$this->Page->extras['clients'] = $response->pluck('client')->map(function($c){
					return [
						'id'                => $c->idcliente,
						'name'              => $c->getType()->nome_principal,
					];
				});
			}
		}

		$this->Page->response  = $response->map(function($b){
			return [
				'id'                => $b->id,
				'situation_color'   => $b->getSituationColor(),
				'situation_text'    => $b->getSituationText(),
				'collaborator'      => $b->getCollaboratorName(),
				'client'            => $b->getClientName(),
				'show_url'          => $b->getShowUrl(),
				'created_at'        => $b->getCreatedAtFormatted(),
				'created_at_time'   => $b->getCreatedAtTime()
			];
		});

		return view('pages.activities.budgets.index' )
			->with( 'Page', $this->Page );
	}

    public function create(Request $request)
    {
        $this->Page->search_no_results = "Nenhum Cliente encontrado!";
        $this->Page->response = Cliente::findByText($request->get('busca'))->validos()->get()->map(function ($s) {
			return [
				'id'              => $s->idcliente,
				'name'            => $s->getName(),
				'document'        => $s->getDocument(),
				'responsible'     => $s->getResponsibleName(),
				'phone'           => $s->getPhone(),
				'created_at'      => $s->getCreatedAtFormatted(),
				'created_at_time' => $s->getCreatedAtTime()
			];
		} );
		$this->Page->titulo_primario       = "Abrir Orçamento de Venda";
		return view('pages.activities.budgets.create' )
			->with( 'Page', $this->Page );
//				$this->Page->response = Fornecedor::get()->map( function ( $s ) {
//					return [
//						'type'            => 1,
//						'id'              => $s->idfornecedor,
//						'name'            => $s->getName(),
//						'document'        => $s->getDocument(),
//						'responsible'     => $s->getResponsibleName(),
//						'phone'           => $s->getPhone(),
//						'created_at'      => $s->getCreatedAtFormatted(),
//						'created_at_time' => $s->getCreatedAtTime()
//					];
//				} );
	}

	public function open($id) {
		$Data = Budget::create(['client_id' => $id]);
		return redirect()->route('budgets.show', $Data->id);
	}

	public function show($id)
	{
		$Data = Budget::findOrFail($id);
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

		return view('pages.activities.budgets.show' )
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
				if($budgetPart != NULL){ //ATUALIZAÇÃO DE PEÇA
					$budgetPart->update([
						'quantity' => $budgetPart->quantity + $quantities[$i],
						'discount' => $budgetPart->discount + $discounts[$i],
					]);
				} else { //CRIAÇÃO DE PEÇA
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

	public function summary($id)
	{
		$Data = Budget::findOrFail($id);
		$this->Page->titulo_primario       = "Resumo Orçamento de Venda";
		return view('pages.activities.budgets.summary' )
			->with( 'Page', $this->Page )
			->with( 'Data', $Data );
	}

	public function close(BudgetCloseRequest $request, $id)
	{
		$Data = Budget::findOrFail($id);
		$Data->close($request->all());
		return redirect()->route('budgets.summary', $Data->id);
	}

	public function reopen($id)
	{
		$Data = Budget::findOrFail($id);
		$Data->reopen();
		return redirect()->route('budgets.show', $Data->id);
	}






	public function _print($id)
	{
		return $id;
	}

	public function send($id)
	{
		return $id;
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




}

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

class BudgetPartsController extends Controller {

	private $Page;

	public function __construct() {
		$this->Page        = (object) [
//			'link'              => $this->route,
//			'view_folder'       => $this->view_folder,
			'Target'            => "Peça",
			'Targets'           => "Peças",
			'Titulo'            => "Peças",
			'extras'            => [],
			'constraints'       => [],
			'search_no_results' => "Nenhum Peça encontrada!",
			'msg_add'           => 'Peça adicionada com sucesso!',
			'msg_upd'           => 'Peça atualizada com sucesso!',
			'msg_rem'           => 'Peça removida com sucesso!',
			'titulo_primario'   => "",
			'titulo_secundario' => "",

			'response'          => array(),
		];
	}

	public function destroy($id)
	{
		$data = BudgetPart::find($id);
		$data->delete();
		return response()->json(['status' => '1',
		                         'response' => $this->Page->msg_rem]);
	}

}

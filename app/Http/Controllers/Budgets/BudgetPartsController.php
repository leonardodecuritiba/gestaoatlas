<?php

namespace App\Http\Controllers\Budgets;

use App\Http\Controllers\Controller;
use App\Models\Budgets\BudgetPart;

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
			'search_no_results' => "Nenhuma Peça encontrada!",
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

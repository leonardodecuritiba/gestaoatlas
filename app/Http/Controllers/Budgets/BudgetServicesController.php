<?php

namespace App\Http\Controllers\Budgets;

use App\Http\Controllers\Controller;
use App\Models\Budgets\BudgetService;

class BudgetServicesController extends Controller {

	private $Page;

	public function __construct() {
		$this->Page        = (object) [
//			'link'              => $this->route,
//			'view_folder'       => $this->view_folder,
			'Target'            => "Serviço",
			'Targets'           => "Serviços",
			'Titulo'            => "Serviços",
			'extras'            => [],
			'constraints'       => [],
			'search_no_results' => "Nenhum Serviço encontrado!",
			'msg_add'           => 'Serviço adicionado com sucesso!',
			'msg_upd'           => 'Serviço atualizado com sucesso!',
			'msg_rem'           => 'Serviço removido com sucesso!',
			'titulo_primario'   => "",
			'titulo_secundario' => "",
			'response'          => array(),
		];
	}

	public function destroy($id)
	{
		$data = BudgetService::find($id);
		$data->delete();
		return response()->json(['status' => '1',
		                         'response' => $this->Page->msg_rem]);
	}

}

<?php

namespace App\Http\Controllers;

use App\Helpers\SintegraHelper;
use App\Models\Inputs\Vehicle;
use App\Ncm;
use App\Selo;
use App\Tecnico;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;

//use JansenFelipe\CnpjGratis\CnpjGratis as CnpjGratis;
use JansenFelipe\Utils\Utils as Utils;
use JansenFelipe\Utils\Mask as Mask;
use SintegraPHP\SP\SintegraSP;

class AjaxController extends Controller
{
	public function getNcm() {
		if (Input::has('value')) {
			$value = Input::get('value');

			return $value;
			$data = Ncm::where('codigo', 'like', $value . "%")->get();

			return $this->selectReturn('idncm', $data);
		}
	}

	public function selectReturn($id, $data) {
		$retorno = NULL;
		if (count($data) > 0) {
			foreach ($data as $i => $dt) {
				$retorno[] = array('id' => $dt->$id, 'text' => $dt->numeracao, 'data' => $dt);
			}
		}
		echo json_encode($retorno);
	}

	public function getSelosDisponiveis() {
//		return Input::all();
		$value     = Input::has('value') ? Input::get('value') : '';
		$idtecnico = Input::has('idtecnico') ? Input::get('idtecnico') : NULL;
		$Tecnico   = ($idtecnico == NULL) ? Auth::user()->colaborador->tecnico : Tecnico::findOrFail($idtecnico);
		if($Tecnico!=NULL){
			$data      = $Tecnico->selos_disponiveis();
			if(Input::has('value')){
				$data->where('numeracao', 'like', $value . "%");
			}
			return $this->selectReturn('idselo', $data->get());
		}
		return $Tecnico;
	}

	public function getLacresDisponiveis() {
		$value     = Input::has('value') ? Input::get('value') : '';
		$idtecnico = Input::has('idtecnico') ? Input::get('idtecnico') : NULL;
		$Tecnico   = ($idtecnico == NULL) ? Auth::user()->colaborador->tecnico : Tecnico::findOrFail($idtecnico);
		$data      = $Tecnico->lacres_disponiveis()->where('numeracao', 'like', $value . "%")->get();

		return $this->selectReturn('idlacre', $data);
	}

	public function getAjaxDataByID() {
		$id      = Input::get('id');
		$pk      = Input::get('pk');
		$table   = Input::get('table');
		$retorno = explode(',',Input::get('retorno'));

		$response = DB::table($table)
			->where($pk, $id)
			->get($retorno);

		return response()->json([ 'status' => '1',
		                          'response' => $response
		]);
	}

	public function ajax() {
		$id     = Input::get('id');
		$pk     = Input::get('pk');
		$sk     = Input::get('sk'); //status key
		$table  = Input::get('table');
		$action = Input::get('action');
		switch($action){
			case 'ativar':
				DB::table($table)
					->where($pk, $id)
					->update([$sk => 1]);

				return response()->json([ 'status' => '1',
				                          'response' => 'Status alterado com sucesso!',
				                          'valor'    => 1
				]);
			case 'desativar':
				DB::table($table)
					->where($pk, $id)
					->update([$sk => 0]);

				return response()->json([ 'status' => '1',
				                          'response' => 'Status alterado com sucesso!',
				                          'valor'    => 0
				]);
		}
	}

	public function ajaxSelect2() {
		$id     = Input::get('id');
		$pk     = Input::get('pk');
		$fk     = Input::get('fk');
		$field  = Input::get('field'); //status key
		$value  = Input::get('value'); //status key
		$table  = Input::get('table');
		$action = Input::get('action');
		if($value==NULL) return;
		switch($action){
			case 'busca_por_id':
				$busca = DB::table($table)
					->where($pk, $id)
					->get();
				break;
			case 'busca_por_campo':
				$busca = DB::table($table)
					->where($field,'like' , $value."%")
					->get();
				break;
			case 'busca_por_fk_campo':
				$busca = DB::table($table)
				           ->where($fk, $id)
				           ->where($field,'like' , $value . "%")
				           ->get();
				break;
		}
		$data = NULL;
		if( count($busca) > 0){
			foreach($busca as $i => $dt){
				$data[] = array( 'id' => $dt->$pk, 'text' => $dt->$field, 'data' => $dt);
			}
		}
		echo json_encode($data);
	}

//    public function consulta_cnpj(Request $request){
//        try {
//            if (!$request->has('cnpj') || !$request->has('captcha') || !$request->has('cookie'))
//                throw new Exception('Informe todos os campos', 99);
//
//            $return = CnpjGratis::consulta($request->get('cnpj'), $request->get('captcha'), $request->get('cookie'));
//
//            $return['cep'] = Utils::mask($return['cep'], Mask::CEP);
//            $return['code'] = 0;
//        } catch (Exception $e) {
//            $return = array('code' => $e->getCode(), 'message' => $e->getMessage());
//        }
//
//        header('Content-Type: application/json');
//        echo json_encode($return);
//    }

	public function consulta_sintegra_sp(Request $request){
		try {
			if ( !$request->has('captcha') || !$request->has('paramBot') || !$request->has('cookie') || !$request->has('cnpj')){
				echo json_encode([ 'status' => 0, 'response' => 'Informe todos os campos!']);
				exit;
			}

//	        $return = SintegraSP::consulta(
			$return = SintegraHelper::consulta(
				$request->get('cnpj'),
				NULL,
				$request->get('paramBot'),
				$request->get('captcha'),
				$request->get('cookie'));
//            print_r( json_encode($return));
//            return ;
//            $return = CnpjGratis::consulta($request->get('cnpj'), $request->get('captcha'), $request->get('cookie'));

//            $return['cep'] = Utils::mask($return['cep'], Mask::CEP);
//            $return['code'] = 0;
		} catch (Exception $e) {
			$return = array( 'code' => $e->getCode(), 'message' => $e->getMessage());
		}

		header('Content-Type: application/json');
		echo json_encode($return);
	}

	public function consulta_params(){
		$params = SintegraHelper::getParams();
		echo json_encode($params);
	}

	public function consulta_veiculos_marcas() {
		return Vehicle::getJsonMarcas(Input::get('tipo'));
	}

	public function consulta_veiculos_veiculos() {
		return Vehicle::getJsonVeiculos(Input::get('tipo'), Input::get('idmarca'));
	}

	public function consulta_veiculos_modelo() {
		return Vehicle::getJsonModelos(Input::get('tipo'), Input::get('idmarca'), Input::get('idveiculo'));
	}

	public function consulta_veiculos_veiculo() {
		return Vehicle::getJsonVeiculo(Input::get('tipo'), Input::get('idmarca'), Input::get('idveiculo'), Input::get('modelo'));
	}


	//declare Selo
	public function changeSeloDeclare() {
		$id         = Input::get('id');
		$declared   = Input::get('declared');
		if($declared){
			Selo::set_undeclared($id);
			return response()->json([ 'status' => '1',
			                          'response' => 'Selo não declarado com sucesso!',
			                          'valor'    => 1
			]);
		} else {
			Selo::set_declared($id);
			return response()->json([ 'status' => '1',
			                          'response' => 'Selo declarado com sucesso!',
			                          'valor'    => 0
			]);
		}
	}
}





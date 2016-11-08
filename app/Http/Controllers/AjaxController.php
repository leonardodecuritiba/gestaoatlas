<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

//use JansenFelipe\CnpjGratis\CnpjGratis as CnpjGratis;
use JansenFelipe\Utils\Utils as Utils;
use JansenFelipe\Utils\Mask as Mask;
use SintegraPHP\SP\SintegraSP;

class AjaxController extends Controller
{
    public function getAjaxDataByID()
    {
        $id      = Input::get('id');
        $pk      = Input::get('pk');
        $table   = Input::get('table');
        $retorno = explode(',',Input::get('retorno'));

        $response = DB::table($table)
            ->where($pk, $id)
            ->get($retorno);

        return response()->json(['status' => '1',
            'response'  => $response
        ]);
    }
    public function ajax()
    {
        $id = Input::get('id');
        $pk = Input::get('pk');
        $sk = Input::get('sk'); //status key
        $table = Input::get('table');
        $action = Input::get('action');
        switch($action){
            case 'ativar':
                DB::table($table)
                    ->where($pk, $id)
                    ->update([$sk => 1]);
                return response()->json(['status' => '1',
                    'response'  => 'Status alterado com sucesso!',
                    'valor'     => 1
                ]);
            case 'desativar':
                DB::table($table)
                    ->where($pk, $id)
                    ->update([$sk => 0]);
                return response()->json(['status' => '1',
                    'response'  => 'Status alterado com sucesso!',
                    'valor'     => 0
                ]);
        }
    }
    public function ajaxSelect2()
    {
        $id = Input::get('id');
        $pk = Input::get('pk');
        $fk = Input::get('fk');
        $field = Input::get('field'); //status key
        $value = Input::get('value'); //status key
        $table = Input::get('table');
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
                    ->where($field,'like' , $value."%")
                    ->get();
                break;
        }
        $data = NULL;
        if(count($busca)>0){
            foreach($busca as $i => $dt){
                $data[] = array('id' => $dt->$pk, 'text' => $dt->$field, 'data' => $dt);
            }
        }
//        $data = NULL;
//        for ($i=0;$i<10;$i++) {
//            $data[] = array('id' => $i, 'text' => 'texto '.$value);
//        }
        echo json_encode($data);
    }
    public function consulta_cnpj(Request $request){
        try {
            if (!$request->has('cnpj') || !$request->has('captcha') || !$request->has('cookie'))
                throw new Exception('Informe todos os campos', 99);

            $return = CnpjGratis::consulta($request->get('cnpj'), $request->get('captcha'), $request->get('cookie'));

            $return['cep'] = Utils::mask($return['cep'], Mask::CEP);
            $return['code'] = 0;
        } catch (Exception $e) {
            $return = array('code' => $e->getCode(), 'message' => $e->getMessage());
        }

        header('Content-Type: application/json');
        echo json_encode($return);
    }

    public function consulta_sintegra_sp(Request $request){
        try {
            if (!$request->has('captcha') || !$request->has('paramBot') || !$request->has('cookie') || !$request->has('cnpj')){
                echo json_encode(['status' => 0, 'response' => 'Informe todos os campos!']);
                exit;
            }

            $return = SintegraSP::consulta(
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
            $return = array('code' => $e->getCode(), 'message' => $e->getMessage());
        }

        header('Content-Type: application/json');
        echo json_encode($return);
    }
    public function consulta_params(){
        $params = SintegraSP::getParams();
        echo json_encode($params);
    }

}





<?php

namespace App\Http\Controllers;

use App\Equipamento;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redirect;
use Validator;
use Illuminate\Http\Request;

use App\Http\Requests;

class EquipamentosController extends Controller
{
    private $Page;
    private $idcolaborador;

    public function __construct()
    {
        /*
        $this->middleware('role:empresa');
        if(Auth::check()){
            $this->empresa_id = (Auth::user()->empresa == "")?'*':Auth::user()->empresa->EMP_ID;
            $this->Empresa = (Auth::user()->empresa == "")?'*':Auth::user()->empresa;
        }
        */
        $this->idcolaborador = 1;
        $this->Page = (object)[
            'link'              => "equipamentos",
            'Target'            => "Equipamento",
            'Targets'           => "Equipamentos",
            'Titulo'            => "Equipamentos",
            'titulo_primario'   => "",
            'titulo_secundario' => "",
            'search_no_results' => "Nenhum Equipamento encontrado!",
        ];
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'idcliente'    => 'required',
            'idmarca'      => 'required',
            'descricao'    => 'required',
            'foto'         => 'required',
            'modelo'       => 'required',
            'numero_serie' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->to($this->getRedirectUrl())
                ->withErrors($validator)
                ->withInput($request->all());
        } else {
            $data = $request->all();
//            return $data;
            //store Equipamento
            if($request->hasfile('foto')){
                $img = new ImageController();
                $data['foto'] = $img->store($request->file('foto'), $this->Page->link);
            } else {
                $data['foto'] = NULL;
            }

            $data['idcolaborador_criador']=$this->idcolaborador;
            $data['idcolaborador_validador']=$this->idcolaborador;
            $data['validated_at'] = Carbon::now()->toDateTimeString();
            $Equipamento = Equipamento::create($data);

            session()->forget('mensagem');
            session(['mensagem' => $this->Page->Target.' adicionado com sucesso!']);
            return $this->RedirectCliente($Equipamento->idcliente,'equipamentos');
        }
    }

    public function RedirectCliente($id, $tab = 'equipamentos')
    {
//        $ClientesController = new ClientesController();
//        return $ClientesController->show($id,$tab);
        return redirect()->route('clientes.show', [$id, $tab]);

    }

    public function update(Request $request, $id)
    {
        $Equipamento = Equipamento::find($id);
        $validacao = [
            'idcliente'    => 'required',
            'idmarca'      => 'required',
            'descricao'    => 'required',
            'modelo'       => 'required',
            'numero_serie' => 'required'
        ];
        $validacao = ($Equipamento->foto == NULL) ? array_merge($validacao, ['foto' => 'required']) : $validacao;
        $validator = Validator::make($request->all(), $validacao);

        if ($validator->fails()) {
            return redirect()->to($this->getRedirectUrl())
                ->withErrors($validator)
                ->withInput($request->all());
        } else {
            $dataUpdate = $request->all();
            if($request->hasfile('foto')){
                $img = new ImageController();
                $dataUpdate['foto'] = $img->update($request->file('foto'),$this->Page->link,$Equipamento->foto);
            }

            //update Equipamento
            $Equipamento->update($dataUpdate);

            session()->forget('mensagem');
            session(['mensagem' => $this->Page->Target.' atualizado com sucesso!']);
            return $this->RedirectCliente($Equipamento->idcliente,'equipamentos');
        }
    }

    public function destroy($id)
    {
        // Remover instrumento
        $Equipamento = Equipamento::find($id);
        $Equipamento->delete();
        return response()->json(['status' => '1',
            'response' => $this->Page->Target.' removido com sucesso!']);
    }
}

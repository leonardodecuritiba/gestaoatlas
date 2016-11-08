<?php

namespace App\Http\Controllers;

use App\ServicoPrestado;
use Validator;
use Illuminate\Http\Request;

use App\Http\Requests;

class ServicosPrestadosController extends Controller
{
    private $Page;

    public function __construct()
    {
        $this->Page = (object)[
            'msg_add'           => 'Serviço Prestado adicionado com sucesso!',
            'msg_upd'           => 'Serviço Prestado atualizado com sucesso!',
            'msg_rem'           => 'Serviço Prestado removido com sucesso!',
        ];
    }

    public function store(Request $request)
    {
        if($request->get('idservico') != ''){
            $data = $request->all();
            $ServicoPrestado = ServicoPrestado::create($data);
            session()->forget('mensagem');
            session(['mensagem' => $this->Page->msg_upd]);
            return redirect()->route('ordem_servicos.show',$ServicoPrestado->aparelho_manutencao->ordem_servico->idordem_servico);
        }
        return redirect()->back();
    }

    public function destroy($id)
    {
        $data = ServicoPrestado::find($id);
        $data->delete();
        return response()->json(['status' => '1',
            'response' => $this->Page->msg_rem]);
    }


}

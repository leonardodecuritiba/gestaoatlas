<?php

namespace App\Http\Controllers;

use App\KitsUtilizados;
use Validator;
use Illuminate\Http\Request;

use App\Http\Requests;

class KitsUtilizadosController extends Controller
{
    private $Page;

    public function __construct()
    {
        $this->Page = (object)[
            'msg_add'           => 'Kit adicionado com sucesso!',
            'msg_upd'           => 'Kit atualizado com sucesso!',
            'msg_rem'           => 'Kit removido com sucesso!',
        ];
    }

    public function store(Request $request)
    {
        if($request->get('idkit') != ''){
            $data = $request->all();
            $KitUtilizado = KitsUtilizados::create($data);
            session()->forget('mensagem');
            session(['mensagem' => $this->Page->msg_upd]);
            return redirect()->route('ordem_servicos.show', $KitUtilizado->aparelho_manutencao->ordem_servico->idordem_servico);
        }
        return redirect()->back();
    }

    public function destroy($id)
    {
        $data = KitsUtilizados::find($id);
        $data->delete();
        return response()->json(['status' => '1',
            'response' => $this->Page->msg_rem]);
    }


}

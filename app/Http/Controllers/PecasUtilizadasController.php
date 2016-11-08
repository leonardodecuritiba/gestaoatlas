<?php

namespace App\Http\Controllers;

use App\PecasUtilizadas;
use Validator;
use Illuminate\Http\Request;

use App\Http\Requests;

class PecasUtilizadasController extends Controller
{
    private $Page;

    public function __construct()
    {
        $this->Page = (object)[
            'msg_add'           => 'Peça adicionada com sucesso!',
            'msg_upd'           => 'Peça atualizada com sucesso!',
            'msg_rem'           => 'Peça removida com sucesso!',
        ];
    }

    public function store(Request $request)
    {
        if($request->get('idpeca') != ''){
            $data = $request->all();
            $PecaUtilizada = PecasUtilizadas::create($data);
            session()->forget('mensagem');
            session(['mensagem' => $this->Page->msg_upd]);
            return redirect()->route('ordem_servicos.show', $PecaUtilizada->aparelho_manutencao->ordem_servico->idordem_servico);
        }
        return redirect()->back();
    }

    public function destroy($id)
    {
        $data = PecasUtilizadas::find($id);
        $data->delete();
        return response()->json(['status' => '1',
            'response' => $this->Page->msg_rem]);
    }


}

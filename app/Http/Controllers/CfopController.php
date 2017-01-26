<?php

namespace App\Http\Controllers;

use App\Cfop;
use Validator;
use Illuminate\Http\Request;

use App\Http\Requests\CfopRequest;

class CfopController extends Controller
{
    private $Page;

    public function __construct()
    {
        $this->Page = (object)[
            'table' => "cfop",
            'link' => "cfop",
            'primaryKey' => "id",
            'Target' => "CFOP",
            'Targets' => "CFOP",
            'Titulo' => "CFOP",
            'search_no_results' => "Nenhum CFOP encontrado!",
            'msg_add' => 'CFOP adicionado com sucesso!',
            'msg_upd' => 'CFOP atualizado com sucesso!',
            'msg_rem' => 'CFOP removida com sucesso!',
            'titulo_primario' => "",
            'titulo_secundario' => "",
        ];
    }

    public function index(Request $request)
    {
        if (isset($request['busca'])) {
            $busca = $request['busca'];
            $Buscas = Cfop::where('numeracao', 'like', '%' . $busca . '%')
                ->paginate(10);
        } else {
            $Buscas = Cfop::paginate(10);
        }
        return view('pages.' . $this->Page->link . '.index')
            ->with('Page', $this->Page)
            ->with('Buscas', $Buscas);
    }

    public function create()
    {
        $this->Page->titulo_primario = "Cadastrar ";
        $this->Page->titulo_secundario = "Dados do " . $this->Page->Target;
        return view('pages.' . $this->Page->link . '.master')
            ->with('Page', $this->Page);
    }

    public function show($id)
    {
        $this->Page->titulo_primario = "Visualização do ";
        $Data = Cfop::find($id);
        return view('pages.' . $this->Page->link . '.show')
            ->with('Data', $Data)
            ->with('Page', $this->Page);
    }

    public function store(CfopRequest $request)
    {
        $data = $request->all();
        $Data = Cfop::create($data);
        session()->forget('mensagem');
        session(['mensagem' => $this->Page->msg_add]);
        return redirect()->route($this->Page->link . '.show', $Data->id);
    }

    public function update(CfopRequest $request, $id)
    {
        $Data = Cfop::find($id);
        $data = $request->all();
        $Data->update($data);
        session()->forget('mensagem');
        session(['mensagem' => $this->Page->msg_upd]);
        return redirect()->route($this->Page->link . '.show', $Data->id);
    }

    public function destroy($id)
    {
        $data = Cfop::find($id);
        $data->delete();
        return response()->json(['status' => '1',
            'response' => $this->Page->msg_rem]);
    }
}

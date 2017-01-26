<?php

namespace App\Http\Controllers;

use App\Cst;
use Validator;
use Illuminate\Http\Request;

use App\Http\Requests\CstRequest;

class CstController extends Controller
{
    private $Page;

    public function __construct()
    {
        $this->Page = (object)[
            'table' => "cst",
            'link' => "cst",
            'primaryKey' => "id",
            'Target' => "CST",
            'Targets' => "CST",
            'Titulo' => "CST",
            'search_no_results' => "Nenhum CST encontrado!",
            'msg_add' => 'CST adicionado com sucesso!',
            'msg_upd' => 'CST atualizado com sucesso!',
            'msg_rem' => 'CST removida com sucesso!',
            'titulo_primario'   => "",
            'titulo_secundario' => "",
        ];
    }
    public function index(Request $request)
    {
        if(isset($request['busca'])){
            $busca = $request['busca'];
            $Buscas = Cst::where('numeracao', 'like', '%' . $busca . '%')
                ->paginate(10);
        } else {
            $Buscas = Cst::paginate(10);
        }
        return view('pages.'.$this->Page->link.'.index')
            ->with('Page', $this->Page)
            ->with('Buscas',$Buscas);
    }

    public function create()
    {
        $this->Page->titulo_primario    = "Cadastrar ";
        $this->Page->titulo_secundario  = "Dados do ".$this->Page->Target;
        return view('pages.'.$this->Page->link.'.master')
            ->with('Page', $this->Page);
    }

    public function show($id)
    {
        $this->Page->titulo_primario = "Visualização do ";
        $Data = Cst::find($id);
        return view('pages.'.$this->Page->link.'.show')
            ->with('Data', $Data)
            ->with('Page', $this->Page);
    }

    public function store(CstRequest $request)
    {
        $data = $request->all();
        $Data = Cst::create($data);
        session()->forget('mensagem');
        session(['mensagem' => $this->Page->msg_add]);
        return redirect()->route($this->Page->link . '.show', $Data->id);
    }

    public function update(CstRequest $request, $id)
    {
        $Data = Cst::find($id);
        $data = $request->all();
        $Data->update($data);
        session()->forget('mensagem');
        session(['mensagem' => $this->Page->msg_upd]);
        return redirect()->route($this->Page->link . '.show', $Data->id);
    }

    public function destroy($id)
    {
        $data = Cst::find($id);
        $data->delete();
        return response()->json(['status' => '1',
            'response' => $this->Page->msg_rem]);
    }
}

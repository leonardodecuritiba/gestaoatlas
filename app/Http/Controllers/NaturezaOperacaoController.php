<?php

namespace App\Http\Controllers;

use App\Http\Requests\NaturezaOperacaoRequest;
use App\NaturezaOperacao;
use Illuminate\Support\Facades\Redirect;
use Validator;
use Illuminate\Http\Request;

class NaturezaOperacaoController extends Controller
{
    private $Page;

    public function __construct()
    {
        $this->Page = (object)[
//            'table'             => "natureza_operacao",
            'link' => "natureza_operacao",
            'primaryKey' => "id",
            'Target' => "Natureza de Operação",
            'Targets' => "Natureza de Operação",
            'Titulo' => "Natureza de Operação",
            'search_no_results' => "Nenhuma Natureza de Operação encontrada!",
            'msg_add' => 'Natureza de Operação adicionada com sucesso!',
            'msg_upd' => 'Natureza de Operação atualizada com sucesso!',
            'msg_rem' => 'Natureza de Operação removida com sucesso!',
            'titulo_primario' => "",
            'titulo_secundario' => "",
        ];
    }

    public function index(Request $request)
    {
        if (isset($request['busca'])) {
            $busca = $request['busca'];
            $Buscas = NaturezaOperacao::where('numero', 'like', '%' . $busca . '%')
                ->orWhere('descricao', 'like', '%' . $busca . '%')
                ->paginate(10);
        } else {
            $Buscas = NaturezaOperacao::paginate(10);
        }
        return view('pages.' . $this->Page->link . '.index')
            ->with('Page', $this->Page)
            ->with('Buscas', $Buscas);
    }

    public function create()
    {
        $this->Page->titulo_primario = "Cadastrar ";
        $this->Page->titulo_secundario = "Dados da " . $this->Page->Target;
        return view('pages.' . $this->Page->link . '.master')
            ->with('Page', $this->Page);
    }

    public function show($id)
    {
        $this->Page->titulo_primario = "Visualização da ";
        $Data = NaturezaOperacao::find($id);
        return view('pages.' . $this->Page->link . '.show')
            ->with('Data', $Data)
            ->with('Page', $this->Page);
    }

    public function store(NaturezaOperacaoRequest $request)
    {
        $data = $request->all();
        $Data = NaturezaOperacao::create($data);
        session()->forget('mensagem');
        session(['mensagem' => $this->Page->msg_add]);
        return Redirect::route($this->Page->link . '.show', $Data->id);
    }

    public function update(NaturezaOperacaoRequest $request, $id)
    {
        $Data = NaturezaOperacao::find($id);
        $data = $request->all();
        $Data->update($data);
        session()->forget('mensagem');
        session(['mensagem' => $this->Page->msg_upd]);
        return Redirect::route($this->Page->link . '.show', $Data->id);
    }

    public function destroy($id)
    {

        return response()->json(['status' => '0',
            'response' => 'NÃO É POSSÍVEL REMOVER NATUREZA DE OPERAÇÃO PELA ASSOCIAÇÃO COM OUTRAS TABELAS: CONTATE O ADMINISTRADOR!']);
        $data = NaturezaOperacao::find($id);
        $data->delete();
        return response()->json(['status' => '1',
            'response' => $this->Page->msg_rem]);
    }
}

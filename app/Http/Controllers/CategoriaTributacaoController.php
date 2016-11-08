<?php

namespace App\Http\Controllers;

use App\CategoriaTributacao;
use Validator;
use Illuminate\Http\Request;

use App\Http\Requests;

class CategoriaTributacaoController extends Controller
{
    private $Page;

    public function __construct()
    {
        $this->Page = (object)[
            'table'             => "categoria_tributacao",
            'link'              => "categoria_tributacao",
            'primaryKey'        => "idcategoria_tributacao",
            'Target'            => "Categoria Tributável",
            'Targets'           => "Categorias Tributáveis",
            'Titulo'            => "Categoria Tributável",
            'search_no_results' => "Nenhuma Categoria Tributável encontrado!",
            'msg_add'           => 'Categoria Tributável adicionada com sucesso!',
            'msg_upd'           => 'Categoria Tributável atualizada com sucesso!',
            'msg_rem'           => 'Categoria Tributável removida com sucesso!',
            'titulo_primario'   => "",
            'titulo_secundario' => "",
        ];
    }
    public function index(Request $request)
    {
        if(isset($request['busca'])){
            $busca = $request['busca'];
            $Buscas = CategoriaTributacao::where('descricao', 'like', '%'.$busca.'%')
                ->orwhere('codigo', 'like', '%'.$busca.'%')
                ->paginate(10);
        } else {
            $Buscas = CategoriaTributacao::paginate(10);
        }
        return view('pages.'.$this->Page->link.'.index')
            ->with('Page', $this->Page)
            ->with('Buscas',$Buscas);
    }

    public function create()
    {
        $this->Page->titulo_primario    = "Cadastrar ";
        $this->Page->titulo_secundario  = "Dados da ".$this->Page->Target;
        return view('pages.'.$this->Page->link.'.master')
            ->with('Page', $this->Page);
    }

    public function show($id)
    {
        $this->Page->titulo_primario = "Visualização de ";
        $CategoriaTributacao = CategoriaTributacao::find($id);
        return view('pages.'.$this->Page->link.'.show')
            ->with('CategoriaTributacao', $CategoriaTributacao)
            ->with('Page', $this->Page);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'codigo'         => 'required|unique:'.$this->Page->table,
            'descricao'      => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->to($this->getRedirectUrl())
                ->withErrors($validator)
                ->withInput($request->all());
        } else {
            $data = $request->all();
            $CategoriaTributacao = CategoriaTributacao::create($data);
            session()->forget('mensagem');
            session(['mensagem' => $this->Page->msg_add]);
            return $this->show($CategoriaTributacao->idcategoria_tributacao);
        }
    }

    public function update(Request $request, $id)
    {
        $CategoriaTributacao = CategoriaTributacao::find($id);
        $validator = Validator::make($request->all(), [
            'codigo'    => 'unique:'.$this->Page->table.',codigo,'.$id.','.$this->Page->primaryKey,
            'descricao' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->to($this->getRedirectUrl())
                ->withErrors($validator)
                ->withInput($request->all());
        } else {
            $dataUpdate = $request->all();
            $CategoriaTributacao->update($dataUpdate);

            session()->forget('mensagem');
            session(['mensagem' => $this->Page->msg_upd]);
            return $this->show($CategoriaTributacao->idcategoria_tributacao);
        }
    }

    public function destroy($id)
    {
        $data = CategoriaTributacao::find($id);
        $data->delete();
        return response()->json(['status' => '1',
            'response' => $this->Page->msg_rem]);
    }
}

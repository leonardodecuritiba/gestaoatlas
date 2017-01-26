<?php

namespace App\Http\Controllers;

use App\OrigemTributacao;
use Validator;
use Illuminate\Http\Request;

use App\Http\Requests;

class OrigemTributacaoController extends Controller
{
    private $Page;

    public function __construct()
    {
        $this->Page = (object)[
            'table'             => "origem_tributacao",
            'link'              => "origem_tributacao",
            'primaryKey'        => "idorigem_tributacao",
            'Target'            => "Origem",
            'Targets'           => "Origens",
            'Titulo'            => "Origem",
            'search_no_results' => "Nenhuma Origem encontrado!",
            'msg_add'           => 'Origem adicionada com sucesso!',
            'msg_upd'           => 'Origem atualizada com sucesso!',
            'msg_rem'           => 'Origem removida com sucesso!',
            'titulo_primario'   => "",
            'titulo_secundario' => "",
        ];
    }
    public function index(Request $request)
    {
        if(isset($request['busca'])){
            $busca = $request['busca'];
            $Buscas = OrigemTributacao::where('descricao', 'like', '%'.$busca.'%')
                ->orwhere('codigo', 'like', '%'.$busca.'%')
                ->paginate(10);
        } else {
            $Buscas = OrigemTributacao::paginate(10);
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
            $OrigemTributacao = OrigemTributacao::create($data);
            session()->forget('mensagem');
            session(['mensagem' => $this->Page->msg_add]);
            return $this->show($OrigemTributacao->idorigem_tributacao);
        }
    }

    public function show($id)
    {
        $this->Page->titulo_primario = "Visualização de ";
        $OrigemTributacao = OrigemTributacao::find($id);
        return view('pages.' . $this->Page->link . '.show')
            ->with('OrigemTributacao', $OrigemTributacao)
            ->with('Page', $this->Page);
    }

    public function update(Request $request, $id)
    {
        $OrigemTributacao = OrigemTributacao::find($id);
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
            $OrigemTributacao->update($dataUpdate);

            session()->forget('mensagem');
            session(['mensagem' => $this->Page->msg_upd]);
            return $this->show($OrigemTributacao->idorigem_tributacao);
        }
    }

    public function destroy($id)
    {
        $data = OrigemTributacao::find($id);
        $data->delete();
        return response()->json(['status' => '1',
            'response' => $this->Page->msg_rem]);
    }
}

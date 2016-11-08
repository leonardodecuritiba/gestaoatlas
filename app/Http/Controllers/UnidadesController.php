<?php

namespace App\Http\Controllers;

use App\Unidade;
use Validator;
use Illuminate\Http\Request;

use App\Http\Requests;

class UnidadesController extends Controller
{
    private $Page;

    public function __construct()
    {
        $this->Page = (object)[
            'table'             => "unidades",
            'link'              => "unidades",
            'primaryKey'        => "idunidade",
            'Target'            => "Unidade",
            'Targets'           => "Unidades",
            'Titulo'            => "Unidades",
            'search_no_results' => "Nenhuma Unidade encontrado!",
            'msg_add'           => 'Unidade adicionada com sucesso!',
            'msg_upd'           => 'Unidade atualizada com sucesso!',
            'msg_rem'           => 'Unidade removida com sucesso!',
            'titulo_primario'   => "",
            'titulo_secundario' => "",
        ];
    }
    public function index(Request $request)
    {
        if(isset($request['busca'])){
            $busca = $request['busca'];
            $Buscas = Unidade::where('codigo', 'like', '%'.$busca.'%')
                ->orWhere('descricao', 'like', '%'.$busca.'%')
                ->paginate(10);
        } else {
            $Buscas = Unidade::paginate(10);
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
        $Unidade = Unidade::find($id);
        return view('pages.'.$this->Page->link.'.show')
            ->with('Unidade', $Unidade)
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
            $Unidade = Unidade::create($data);
            session()->forget('mensagem');
            session(['mensagem' => $this->Page->msg_add]);
            return $this->show($Unidade->idunidade);
        }
    }

    public function update(Request $request, $id)
    {
        $Unidade = Unidade::find($id);
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
            $Unidade->update($dataUpdate);

            session()->forget('mensagem');
            session(['mensagem' => $this->Page->msg_upd]);
            return $this->show($Unidade->idunidade);
        }
    }

    public function destroy($id)
    {
        $data = Unidade::find($id);
        $data->delete();
        return response()->json(['status' => '1',
            'response' => $this->Page->msg_rem]);
    }
}

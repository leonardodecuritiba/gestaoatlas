<?php

namespace App\Http\Controllers;

use App\Grupo;
use Validator;
use Illuminate\Http\Request;

use App\Http\Requests;

class GruposController extends Controller
{
    private $Page;

    public function __construct()
    {
        $this->Page = (object)[
            'table'             => "grupos",
            'link'              => "grupos",
            'primaryKey'        => "idgrupo",
            'Target'            => "Grupo",
            'Targets'           => "Grupos",
            'Titulo'            => "Grupos",
            'search_no_results' => "Nenhum Grupo encontrado!",
            'msg_add'           => 'Grupo adicionado com sucesso!',
            'msg_upd'           => 'Grupo atualizado com sucesso!',
            'msg_rem'           => 'Grupo removido com sucesso!',
            'titulo_primario'   => "",
            'titulo_secundario' => "",
        ];
    }
    public function index(Request $request)
    {
        if(isset($request['busca'])){
            $busca = $request['busca'];
            $Buscas = Grupo::where('descricao', 'like', '%'.$busca.'%')
                ->paginate(10);
        } else {
            $Buscas = Grupo::paginate(10);
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
        $this->Page->titulo_primario = "Visualização de ";
        $Grupo = Grupo::find($id);
        return view('pages.'.$this->Page->link.'.show')
            ->with('Grupo', $Grupo)
            ->with('Page', $this->Page);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'descricao'         => 'required|unique:'.$this->Page->table,
        ]);
        if ($validator->fails()) {
            return redirect()->to($this->getRedirectUrl())
                ->withErrors($validator)
                ->withInput($request->all());
        } else {
            $data = $request->all();
            $Grupo = Grupo::create($data);
            session()->forget('mensagem');
            session(['mensagem' => $this->Page->msg_add]);
            return $this->show($Grupo->idgrupo);
        }
    }

    public function update(Request $request, $id)
    {
        $Grupo = Grupo::find($id);
        $validator = Validator::make($request->all(), [
            'descricao' => 'unique:'.$this->Page->table.',descricao,'.$id.','.$this->Page->primaryKey,
        ]);

        if ($validator->fails()) {
            return redirect()->to($this->getRedirectUrl())
                ->withErrors($validator)
                ->withInput($request->all());
        } else {
            $dataUpdate = $request->all();
            $Grupo->update($dataUpdate);

            session()->forget('mensagem');
            session(['mensagem' => $this->Page->msg_upd]);
            return $this->show($Grupo->idgrupo);
        }
    }

    public function destroy($id)
    {
        $data = Grupo::find($id);
        $data->delete();
        return response()->json(['status' => '1',
            'response' => $this->Page->msg_rem]);
    }
}

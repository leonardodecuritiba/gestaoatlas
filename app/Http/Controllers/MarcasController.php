<?php

namespace App\Http\Controllers;

use App\Marca;
use Validator;
use Illuminate\Http\Request;

use App\Http\Requests;

class MarcasController extends Controller
{
    private $Page;

    public function __construct()
    {
        $this->Page = (object)[
            'table'             => "marcas",
            'link'              => "marcas",
            'primaryKey'        => "idmarca",
            'Target'            => "Marca",
            'Targets'           => "Marcas",
            'Titulo'            => "Marcas",
            'search_no_results' => "Nenhuma Marca encontrado!",
            'msg_add'           => 'Marca adicionada com sucesso!',
            'msg_upd'           => 'Marca atualizada com sucesso!',
            'msg_rem'           => 'Marca removida com sucesso!',
            'titulo_primario'   => "",
            'titulo_secundario' => "",
        ];
    }
    public function index(Request $request)
    {
        if(isset($request['busca'])){
            $busca = $request['busca'];
            $Buscas = Marca::where('descricao', 'like', '%'.$busca.'%')
                ->paginate(10);
        } else {
            $Buscas = Marca::paginate(10);
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
        $Marca = Marca::find($id);
        return view('pages.'.$this->Page->link.'.show')
            ->with('Marca', $Marca)
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
            $Marca = Marca::create($data);
            session()->forget('mensagem');
            session(['mensagem' => $this->Page->msg_add]);
            return $this->show($Marca->idmarca);
        }
    }

    public function update(Request $request, $id)
    {
        $Marca = Marca::find($id);
        $validator = Validator::make($request->all(), [
            'descricao' => 'unique:'.$this->Page->table.',descricao,'.$id.','.$this->Page->primaryKey,
        ]);

        if ($validator->fails()) {
            return redirect()->to($this->getRedirectUrl())
                ->withErrors($validator)
                ->withInput($request->all());
        } else {
            $dataUpdate = $request->all();
            $Marca->update($dataUpdate);

            session()->forget('mensagem');
            session(['mensagem' => $this->Page->msg_upd]);
            return $this->show($Marca->idmarca);
        }
    }

    public function destroy($id)
    {
        $data = Marca::find($id);
        $data->delete();
        return response()->json(['status' => '1',
            'response' => $this->Page->msg_rem]);
    }
}

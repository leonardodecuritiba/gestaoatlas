<?php

namespace App\Http\Controllers;

use App\Models\Ajustes\Ajuste;
use Validator;
use Illuminate\Http\Request;

use App\Http\Requests;

class AjustesController extends Controller
{
    private $Page;

    public function __construct()
    {
        $this->Page = (object)[
            'table'             => "ajustes",
            'link'              => "ajustes",
            'primaryKey'        => "id",
            'Target'            => "Ajuste",
            'Targets'           => "Ajustes",
            'Titulo'            => "Ajustes",
            'search_no_results' => "Nenhum Ajuste encontrado!",
            'msg_add'           => 'Ajuste adicionado com sucesso!',
            'msg_upd'           => 'Ajuste atualizado com sucesso!',
            'msg_rem'           => 'Ajuste removido com sucesso!',
            'titulo_primario'   => "",
            'titulo_secundario' => "",
        ];
    }
    public function index(Request $request)
    {
        if(isset($request['busca'])){
            $busca = $request['busca'];
            $Buscas = Ajuste::where('meta_key', 'like', '%'.$busca.'%')
                ->orWhere('meta_value', 'like', '%'.$busca.'%')
                ->paginate(10);
        } else {
            $Buscas = Ajuste::paginate(10);
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
        $Ajuste = Ajuste::find($id);
        return view('pages.'.$this->Page->link.'.show')
            ->with('Ajuste', $Ajuste)
            ->with('Page', $this->Page);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'meta_key'         => 'required|unique:'.$this->Page->table,
            'meta_value'       => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->to($this->getRedirectUrl())
                ->withErrors($validator)
                ->withInput($request->all());
        } else {
            $data = $request->all();
            $Ajuste = Ajuste::create($data);
            session()->forget('mensagem');
            session(['mensagem' => $this->Page->msg_add]);
            return redirect()->route('ajustes.show',$Ajuste->id);
        }
    }

    public function update(Request $request, $id)
    {
        $Ajuste = Ajuste::find($id);
        $validator = Validator::make($request->all(), [
            'meta_key'         => 'unique:'.$this->Page->table.',meta_key,'.$id.','.$this->Page->primaryKey,
            'meta_value'       => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->to($this->getRedirectUrl())
                ->withErrors($validator)
                ->withInput($request->all());
        } else {
            $dataUpdate = $request->all();
            $Ajuste->update($dataUpdate);

            session()->forget('mensagem');
            session(['mensagem' => $this->Page->msg_upd]);
            return redirect()->route('ajustes.show',$Ajuste->id);
        }
    }

    public function destroy($id)
    {
        $data = Ajuste::find($id);
        $data->delete();
        return response()->json(['status' => '1',
            'response' => $this->Page->msg_rem]);
    }
}

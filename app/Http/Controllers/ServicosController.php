<?php

namespace App\Http\Controllers;

use App\Servico;
use Validator;
use Illuminate\Http\Request;

use App\Http\Requests;

class ServicosController extends Controller
{
    private $Page;

    public function __construct()
    {
        $this->Page = (object)[
            'table'             => "servicos",
            'link'              => "servicos",
            'primaryKey'        => "idservico",
            'Target'            => "Serviço",
            'Targets'           => "Serviços",
            'Titulo'            => "Serviços",
            'search_no_results' => "Nenhum Serviço encontrado!",
            'msg_add'           => 'Serviço adicionado com sucesso!',
            'msg_upd'           => 'Serviço atualizado com sucesso!',
            'msg_rem'           => 'Serviço removido com sucesso!',
            'titulo_primario'   => "",
            'titulo_secundario' => "",
        ];
    }
    public function index(Request $request)
    {
        if(isset($request['busca'])){
            $busca = $request['busca'];
            $Buscas = Servico::where('nome', 'like', '%'.$busca.'%')
                ->paginate(10);
        } else {
            $Buscas = Servico::paginate(10);
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
        $Serviço = Servico::find($id);
        return view('pages.'.$this->Page->link.'.show')
            ->with('Servico', $Serviço)
            ->with('Page', $this->Page);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nome'         => 'required|unique:'.$this->Page->table,
        ]);
        if ($validator->fails()) {
            return redirect()->to($this->getRedirectUrl())
                ->withErrors($validator)
                ->withInput($request->all());
        } else {
            $data = $request->all();
            $Serviço = Servico::create($data);
            session()->forget('mensagem');
            session(['mensagem' => $this->Page->msg_add]);
            return $this->show($Serviço->idservico);
        }
    }

    public function update(Request $request, $id)
    {
        $Serviço = Servico::find($id);
        $validator = Validator::make($request->all(), [
            'nome' => 'unique:'.$this->Page->table.',nome,'.$id.','.$this->Page->primaryKey,
        ]);

        if ($validator->fails()) {
            return redirect()->to($this->getRedirectUrl())
                ->withErrors($validator)
                ->withInput($request->all());
        } else {
            $dataUpdate = $request->all();
            $Serviço->update($dataUpdate);

            session()->forget('mensagem');
            session(['mensagem' => $this->Page->msg_upd]);
            return $this->show($Serviço->idservico);
        }
    }

    public function destroy($id)
    {
        $data = Servico::find($id);
        $data->delete();
        return response()->json(['status' => '1',
            'response' => $this->Page->msg_rem]);
    }
}

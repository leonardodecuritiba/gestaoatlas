<?php

namespace App\Http\Controllers;

use App\Regiao;
use Validator;
use Illuminate\Http\Request;

use App\Http\Requests;

class RegioesController extends Controller
{
    private $Page;

    public function __construct()
    {
        /*
        $this->middleware('role:empresa');
        if(Auth::check()){
            $this->empresa_id = (Auth::user()->empresa == "")?'*':Auth::user()->empresa->EMP_ID;
            $this->Empresa = (Auth::user()->empresa == "")?'*':Auth::user()->empresa;
        }
        */
        $this->idprofissional_criador = 1;
        $this->Page = (object)[
            'link'              => "regioes",
            'Target'            => "Região",
            'Targets'           => "Regiões",
            'Titulo'            => "Regiões",
            'search_no_results' => "Nenhuma região encontrada!",
            'titulo_primario'   => "",
            'titulo_secundario' => "",
        ];
    }
    public function index()
    {
        $Buscas = Regiao::paginate(10);
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
        $Regiao = Regiao::find($id);
        return view('pages.'.$this->Page->link.'.show')
            ->with('Regiao', $Regiao)
            ->with('Page', $this->Page);
    }

    public function store(Request $request)
    {
        //
        $validator = Validator::make($request->all(), [
            'descricao'         => 'required|unique:regioes',
        ]);
        if ($validator->fails()) {
            return redirect()->to($this->getRedirectUrl())
                ->withErrors($validator)
                ->withInput($request->all());
        } else {
            $data = $request->all();
            $Regiao = Regiao::create($data);
            session()->forget('mensagem');
            session(['mensagem' => $this->Page->Target.' adicionado com sucesso!']);
            return $this->show($Regiao->idregiao);
        }
    }

    public function update(Request $request, $id)
    {
        $Regiao = Regiao::find($id);
        $validator = Validator::make($request->all(), [
            'descricao' => 'unique:regioes,descricao,'.$id.',idregiao',
        ]);

        if ($validator->fails()) {
            return redirect()->to($this->getRedirectUrl())
                ->withErrors($validator)
                ->withInput($request->all());
        } else {
            $dataUpdate = $request->all();
            $Regiao->update($dataUpdate);

            session()->forget('mensagem');
            session(['mensagem' => $this->Page->Target.' atualizado com sucesso!']);
            return $this->show($Regiao->idregiao);
        }
    }

    public function destroy($id)
    {
        // Remover Regiao
        $Regiao = Regiao::find($id);
        $Regiao->delete();
        return response()->json(['status' => '1',
            'response' => $this->Page->Target.' removido com sucesso!']);
    }
}

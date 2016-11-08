<?php

namespace App\Http\Controllers;

use App\TabelaPreco;
use Validator;
use Illuminate\Http\Request;

use App\Http\Requests;

class TabelaPrecosController extends Controller
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
            'link'              => "tabela_precos",
            'Target'            => "Tabela de Preços",
            'Targets'           => "Tabelas de Preços",
            'Titulo'            => "Tabelas de Preços",
            'search_no_results' => "Nenhuma tabela encontrada!",
            'titulo_primario'   => "",
            'titulo_secundario' => "",
        ];
    }
    public function index()
    {
        $Buscas = TabelaPreco::paginate(10);
        return view('pages.'.$this->Page->link.'.index')
            ->with('Page', $this->Page)
            ->with('Buscas',$Buscas);
    }

    public function create()
    {
        $this->Page->titulo_primario    = "Cadastrar ";
        $this->Page->titulo_secundario  = "Dados da Tabela";
        return view('pages.'.$this->Page->link.'.master')
            ->with('Page', $this->Page);
    }

    public function show($id)
    {
        $this->Page->titulo_primario = "Visualização de ";
        $TabelaPreco = TabelaPreco::find($id);
        return view('pages.'.$this->Page->link.'.show')
            ->with('TabelaPreco', $TabelaPreco)
            ->with('Page', $this->Page);
    }

    public function store(Request $request)
    {
        //
        $validator = Validator::make($request->all(), [
            'descricao'         => 'required|unique:tabela_precos',
        ]);
        if ($validator->fails()) {
            return redirect()->to($this->getRedirectUrl())
                ->withErrors($validator)
                ->withInput($request->all());
        } else {
            $data = $request->all();
            $TabelaPreco = TabelaPreco::create($data);
            session()->forget('mensagem');
            session(['mensagem' => $this->Page->Target.' adicionado com sucesso!']);
            return $this->show($TabelaPreco->idtabela_preco);
        }
    }

    public function update(Request $request, $id)
    {
        $TabelaPreco = TabelaPreco::find($id);
        $validator = Validator::make($request->all(), [
            'descricao' => 'unique:tabela_precos,descricao,'.$id.',idtabela_preco',
        ]);

        if ($validator->fails()) {
            return redirect()->to($this->getRedirectUrl())
                ->withErrors($validator)
                ->withInput($request->all());
        } else {
            $dataUpdate = $request->all();
            $TabelaPreco->update($dataUpdate);

            session()->forget('mensagem');
            session(['mensagem' => $this->Page->Target.' atualizado com sucesso!']);
            return $this->show($TabelaPreco->idtabela_preco);
        }
    }

    public function destroy($id)
    {
        // Remover TabelaPreco
        $TabelaPreco = TabelaPreco::find($id);
        $TabelaPreco->delete();
        return response()->json(['status' => '1',
            'response' => $this->Page->Target.' removido com sucesso!']);
    }
}

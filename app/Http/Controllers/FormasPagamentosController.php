<?php

namespace App\Http\Controllers;

use App\FormaPagamento;
use Validator;
use Illuminate\Http\Request;

use App\Http\Requests;

class FormasPagamentosController extends Controller
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
            'link'              => "formas_pagamentos",
            'Target'            => "Forma de Pagamento",
            'Targets'           => "Formas de Pagamento",
            'Titulo'            => "Formas de Pagamento",
            'search_no_results' => "Nenhuma forma encontrada!",
            'titulo_primario'   => "",
            'titulo_secundario' => "",
        ];
    }
    public function index()
    {
        $Buscas = FormaPagamento::paginate(10);
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
        $FormaPagamento = FormaPagamento::find($id);
        return view('pages.'.$this->Page->link.'.show')
            ->with('FormaPagamento', $FormaPagamento)
            ->with('Page', $this->Page);
    }

    public function store(Request $request)
    {
        //
        $validator = Validator::make($request->all(), [
            'descricao'         => 'required|unique:formas_pagamentos',
        ]);
        if ($validator->fails()) {
            return redirect()->to($this->getRedirectUrl())
                ->withErrors($validator)
                ->withInput($request->all());
        } else {
            $data = $request->all();
            $FormaPagamento = FormaPagamento::create($data);
            session()->forget('mensagem');
            session(['mensagem' => $this->Page->Target.' adicionado com sucesso!']);
            return $this->show($FormaPagamento->idforma_pagamento);
        }
    }

    public function update(Request $request, $id)
    {
        $FormaPagamento = FormaPagamento::find($id);
        $validator = Validator::make($request->all(), [
            'descricao' => 'unique:formas_pagamentos,descricao,'.$id.',idforma_pagamento',
        ]);

        if ($validator->fails()) {
            return redirect()->to($this->getRedirectUrl())
                ->withErrors($validator)
                ->withInput($request->all());
        } else {
            $dataUpdate = $request->all();
            $FormaPagamento->update($dataUpdate);

            session()->forget('mensagem');
            session(['mensagem' => $this->Page->Target.' atualizado com sucesso!']);
            return $this->show($FormaPagamento->idforma_pagamento);
        }
    }

    public function destroy($id)
    {
        // Remover FormaPagamento
        $FormaPagamento = FormaPagamento::find($id);
        $FormaPagamento->delete();
        return response()->json(['status' => '1',
            'response' => $this->Page->Target.' removido com sucesso!']);
    }
}

<?php

namespace App\Http\Controllers;

use App\FormaPagamento;
use App\Marca;
use App\Regiao;
use App\Segmento;
use App\TabelaPreco;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Validator;
use App\Cliente;
use App\PessoaJuridica;
use App\PessoaFisica;
use App\Contato;
use Illuminate\Http\Request;

use App\Http\Requests;

class ClientesController extends Controller
{
    private $Page;
    private $colaborador;

    private $Validation = [
        'pessoa_juridica' => array(
            'razao_social'              => 'required',
            'nome_fantasia'             => 'required',
        )
    ];

    public function __construct()
    {
        $this->colaborador = Auth::user()->colaborador;
        $this->Page = (object)[
            'link'              => "clientes",
            'Target'            => "Cliente",
            'Targets'           => "Clientes",
            'Titulo'            => "Clientes",
            'titulo_primario'   => "",
            'titulo_secundario' => "",
            'search_no_results' => "Nenhum cliente encontrado!",
            'extras'            => NULL,
        ];
    }

    public function index(Request $request)
    {
        if (isset($request['busca'])) {
            $busca = $request['busca'];
            $Buscas = Cliente::getAll($busca)->paginate(10);
        } else {
            $Buscas = Cliente::paginate(10);
        }

        return view('pages.'.$this->Page->link.'.index')
            ->with('Page', $this->Page)
            ->with('Buscas',$Buscas);
    }

    public function create()
    {
        $this->Page->titulo_primario    = "Cadastrar ";
        $this->Page->titulo_secundario  = "Dados do Cliente";
        $this->Page->extras = [
            'centro_custo'      => Cliente::all(),
            'segmentos'         => Segmento::all(),
            'regioes'           => Regiao::all(),
            'tabela_precos'     => TabelaPreco::all(),
            'formas_pagamentos' => FormaPagamento::all(),
        ];
        return view('pages.'.$this->Page->link.'.master')
            ->with('Page', $this->Page);
    }

    public function show($id,$tab='sobre')
    {
        $this->Page->titulo_primario = "Visualização de ";
        $this->Page->tab = $tab;
        $this->Page->extras = [
            'centro_custo'  => Cliente::all(),
            'segmentos'     => Segmento::all(),
            'regioes'       => Regiao::all(),
            'tabela_precos' => TabelaPreco::all(),
            'marcas'        => Marca::all(),
            'formas_pagamentos' => FormaPagamento::all(),
        ];
        $Cliente = Cliente::find($id);
        return view('pages.'.$this->Page->link.'.show')
            ->with('Cliente', $Cliente)
            ->with('Page', $this->Page);
    }

    public function store(Request $request)
    {
        //cliente
        $validacao = [
            'centro_custo'      => 'required',
            'idforma_pagamento' => 'required',
            'email_orcamento'   => 'required',
//            'email_nota'        => 'required',
            'idsegmento'        => 'required',
            'idtabela_preco'    => 'required',
            'idregiao'          => 'required',
            'distancia'         => 'required',
            'pedagios'          => 'required',
            'outros_custos'     => 'required',
        ];
        if($request->get('centro_custo')=='0'){
            $validacao = array_merge($validacao,[
                'limite_credito'    => 'required'
            ]);
        }

        $data = $request->all();
        if($request->get('tipo_cliente')=="0"){
            //pessoa física
            $validacao = array_merge($validacao,[
                    'cpf' => 'unique:pfisicas',
                ]);
        } else {

            //pessoa juridica, teste da isenção IE
            if($request->has('isencao_ie')){
                $data['isencao_ie'] = 1;
                $validacao = array_merge($validacao,[
                    'cnpj'  => 'unique:pjuridicas',
                ]);
            } else {
                $validacao = array_merge($validacao,[
                    'cnpj'  => 'unique:pjuridicas',
                    'ie'    => 'unique:pjuridicas',
                ]);
            }

            $validacao = array_merge($validacao,$this->Validation['pessoa_juridica']);

        }
        $validator = Validator::make($request->all(), $validacao);

        if ($validator->fails()) {
            return redirect()->to($this->getRedirectUrl())
                ->withErrors($validator)
                ->withInput($request->all());
//                ->withInput();
        } else {

            $data['idcolaborador_criador'] = $this->colaborador->idcolaborador;
            if (!$this->colaborador->hasRole('tecnico')) {
                $data['idcolaborador_validador'] = $this->colaborador->idcolaborador;
                $data['validated_at'] = Carbon::now()->toDateTimeString();
            }
            //store CONTATO
            $Contato = Contato::create($data);
            $data['idcontato'] = $Contato->idcontato;

//            return $data;
            if($data['tipo_cliente']=="0"){
                //store física
                $TipoCliente = PessoaFisica::create($data);
                $data['idpfisica'] = $TipoCliente->idpfisica;
            } else {
                //store juridica
                $TipoCliente = PessoaJuridica::create($data);
                $data['idpjuridica'] = $TipoCliente->idpjuridica;
            }
            if($request->hasfile('foto')){
                $img = new ImageController();
                $data['foto'] = $img->store($request->file('foto'), $this->Page->link);
            } else {
                $data['foto'] = NULL;
            }

            //store Cliente
            if($data['centro_custo']!='0'){
                $data['idcliente_centro_custo'] = ($data['idcliente_centro_custo']=='')?NULL:$data['idcliente_centro_custo'];
                $data['limite_credito']=NULL;
            } else {
                $data['idcliente_centro_custo']=NULL;
            }


            $Cliente = Cliente::create($data);

            session()->forget('mensagem');
            session(['mensagem' => $this->Page->Target.' adicionado com sucesso!']);
            return redirect()->route('clientes.show', $Cliente->idcliente);
        }
    }

    public function update(Request $request, $id)
    {
        $Cliente = Cliente::find($id);
        //cliente
        $validacao = [
            'centro_custo'      => 'required',
            'idforma_pagamento' => 'required',
            'email_orcamento'   => 'required',
//            'email_nota'        => 'required',
            'idsegmento'        => 'required',
            'idtabela_preco'    => 'required',
            'idregiao'          => 'required',
            'distancia'         => 'required',
            'pedagios'          => 'required',
            'outros_custos'     => 'required',
        ];

        if($request->get('centro_custo')=='0'){
            $validacao = array_merge($validacao,[
                'limite_credito'    => 'required'
            ]);
        }

        $dataUpdate = $request->all();
        if($Cliente->getType()->tipo_cliente==0){
            //pessoa física
            $idpfisica = $Cliente->pessoa_fisica->idpfisica;
            $validacao = array_merge($validacao,[
                'cpf'               => 'unique:pfisicas,cpf,'.$idpfisica.',idpfisica',
            ]);
        } else {
            //pessoa juridica, teste da isenção IE
            $idpjuridica = $Cliente->pessoa_juridica->idpjuridica;
            if($request->has('isencao_ie')){
                $data['isencao_ie'] = 1;
                $validacao = array_merge($validacao,[
                    'cnpj'  => 'unique:pjuridicas,cnpj,'.$idpjuridica.',idpjuridica'
                ]);
            } else {
                $validacao = array_merge($validacao,[
                    'cnpj'  => 'unique:pjuridicas,cnpj,'.$idpjuridica.',idpjuridica',
                    'ie'    => 'unique:pjuridicas,ie,'.$idpjuridica.',idpjuridica',
                ]);
            }
            $validacao = array_merge($validacao,$this->Validation['pessoa_juridica']);
        }
        $validator = Validator::make($request->all(), $validacao);

        if ($validator->fails()) {
            return redirect()->to($this->getRedirectUrl())
                ->withErrors($validator)
                ->withInput($request->all());
//                ->withInput();
        } else {

//            return $validacao;
            if($Cliente->getType()->tipo_cliente==0){
                //update física
                $Cliente->pessoa_fisica->update($dataUpdate);
            } else {
                //update juridica
                $Cliente->pessoa_juridica->update($dataUpdate);
            }

            //update foto
            if($request->hasfile('foto')){
                $img = new ImageController();
                $dataUpdate['foto'] = $img->store($request->file('foto'), $this->Page->link);
            }

            //update CLIENTE
            if($dataUpdate['centro_custo']!='0'){
                $dataUpdate['idcliente_centro_custo'] = ($dataUpdate['idcliente_centro_custo']=='')?NULL:$dataUpdate['idcliente_centro_custo'];
                $dataUpdate['limite_credito']=NULL;
            } else {
                $dataUpdate['idcliente_centro_custo']=NULL;
            }

            $Cliente->update($dataUpdate);
            //update CONTATO
            $Cliente->contato->update($dataUpdate);

            session()->forget('mensagem');
            session(['mensagem' => ($this->Page->Target.' atualizado com sucesso!')]);
            return redirect()->route('clientes.show', $Cliente->idcliente);
        }
    }

    public function destroy($id)
    {
        $data = Cliente::find($id);
        $data->delete();
        return response()->json(['status' => '1',
            'response' => $this->Page->msg_rem]);
    }

    public function validar($id)
    {
        $Cliente = Cliente::find($id);
        if ($this->colaborador->hasRole('admin')) {
            $data['idcolaborador_validador'] = $this->colaborador->idcolaborador;
            $data['validated_at'] = Carbon::now()->toDateTimeString();
            $Cliente->update($data);
            session()->forget('mensagem');
            session(['mensagem' => ($this->Page->Target . ' atualizado com sucesso!')]);
        }
        return redirect()->route('clientes.show', $id);
    }
}

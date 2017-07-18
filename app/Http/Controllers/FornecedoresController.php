<?php

namespace App\Http\Controllers;

use App\Models\Ajustes\RecursosHumanos\Fornecedores\SegmentoFornecedor;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Contato;
use App\PessoaFisica;
use App\PessoaJuridica;
use App\Fornecedor;
use Illuminate\Http\Request;

use App\Http\Requests;

class FornecedoresController extends Controller
{
    private $Page;
    private $Validation = [
        'pessoa_juridica' => array(
            'razao_social'              => 'required',
            'nome_fantasia'             => 'required',
            'ativ_economica'            => 'required',
            'sit_cad_vigente'           => 'required',
            'sit_cad_status'            => 'required',
            'data_sit_cad'              => 'required',
            'reg_apuracao'              => 'required',
            'data_credenciamento'       => 'required',
            'ind_obrigatoriedade'       => 'required',
            'data_ini_obrigatoriedade'  => 'required',
        )
    ];

    public function __construct()
    {
        /*
        $this->middleware('role:empresa');
        if(Auth::check()){
            $this->empresa_id = (Auth::user()->empresa == "")?'*':Auth::user()->empresa->EMP_ID;
            $this->Empresa = (Auth::user()->empresa == "")?'*':Auth::user()->empresa;
        }
        */
        $this->idcolaborador = Auth::user()->colaborador->idcolaborador;
        $this->Page = (object)[
            'link'              => "fornecedores",
            'Target'            => "Fornecedor",
            'Targets'           => "Fornecedores",
            'Titulo'            => "Fornecedores",
            'titulo_primario'   => "",
            'titulo_secundario' => "",
            'search_no_results' => "Nenhum Fornecedor encontrado!",
            'extras'            => NULL,
        ];
    }

    public function index(Request $request)
    {
//        print_r(Auth::user());
//        return $this->empresa_id;
        /*
        $titulo = "Busca de ";
        if(isset($request['busca'])){
            $busca = $request['busca'];
            $Buscas = Cliente::where('nome', 'like', '%'.$busca.'%')
                ->orwhere('cpf', 'like', '%'.$busca.'%')
                ->orwhere('rg', 'like', '%'.$busca.'%')
                ->orwhere('email', 'like', '%'.$busca.'%')
                ->get();
        } else {
            $Buscas = Paciente::all();
        }

        */
        $Buscas = Fornecedor::paginate(10);
        return view('pages.'.$this->Page->link.'.index')
            ->with('Page', $this->Page)
            ->with('Buscas',$Buscas);
    }

    public function create()
    {
        $this->Page->titulo_primario    = "Cadastrar ";
        $this->Page->titulo_secundario  = "Dados do ".$this->Page->Target;
        $this->Page->extras = [
            'segmentos_fornecedores'    => SegmentoFornecedor::all()
        ];
        return view('pages.'.$this->Page->link.'.master')
            ->with('Page', $this->Page);
    }

    public function store(Request $request)
    {
        //fornecedor
        $validacao = [
            'idsegmento_fornecedor' => 'required',
            'email_orcamento'       => 'required',
        ];

        $data = $request->all();
        if($request->get('tipo_fornecedor')=="0"){
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

            //store CONTATO
            $Contato = Contato::create($data);
            $data['idcontato'] = $Contato->idcontato;

//            return $data;
            if($data['tipo_fornecedor']=="0"){
                //store física
                $TipoCliente = PessoaFisica::create($data);
                $data['idpfisica'] = $TipoCliente->idpfisica;
            } else {
                //store juridica
                $TipoCliente = PessoaJuridica::create($data);
                $data['idpjuridica'] = $TipoCliente->idpjuridica;
            }

            //store Fornecedor
            $data['idcolaborador_criador']=$this->idcolaborador;
            $data['idcolaborador_validador']=$this->idcolaborador;
            $data['validated_at']=Carbon::now()->toDateTimeString();
            $Fornecedor = Fornecedor::create($data);

            session()->forget('mensagem');
            session(['mensagem' => $this->Page->Target.' adicionado com sucesso!']);
            return $this->show($Fornecedor->idfornecedor);
        }
    }

    public function show($id, $tab = 'sobre')
    {
        $this->Page->extras = [
            'segmentos_fornecedores' => SegmentoFornecedor::all(),
        ];

        $this->Page->titulo_primario = "Visualização de ";
        $this->Page->tab = $tab;
        $Fornecedor = Fornecedor::find($id);
        return view('pages.' . $this->Page->link . '.show')
            ->with('Fornecedor', $Fornecedor)
            ->with('Page', $this->Page);
    }

    public function update(Request $request, $id)
    {

        $Fornecedor = Fornecedor::find($id);

        //fornecedor
        $validacao = [
            'idsegmento_fornecedor' => 'required',
            'email_orcamento'       => 'required',
            'grupo'                 => 'required',
        ];

        $dataUpdate = $request->all();
        if($Fornecedor->getType()->tipo_fornecedor==0){
            //pessoa física
            $idpfisica = $Fornecedor->pessoa_fisica->idpfisica;
            $validacao = array_merge($validacao,[
                'cpf' => 'unique:pfisicas,cpf,'.$idpfisica.',idpfisica',
            ]);
        } else {

            //pessoa juridica, teste da isenção IE
            $idpjuridica = $Fornecedor->pessoa_juridica->idpjuridica;
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
            $dataUpdate = $request->all();

            if($Fornecedor->getType()->tipo_fornecedor==0){
                //update física
                $Fornecedor->pessoa_fisica->update($dataUpdate);
            } else {
                //update juridica
                $Fornecedor->pessoa_juridica->update($dataUpdate);
            }

            //update Fornecedor
            $Fornecedor->update($dataUpdate);
            //update CONTATO
            $Fornecedor->contato->update($dataUpdate);

            session()->forget('mensagem');
            session(['mensagem' => $this->Page->Target.' atualizado com sucesso!']);
            return $this->show($Fornecedor->idfornecedor);
        }
    }

    public function destroy($id)
    {
        $data = Fornecedor::find($id);
        $data->delete();
        return response()->json(['status' => '1',
            'response' => $this->Page->msg_rem]);
    }
}

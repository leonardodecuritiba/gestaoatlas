<?php

namespace App\Http\Controllers;

use App\Colaborador;
use App\Lacre;
use App\Role;
use App\Selo;
use App\User;
use App\Contato;
use App\Tecnico;
use Illuminate\Support\Facades\Redirect;
use Validator;
use Illuminate\Http\Request;

use App\Http\Requests;

class ColaboradoresController extends Controller
{
    private $Page;

//    private $colaborador;

    public function __construct()
    {
        /*
        $this->middleware('role:empresa');
        if(Auth::check()){
            $this->empresa_id = (Auth::user()->empresa == "")?'*':Auth::user()->empresa->EMP_ID;
            $this->Empresa = (Auth::user()->empresa == "")?'*':Auth::user()->empresa;
        }
        */

//        $this->colaborador = Auth::user()->colaborador;
        $this->Page = (object)[
            'link'              => "colaboradores",
            'Target'            => "Colaborador",
            'Targets'           => "Colaboradores",
            'Titulo'            => "Colaboradores",
            'titulo_primario'   => "",
            'titulo_secundario' => "",
            'search_no_results' => "Nenhum Colaborador encontrado!",
            'extras'            => NULL,
        ];
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /*
        if(isset($request['busca'])){
            $busca = $request['busca'];
            $Buscas = Paciente::where('nome', 'like', '%'.$busca.'%')
                ->orwhere('cpf', 'like', '%'.$busca.'%')
                ->orwhere('rg', 'like', '%'.$busca.'%')
                ->orwhere('email', 'like', '%'.$busca.'%')
                ->get();
        } else {
            $Buscas = Paciente::all();
        }
        */

        $Buscas = Colaborador::paginate(10);
        return view('pages.'.$this->Page->link.'.index')
            ->with('Page', $this->Page)
            ->with('Buscas',$Buscas);
    }

    public function create()
    {
        $this->Page->extras  = [
            "Role" => Role::all()
        ];
        $this->Page->titulo_primario    = "Cadastrar ";
        $this->Page->titulo_secundario  = "Dados Pessoais";
        return view('pages.'.$this->Page->link.'.master')
            ->with('Page', $this->Page);
    }

    public function store(Request $request)
    {
        //colaborador
//        return $request->all();
        $role = Role::find($request->get('tipo_cadastro'));
        $validacao = [
            'email'             => 'email',
            'nome'              => 'required',
            'cpf'               => 'unique:colaboradores',
            'rg'                => 'unique:colaboradores',
            'data_nascimento'   => 'required',
            'cnh'               => 'image',
            'carteira_trabalho' => 'image',
        ];
        if($role->name == 'tecnico'){
            $validacao = array_merge($validacao,[
                'carteira_imetro' => 'image',
                'carteira_ipem'   => 'image',
            ]);
        }

        $validator = Validator::make($request->all(), $validacao);

        if ($validator->fails()) {
            return redirect()->to($this->getRedirectUrl())
                ->withErrors($validator)
                ->withInput($request->all());
//                ->withInput();
        } else {
            $data = $request->all();

            //store CONTATO
            $Contato = Contato::create($data);
            $data['idcontato'] = $Contato->idcontato;

            //store USER
            $data['remember_token'] = str_random(60);
            $data['password'] = bcrypt('123');
            $User = User::create($data);
            $User->attachRole($role);
            $data['iduser'] = $User->iduser;

            foreach(['cnh','carteira_trabalho'] as $doc){
                if($request->hasfile($doc)){
                    $img = new ImageController();
                    $data[$doc] = $img->store($request->file($doc), $this->Page->link);
                } else {
                    $data[$doc] = NULL;
                }
            }

            $Colaborador = Colaborador::create($data);

            if($role->name == 'tecnico'){
                $data['idcolaborador'] = $Colaborador->idcolaborador;
                foreach(['carteira_imetro','carteira_ipem'] as $doc){
                    if($request->hasfile($doc)){
                        $img = new ImageController();
                        $data[$doc] = $img->store($request->file($doc), 'tecnicos');
                    } else {
                        $data[$doc] = NULL;
                    }
                }
                $Tecnico = Tecnico::create($data);
            }

            session()->forget('mensagem');
            session(['mensagem' => $this->Page->Target.' adicionado com sucesso!']);
            return $this->show($Colaborador->idcolaborador);
        }
    }

    public function show($id, $tab = 'sobre')
    {
        $this->Page->titulo_primario = "Visualização de ";
        $this->Page->tab = $tab;
        $Colaborador = Colaborador::find($id);
        if ($Colaborador->hasRole('tecnico')) {
            $this->Page->extras['tecnicos'] = Tecnico::outros($Colaborador->tecnico->idtecnico);
        }
        return view('pages.' . $this->Page->link . '.show')
            ->with('Colaborador', $Colaborador)
            ->with('Page', $this->Page);
    }

    public function selolacre_remanejar(Request $request)
    {
        return 'AINDA NÃO ESTÁ FUNCIONAL';
        return $request->all();
        $ini = $request->get('numeracao_inicial');
        $end = $request->get('numeracao_final');
        $qtd = $end - $ini;
        if ($request->get('opcao') == 'selo') {
            if (Selo::where('numeracao', $ini)->count() > 0) {
                $erros = 'Já existem Selos com essa numeração';
            } else {
                for ($i = $ini; $i <= $end; $i++) {
                    $data[] = [
                        'idtecnico' => $idtecnico,
                        'numeracao' => $i,
                    ];
                }
                Selo::insert($data);
            }
        } else {
            if (Lacre::where('numeracao', $ini)->count() > 0) {
                $erros = 'Já existem Lacres com essa numeração';
            } else {
                for ($i = $ini; $i <= $end; $i++) {
                    $data[] = [
                        'idtecnico' => $idtecnico,
                        'numeracao' => $i,
                    ];
                }
                Lacre::insert($data);
            }
        }

        if (isset($erros)) {
            return redirect()->back()
                ->withErrors($erros)
                ->withInput($request->all());
        }

        $msg = 'Foram adicionados ' . $qtd . ' ' . $request->get('opcao') . 's!';
        session()->forget('mensagem');
        session(['mensagem' => $msg]);
        return redirect()->route('colaboradores.show', ['idcolaborador' => Tecnico::find($idtecnico)->idcolaborador]);

    }
    public function selolacre_store(Request $request, $idtecnico)
    {
        $ini = $request->get('numeracao_inicial');
        $end = $request->get('numeracao_final');
        $qtd = $end - $ini;
        if($request->get('opcao') == 'selo'){
            if(Selo::where('numeracao',$ini)->count()>0){
                $erros = 'Já existem Selos com essa numeração';
            } else {
                for($i = $ini; $i <= $end; $i++){
                    $data[] = [
                        'idtecnico' => $idtecnico,
                        'numeracao' => $i,
                    ];
                }
                Selo::insert($data);
            }
        } else {
            if(Lacre::where('numeracao',$ini)->count()>0){
                $erros = 'Já existem Lacres com essa numeração';
            } else {
                for($i = $ini; $i <= $end; $i++){
                    $data[] = [
                        'idtecnico' => $idtecnico,
                        'numeracao' => $i,
                    ];
                }
                Lacre::insert($data);
            }
        }

        if(isset($erros)){
            return redirect()->back()
                ->withErrors($erros)
                ->withInput($request->all());
        }

        $msg = 'Foram adicionados '.$qtd.' '.$request->get('opcao').'s!';
        session()->forget('mensagem');
        session(['mensagem' => $msg]);
        return redirect()->route('colaboradores.show', ['idcolaborador' => Tecnico::find($idtecnico)->idcolaborador]);

    }

    public function upd_pass(Request $request, $id)
    {
        $validator = Validator::make($request->all(), ['password' => 'required|confirmed|min:6|max:20']);
        if ($validator->fails()) {
            return redirect()->to($this->getRedirectUrl())
                ->withErrors($validator)
                ->withInput($request->all());
        } else {
            $Colaborador = Colaborador::find($id);
            $Colaborador->user->update([
                'password' => bcrypt($request->get('password'))
            ]);
            session()->forget('mensagem');
            session(['mensagem' => $this->Page->Target . ' atualizado com sucesso!']);
            return redirect()->route('colaboradores.show', ['idcolaborador' => $Colaborador->idcolaborador]);
        }
    }

    public function update(Request $request, $id)
    {
        //colaborador
//        return $request->all();
        $Colaborador = Colaborador::find($id);
        $validacao = [
            'email'             => 'email',
            'nome'              => 'required',
            'cpf'               => 'unique:colaboradores,cpf,'.$id.',idcolaborador',
            'rg'                => 'unique:colaboradores,rg,'.$id.',idcolaborador',
            'data_nascimento'   => 'required',
            'cnh'               => 'image',
            'carteira_trabalho' => 'image',
        ];
        if ($Colaborador->hasRole('tecnico')) {
            $validacao = array_merge($validacao,[
                'carteira_imetro' => 'image',
                'carteira_ipem'   => 'image',
            ]);
        }

        $validator = Validator::make($request->all(), $validacao);

        if ($validator->fails()) {
            return redirect()->to($this->getRedirectUrl())
                ->withErrors($validator)
                ->withInput($request->all());
        } else {
            $dataUpdate = $request->all();

//            return $Colaborador;
            //update CONTATO
            $Colaborador->contato->update($dataUpdate);

            //store USER
            $Colaborador->user->update($dataUpdate);

            foreach(['cnh','carteira_trabalho'] as $doc){
                if ($request->hasfile($doc)) {
                    $img = new ImageController();
                    $dataUpdate[$doc] = $img->store($request->file($doc), $this->Page->link);
                }
            }
            $Colaborador->update($dataUpdate);

//            return $Colaborador;
            if ($Colaborador->hasRole('tecnico')) {
                foreach(['carteira_imetro','carteira_ipem'] as $doc){
                    if($request->hasfile($doc)){
                        $img = new ImageController();
                        $dataUpdate[$doc] = $img->store($request->file($doc), 'tecnicos');
                    } else {
                        $dataUpdate[$doc] = NULL;
                    }
                }
                $Colaborador->tecnico->update($dataUpdate);
            }

            session()->forget('mensagem');
            session(['mensagem' => $this->Page->Target.' adicionado com sucesso!']);
            return Redirect::route('colaboradores.show', $Colaborador->idcolaborador);
        }
    }

    public function destroy($id)
    {
        $data = Colaborador::find($id);
        $data->delete();
        return response()->json(['status' => '1',
            'response' => $this->Page->msg_rem]);
    }
}

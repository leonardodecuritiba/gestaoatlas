<?php

namespace App\Http\Controllers;

use App\Helpers\DataHelper;
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

    public function show($id)
    {
        $this->Page->titulo_primario = "Visualização de ";
        $Serviço = Servico::find($id);
        return view('pages.' . $this->Page->link . '.show')
            ->with('Servico', $Serviço)
            ->with('Page', $this->Page);
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

//            return $dataUpdate;
            //ATUALIZANDO OS PREÇOS E MARGENS
            $margens = $request->get('margem');
            $margem_minimos = $request->get('margem_minimo');
            $custo_final = $Serviço->valor_float();
            foreach ($Serviço->tabela_preco as $tabela_preco) {
                $margem = DataHelper::getPercent2Float($margens[$tabela_preco->idtabela_preco]);
                $margem_minimo = DataHelper::getPercent2Float($margem_minimos[$tabela_preco->idtabela_preco]);

                $dataUpd = [
                    'preco' => $custo_final + ($custo_final * $margem) / 100,
                    'margem' => $margem,
                    'preco_minimo' => $custo_final + ($custo_final * $margem_minimo) / 100,
                    'margem_minimo' => $margem_minimo,
                ];
                $tabela_preco->update($dataUpd);
            }

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

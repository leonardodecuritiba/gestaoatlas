<?php

namespace App\Http\Controllers;

use App\Kit;
use App\Peca;
use App\PecaKit;
use App\Unidade;
use Validator;
use Illuminate\Http\Request;

use App\Http\Requests;

class KitsController extends Controller
{
    private $Page;

    public function __construct()
    {
        $this->Page = (object)[
            'table'             => "kits",
            'link'              => "kits",
            'primaryKey'        => "idkit",
            'Target'            => "Kit",
            'Targets'           => "Kits",
            'Titulo'            => "Kits",
            'search_no_results' => "Nenhum kit encontrado!",
            'msg_add'           => 'Kit adicionado com sucesso!',
            'msg_upd'           => 'Kit atualizado com sucesso!',
            'msg_rem'           => 'Kit removido com sucesso!',
            'msg_rem_1'         => 'Item removido com sucesso!',
            'msg_rem_2'         => 'O kit deve conter ao menos um item!',
            'titulo_primario'   => "",
            'titulo_secundario' => "",
        ];
    }
    public function index(Request $request)
    {
        if(isset($request['busca'])){
            $busca = $request['busca'];
            $Buscas = Kit::where('nome', 'like', '%'.$busca.'%')
                ->orWhere('descricao', 'like', '%'.$busca.'%')
                ->orWhere('observacao', 'like', '%'.$busca.'%')
                ->paginate(10);
        } else {
            $Buscas = Kit::paginate(10);
        }
        return view('pages.'.$this->Page->link.'.index')
            ->with('Page', $this->Page)
            ->with('Buscas',$Buscas);
    }

    public function create()
    {
        $this->Page->titulo_primario    = "Cadastrar ";
        $this->Page->titulo_secundario  = "Dados do ".$this->Page->Target;
        $this->Page->extras = [
            'pecas'             => Peca::all()
        ];
        return view('pages.'.$this->Page->link.'.master')
            ->with('Page', $this->Page);
    }

    public function show($id)
    {
        $this->Page->titulo_primario = "Visualização de ";
        $this->Page->titulo_secundario  = "Dados do ".$this->Page->Target;
        $Kit = Kit::find($id);
        $this->Page->extras = [
            'pecas'             => Peca::all()
        ];
        return view('pages.'.$this->Page->link.'.show')
            ->with('Kit', $Kit)
            ->with('Page', $this->Page);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nome'          => 'required|unique:'.$this->Page->table,
            'descricao'     => 'required',
            'idpeca'        => 'required',
            'quantidade'    => 'required',
            'valor_unidade' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->to($this->getRedirectUrl())
                ->withErrors($validator)
                ->withInput($request->all());
        } else {
            //fazer varredura nas peças
            $data = $request->all();
            $Kit = Kit::create($data);

            foreach($data["idpeca"] as $i => $idpeca){
                $qtd = $data["quantidade"][$i];
                $val = floatval(str_replace(',','.',$data["valor_unidade"][$i]));
                PecaKit::create([
                    'idkit'                 => $Kit->idkit,
                    'idpeca'                => $data["idpeca"][$i],
                    'quantidade'            => $qtd,
                    'valor_unidade'         => $val,
                    'valor_total'           => $qtd*$val,
                    'descricao_adicional'   => $data["descricao_adicional"][$i],
                ]);
            }
            session()->forget('mensagem');
            session(['mensagem' => $this->Page->msg_add]);
            return $this->show($Kit->idkit);
        }
    }

    public function update(Request $request, $id)
    {
        $Kit = Kit::find($id);
        $validator = Validator::make($request->all(), [
            'nome'      => 'unique:'.$this->Page->table.',nome,'.$id.','.$this->Page->primaryKey,
            'descricao'     => 'required',
            'idpeca'        => 'required',
            'quantidade'    => 'required',
            'valor_unidade' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->to($this->getRedirectUrl())
                ->withErrors($validator)
                ->withInput($request->all());
        } else {
            //fazer varredura nas peças
            $dataUpdate = $request->all();
//            return $dataUpdate;
            $Kit->update($dataUpdate);

            foreach($dataUpdate["idpeca"] as $i => $idpeca){
                if(!isset($dataUpdate["idpecas_kit"][$i])){
                    $dados = [
                        'idkit'         => $Kit->idkit,
                        'idpeca'        => $dataUpdate["idpeca"][$i],
                        'quantidade'    => $dataUpdate["quantidade"][$i],
                        'valor_unidade' => floatval(str_replace(',','.',$dataUpdate["valor_unidade"][$i])),
                        'descricao_adicional' => $dataUpdate["descricao_adicional"][$i],
                    ];
                    PecaKit::create($dados);
                } else {
                    $peca_kit = PecaKit::find($dataUpdate["idpecas_kit"][$i]);
                    $dados = [
                        'idpeca'        => $dataUpdate["idpeca"][$i],
                        'quantidade'    => $dataUpdate["quantidade"][$i],
                        'valor_unidade' => floatval(str_replace(',','.',$dataUpdate["valor_unidade"][$i])),
                        'descricao_adicional' => $dataUpdate["descricao_adicional"][$i],
                    ];
                    $peca_kit->update($dados);
                }
            }

            session()->forget('mensagem');
            session(['mensagem' => $this->Page->msg_upd]);
            return $this->show($Kit->idkit);
        }

    }

    public function destroy($id)
    {
        $data = Kit::find($id);
        $data->delete();
        return response()->json(['status' => '1',
            'response' => $this->Page->msg_rem]);
    }
    public function pecaKitDestroy($id)
    {
        $item = PecaKit::find($id);
        if($item->kit->pecas_kit()->count() == 1){ //caso só tenha um item no kit, nao remover
            return response()->json(['status' => '0',
                'response' => $this->Page->msg_rem_2]);
        } else {
            $item->delete();
            return response()->json(['status' => '1',
                'response' => $this->Page->msg_rem_1]);
        }

    }
}

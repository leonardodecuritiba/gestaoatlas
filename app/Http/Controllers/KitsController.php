<?php

namespace App\Http\Controllers;

use App\Helpers\DataHelper;
use App\Kit;
use App\Peca;
use App\PecaKit;
use App\TabelaPreco;
use App\TabelaPrecoKit;
use App\Unidade;
use Illuminate\Support\Facades\Redirect;
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
            'pecas' => Peca::all(),
            'tabela_preco' => TabelaPreco::all(),
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
//            return $data;
            $Kit = Kit::create($data);

            foreach($data["idpeca"] as $i => $idpeca){
                $qtd = $data["quantidade"][$i];
                $valor_unidade = DataHelper::getReal2Float($data["valor_unidade"][$i]);
                $valor_total = $qtd * $valor_unidade;
                $dados = [
                    'idkit'                 => $Kit->idkit,
                    'idpeca'                => $data["idpeca"][$i],
                    'quantidade'            => $qtd,
                    'valor_unidade' => $valor_unidade,
                    'valor_total' => $valor_total,
                    'descricao_adicional'   => $data["descricao_adicional"][$i],
                ];
                PecaKit::create($dados);
            }

            $margem_minimo = 10;
            $margem = $margem_minimo + 5;
            $Tabelas_preco = TabelaPreco::all();
            foreach ($Tabelas_preco as $tabela_preco) {
                $valor = $Kit->valor_total_float();
                $preco = $valor + ($margem * $valor) / 100;
                $preco_minimo = $valor + ($margem_minimo * $valor) / 100;
                $dados = [
                    'idtabela_preco' => $tabela_preco->idtabela_preco,
                    'idkit' => $Kit->idkit,
                    'margem' => $margem,
                    'preco' => $preco,
                    'margem_minimo' => $margem_minimo,
                    'preco_minimo' => $preco_minimo,
                ];
                TabelaPrecoKit::create($dados);
            }

            session()->forget('mensagem');
            session(['mensagem' => $this->Page->msg_add]);
            return Redirect::route('kits.show', $Kit->idkit);
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
                $qtd = $dataUpdate["quantidade"][$i];
                $valor_unidade = DataHelper::getReal2Float($dataUpdate["valor_unidade"][$i]);
                $valor_total = $qtd * $valor_unidade;
                if(!isset($dataUpdate["idpecas_kit"][$i])){
                    $dados = [
                        'idkit' => $Kit->idkit,
                        'idpeca' => $dataUpdate["idpeca"][$i],
                        'quantidade' => $qtd,
                        'valor_unidade' => $valor_unidade,
                        'valor_total' => $valor_total,
                        'descricao_adicional' => $dataUpdate["descricao_adicional"][$i],
                    ];
                    PecaKit::create($dados);
                } else {
                    $peca_kit = PecaKit::find($dataUpdate["idpecas_kit"][$i]);
                    $dados = [
                        'idpeca' => $dataUpdate["idpeca"][$i],
                        'quantidade' => $qtd,
                        'valor_unidade' => $valor_unidade,
                        'valor_total' => $valor_total,
                        'descricao_adicional' => $dataUpdate["descricao_adicional"][$i],
                    ];
//                    print_r($dados);
                    $peca_kit->update($dados);
                }
            }

            //ATUALIZANDO OS PREÇOS E MARGENS
            $dados = [
                'margens' => $request->get('margem'),
                'margem_minimo' => $request->get('margem_minimo'),
                'valor' => $Kit->valor_total_float(),
            ];
            $Tabelas_preco = $Kit->tabela_preco;
            DataHelper::updatePriceTable($request, $Tabelas_preco);

            session()->forget('mensagem');
            session(['mensagem' => $this->Page->msg_upd]);
            return Redirect::route('kits.show', $Kit->idkit);
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

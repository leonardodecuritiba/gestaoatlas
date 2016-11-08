<?php

namespace App\Http\Controllers;

use App\SegmentoFornecedor;
use Validator;
use Illuminate\Http\Request;

use App\Http\Requests;

class SegmentosFornecedoresController extends Controller
{
    private $Page;

    public function __construct()
    {
        $this->Page = (object)[
            'table'             => "segmentos_fornecedores",
            'link'              => "segmentos_fornecedores",
            'primaryKey'        => "idsegmento_fornecedor",
            'Target'            => "Segmento do Fornecedor",
            'Targets'           => "Segmentos dos Fornecedores",
            'Titulo'            => "Segmentos dos Fornecedores",
            'search_no_results' => "Nenhum segmento encontrado!",
            'msg_add'           => 'Segmento do Fornecedor adicionado com sucesso!',
            'msg_upd'           => 'Segmento do Fornecedor atualizado com sucesso!',
            'msg_rem'           => 'Segmento do Fornecedor removido com sucesso!',
            'titulo_primario'   => "",
            'titulo_secundario' => "",
        ];
    }
    public function index(Request $request)
    {
        if(isset($request['busca'])){
            $busca = $request['busca'];
            $Buscas = SegmentoFornecedor::where('descricao', 'like', '%'.$busca.'%')
                ->paginate(10);
        } else {
            $Buscas = SegmentoFornecedor::paginate(10);
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
        $SegmentoFornecedor = SegmentoFornecedor::find($id);
        return view('pages.'.$this->Page->link.'.show')
            ->with('SegmentoFornecedor', $SegmentoFornecedor)
            ->with('Page', $this->Page);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'descricao'         => 'required|unique:'.$this->Page->table,
        ]);
        if ($validator->fails()) {
            return redirect()->to($this->getRedirectUrl())
                ->withErrors($validator)
                ->withInput($request->all());
        } else {
            $data = $request->all();
            $SegmentoFornecedor = SegmentoFornecedor::create($data);
            session()->forget('mensagem');
            session(['mensagem' => $this->Page->msg_add]);
            return $this->show($SegmentoFornecedor->idsegmento_fornecedor);
        }
    }

    public function update(Request $request, $id)
    {
        $SegmentoFornecedor = SegmentoFornecedor::find($id);
        $validator = Validator::make($request->all(), [
            'descricao' => 'unique:'.$this->Page->table.',descricao,'.$id.','.$this->Page->primaryKey,
        ]);

        if ($validator->fails()) {
            return redirect()->to($this->getRedirectUrl())
                ->withErrors($validator)
                ->withInput($request->all());
        } else {
            $dataUpdate = $request->all();
            $SegmentoFornecedor->update($dataUpdate);

            session()->forget('mensagem');
            session(['mensagem' => $this->Page->msg_upd]);
            return $this->show($SegmentoFornecedor->idsegmento_fornecedor);
        }
    }

    public function destroy($id)
    {
        $data = SegmentoFornecedor::find($id);
        $data->delete();
        return response()->json(['status' => '1',
            'response' => $this->Page->msg_rem]);
    }
}

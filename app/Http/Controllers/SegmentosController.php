<?php

namespace App\Http\Controllers;

use App\Segmento;
use Validator;
use Illuminate\Http\Request;

use App\Http\Requests;

class SegmentosController extends Controller
{
    private $Page;

    public function __construct()
    {
        $this->Page = (object)[
            'table'             => "segmentos",
            'link'              => "segmentos",
            'primaryKey'        => "idsegmento",
            'Target'            => "Segmento",
            'Targets'           => "Segmentos",
            'Titulo'            => "Segmentos",
            'search_no_results' => "Nenhum segmento encontrado!",
            'msg_add'           => 'Segmento adicionado com sucesso!',
            'msg_upd'           => 'Segmento atualizado com sucesso!',
            'msg_rem'           => 'Segmento removido com sucesso!',
            'titulo_primario'   => "",
            'titulo_secundario' => "",
        ];
    }
    public function index(Request $request)
    {
        if(isset($request['busca'])){
            $busca = $request['busca'];
            $Buscas = Segmento::where('descricao', 'like', '%'.$busca.'%')
                ->paginate(10);
        } else {
            $Buscas = Segmento::paginate(10);
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
        $Segmento = Segmento::find($id);
        return view('pages.'.$this->Page->link.'.show')
            ->with('Segmento', $Segmento)
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
            $Segmento = Segmento::create($data);
            session()->forget('mensagem');
            session(['mensagem' => $this->Page->msg_add]);
            return $this->show($Segmento->idsegmento);
        }
    }

    public function update(Request $request, $id)
    {
        $Segmento = Segmento::find($id);
        $validator = Validator::make($request->all(), [
            'descricao' => 'unique:'.$this->Page->table.',descricao,'.$id.','.$this->Page->primaryKey,
        ]);

        if ($validator->fails()) {
            return redirect()->to($this->getRedirectUrl())
                ->withErrors($validator)
                ->withInput($request->all());
        } else {
            $dataUpdate = $request->all();
            $Segmento->update($dataUpdate);

            session()->forget('mensagem');
            session(['mensagem' => $this->Page->msg_upd]);
            return $this->show($Segmento->idsegmento);
        }
    }

    public function destroy($id)
    {
        $data = Segmento::find($id);
        $data->delete();
        return response()->json(['status' => '1',
            'response' => $this->Page->msg_rem]);
    }
}

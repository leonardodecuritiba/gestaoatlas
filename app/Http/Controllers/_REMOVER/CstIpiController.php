<?php

namespace App\Http\Controllers;

use App\CstIpi;
use Validator;
use Illuminate\Http\Request;

use App\Http\Requests;

class CstIpiController extends Controller
{
    private $Page;

    public function __construct()
    {
        $this->Page = (object)[
            'table' => "cst_ipi",
            'link' => "cst_ipi",
            'primaryKey' => "idcst_ipi",
            'Target' => "CST IPI",
            'Targets' => "CST IPI",
            'Titulo' => "CST IPI",
            'search_no_results' => "Nenhum CST IPI encontrado!",
            'msg_add' => 'CST IPI adicionado com sucesso!',
            'msg_upd' => 'CST IPI atualizado com sucesso!',
            'msg_rem' => 'CST IPI removida com sucesso!',
            'titulo_primario' => "",
            'titulo_secundario' => "",
        ];
    }

    public function index(Request $request)
    {
        if (isset($request['busca'])) {
            $busca = $request['busca'];
            $Buscas = CstIpi::where('descricao', 'like', '%' . $busca . '%')
                ->orwhere('codigo', 'like', '%' . $busca . '%')
                ->paginate(10);
        } else {
            $Buscas = CstIpi::paginate(10);
        }
        return view('pages.' . $this->Page->link . '.index')
            ->with('Page', $this->Page)
            ->with('Buscas', $Buscas);
    }

    public function create()
    {
        $this->Page->titulo_primario = "Cadastrar ";
        $this->Page->titulo_secundario = "Dados do " . $this->Page->Target;
        return view('pages.' . $this->Page->link . '.master')
            ->with('Page', $this->Page);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'codigo' => 'required|unique:' . $this->Page->table,
            'descricao' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->to($this->getRedirectUrl())
                ->withErrors($validator)
                ->withInput($request->all());
        } else {
            $data = $request->all();
            $CstIpi = CstIpi::create($data);
            session()->forget('mensagem');
            session(['mensagem' => $this->Page->msg_add]);
            return $this->show($CstIpi->idcst_ipi);
        }
    }

    public function show($id)
    {
        $this->Page->titulo_primario = "Visualização de ";
        $CstIpi = CstIpi::find($id);
        return view('pages.' . $this->Page->link . '.show')
            ->with('CstIpi', $CstIpi)
            ->with('Page', $this->Page);
    }

    public function update(Request $request, $id)
    {
        $CstIpi = CstIpi::find($id);
        $validator = Validator::make($request->all(), [
            'codigo' => 'unique:' . $this->Page->table . ',codigo,' . $id . ',' . $this->Page->primaryKey,
            'descricao' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->to($this->getRedirectUrl())
                ->withErrors($validator)
                ->withInput($request->all());
        } else {
            $dataUpdate = $request->all();
            $CstIpi->update($dataUpdate);

            session()->forget('mensagem');
            session(['mensagem' => $this->Page->msg_upd]);
            return $this->show($CstIpi->idcst_ipi);
        }
    }

    public function destroy($id)
    {
        $data = CstIpi::find($id);
        $data->delete();
        return response()->json(['status' => '1',
            'response' => $this->Page->msg_rem]);
    }
}

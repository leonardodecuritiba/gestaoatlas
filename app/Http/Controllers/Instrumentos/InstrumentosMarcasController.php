<?php

namespace App\Http\Controllers\Instrumentos;

use App\Http\Controllers\Controller;
use App\Models\Instrumentos\InstrumentoMarca;
use Illuminate\Http\Request;

use App\Http\Requests\Instrumentos\InstrumentoMarcaRequest;

class InstrumentosMarcasController extends Controller
{
    private $Page;
    private $genre = "F";
    private $name = [0 => "Marca do Instrumento", '1' => "Marcas do Instrumento"];

    public function __construct()
    {
        $this->Page = (object)[
            'path' => "instrumentos.",
            'table' => "instrumento_marcas",
            'link' => "instrumento_marcas",
            'primaryKey' => "id",
            'Target' => $this->name[0],
            'Targets' => $this->name[1],
            'Results' => "",
            'Titulo' => $this->name[0],
            'search_no_results' => trans('messages.crud.' . $this->genre . 'RE', ['name' => $this->name[0]]),
            'titulo_primario' => $this->name[0],
            'titulo_secundario' => "",
        ];
    }

    public function index(Request $request)
    {
        if (isset($request['busca'])) {
            $busca = $request['busca'];
            $Buscas = InstrumentoMarca::where('descricao', 'like', '%' . $busca . '%')
                ->paginate(10);
        } else {
            $Buscas = InstrumentoMarca::paginate(10);
        }
        $this->Page->Results = response()->results($Buscas->count(), $this->genre, $this->name);
        return view('pages.' . $this->Page->path . $this->Page->link . '.index')
            ->with('Page', $this->Page)
            ->with('Buscas', $Buscas);
    }

    public function create()
    {
        $this->Page->titulo_secundario = trans('messages.visualization.' . $this->genre . 'DATA', ['name' => $this->name[0]]);
        return view('pages.' . $this->Page->path . $this->Page->link . '.master')
            ->with('Page', $this->Page);
    }

    public function show($id)
    {
        $this->Page->titulo_secundario = trans('messages.visualization.' . $this->genre . 'DATA', ['name' => $this->name[0]]);
        $Data = InstrumentoMarca::find($id);
        return view('pages.' . $this->Page->path . $this->Page->link . '.show')
            ->with('Data', $Data)
            ->with('Page', $this->Page);
    }

    public function store(InstrumentoMarcaRequest $request)
    {
        $data = $request->all();
        $Data = InstrumentoMarca::create($data);
        session()->forget('mensagem');
        session(['mensagem' => trans('messages.crud.' . $this->genre . 'SS', ['name' => $this->name[0]])]);
        return redirect()->route($this->Page->link . '.show', $Data->id);
    }

    public function update(InstrumentoMarcaRequest $request, $id)
    {
        $Data = InstrumentoMarca::find($id);
        $data = $request->all();
        $Data->update($data);
        session()->forget('mensagem');
        session(['mensagem' => trans('messages.crud.' . $this->genre . 'US', ['name' => $this->name[0]])]);
        return redirect()->route($this->Page->link . '.show', $Data->id);
    }

    public function destroy($id)
    {
        $data = InstrumentoMarca::find($id);
        $data->delete();
        return response()->json(['status' => '1',
            'response' => trans('messages.crud.' . $this->genre . 'DS', ['name' => $this->name[0]])]);
    }
}

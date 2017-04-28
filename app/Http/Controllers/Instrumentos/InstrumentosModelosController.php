<?php

namespace App\Http\Controllers\Instrumentos;

use App\Http\Controllers\Controller;
use App\Models\Instrumentos\InstrumentoMarca;
use App\Models\Instrumentos\InstrumentoModelo;
use Illuminate\Http\Request;

use App\Http\Requests\Instrumentos\InstrumentoModeloRequest;

class InstrumentosModelosController extends Controller
{
    private $Page;
    private $genre = "M";
    private $name = [0 => "Modelo de Instrumento", '1' => "Modelos de Instrumento"];

    public function __construct()
    {
        $this->Page = (object)[
            'path' => "instrumentos.",
            'table' => "instrumento_modelos",
            'link' => "instrumento_modelos",
            'primaryKey' => "id",
            'Target' => $this->name[0],
            'Targets' => $this->name[1],
            'Results' => "",
            'Titulo' => $this->name[0],
            'search_no_results' => trans('messages.crud.' . $this->genre . 'RE', ['name' => $this->name[0]]),
            'titulo_primario' => $this->name[0],
            'titulo_secundario' => "",
            'extras' => [],
        ];
    }

    public function index(Request $request)
    {
        if (isset($request['busca'])) {
            $busca = $request['busca'];
            $Buscas = InstrumentoModelo::where('descricao', 'like', '%' . $busca . '%')
                ->paginate(10);
        } else {
            $Buscas = InstrumentoModelo::paginate(10);
        }
        //instrumento_marcas
        $this->Page->Results = response()->results($Buscas->count(), $this->genre, $this->name);
        return view('pages.' . $this->Page->path . $this->Page->link . '.index')
            ->with('Page', $this->Page)
            ->with('Buscas', $Buscas);
    }

    public function create()
    {
        $this->Page->extras['instrumento_marcas'] = InstrumentoMarca::all();
        $this->Page->titulo_secundario = trans('messages.visualization.' . $this->genre . 'DATA', ['name' => $this->name[0]]);
        return view('pages.' . $this->Page->path . $this->Page->link . '.master')
            ->with('Page', $this->Page);
    }

    public function show($id)
    {
        $this->Page->extras['instrumento_marcas'] = InstrumentoMarca::all();
        $this->Page->titulo_secundario = trans('messages.visualization.' . $this->genre . 'DATA', ['name' => $this->name[0]]);
        $Data = InstrumentoModelo::find($id);
        return view('pages.' . $this->Page->path . $this->Page->link . '.show')
            ->with('Data', $Data)
            ->with('Page', $this->Page);
    }

    public function store(InstrumentoModeloRequest $request)
    {
        $data = $request->all();
        $Data = InstrumentoModelo::create($data);
        session()->forget('mensagem');
        session(['mensagem' => trans('messages.crud.' . $this->genre . 'SS', ['name' => $this->name[0]])]);
        return redirect()->route($this->Page->link . '.show', $Data->id);
    }

    public function update(InstrumentoModeloRequest $request, $id)
    {
        $Data = InstrumentoModelo::find($id);
        $data = $request->all();
        $Data->update($data);
        session()->forget('mensagem');
        session(['mensagem' => trans('messages.crud.' . $this->genre . 'US', ['name' => $this->name[0]])]);
        return redirect()->route($this->Page->link . '.show', $Data->id);
    }

    public function destroy($id)
    {
        $data = InstrumentoModelo::find($id);
        $data->delete();
        return response()->json(['status' => '1',
            'response' => trans('messages.crud.' . $this->genre . 'DS', ['name' => $this->name[0]])]);
    }
}

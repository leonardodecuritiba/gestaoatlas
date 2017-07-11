<?php

namespace App\Http\Controllers\Instrumentos;

use App\Helpers\ImageHelper;
use App\Http\Controllers\Controller;
use App\Models\Instrumentos\InstrumentoBase;
use App\Models\Instrumentos\InstrumentoModelo;
use Illuminate\Http\Request;
use App\Models\ExcelFile;

use App\Http\Requests\Instrumentos\InstrumentoBaseRequest;

class InstrumentosBasesController extends Controller
{
    private $Page;
    private $genre = "M";
    private $name = [0 => "Instrumento de Base", '1' => "Instrumento de Base"];

    public function __construct()
    {
        $this->Page = (object)[
            'path' => "instrumentos.",
            'table' => "instrumento_bases",
            'link' => "instrumento_bases",
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
            $Buscas = InstrumentoBase::where('descricao', 'like', '%' . $busca . '%')
                ->get();
        } else {
            $Buscas = InstrumentoBase::all();
        }
        //instrumento_marcas
        $this->Page->Results = response()->results($Buscas->count(), $this->genre, $this->name);
        return view('pages.' . $this->Page->path . $this->Page->link . '.index')
            ->with('Page', $this->Page)
            ->with('Buscas', $Buscas);
    }

    public function create()
    {
        $this->Page->extras['instrumento_modelos'] = InstrumentoModelo::all();
        $this->Page->titulo_secundario = trans('messages.visualization.' . $this->genre . 'DATA', ['name' => $this->name[0]]);
        return view('pages.' . $this->Page->path . $this->Page->link . '.master')
            ->with('Page', $this->Page);
    }

    public function show($id)
    {
        $this->Page->extras['instrumento_modelos'] = InstrumentoModelo::all();
        $this->Page->titulo_secundario = trans('messages.visualization.' . $this->genre . 'DATA', ['name' => $this->name[0]]);
        $Data = InstrumentoBase::find($id);
        return view('pages.' . $this->Page->path . $this->Page->link . '.show')
            ->with('Data', $Data)
            ->with('Page', $this->Page);
    }

    public function store(InstrumentoBaseRequest $request)
    {
        $data = $request->all();
        if ($request->hasfile('foto')) {
            $ImageHelper = new ImageHelper();
            $data['foto'] = $ImageHelper->store($request->file('foto'), $this->Page->link);
        } else {
            $data['foto'] = NULL;
        }
        $Data = InstrumentoBase::create($data);
        session()->forget('mensagem');
        session(['mensagem' => trans('messages.crud.' . $this->genre . 'SS', ['name' => $this->name[0]])]);
        return redirect()->route($this->Page->link . '.show', $Data->id);
    }

    public function update(InstrumentoBaseRequest $request, $id)
    {
        $Data = InstrumentoBase::find($id);
        $data = $request->all();
        if ($request->hasfile('foto')) {
            $ImageHelper = new ImageHelper();
            $data['foto'] = $ImageHelper->update($request->file('foto'), $this->Page->link, $Data->foto);
        }
        $Data->update($data);
        session()->forget('mensagem');
        session(['mensagem' => trans('messages.crud.' . $this->genre . 'US', ['name' => $this->name[0]])]);
        return redirect()->route($this->Page->link . '.show', $Data->id);
    }

    public function destroy($id)
    {
        $data = InstrumentoBase::find($id);
        $ImageHelper = new ImageHelper();
        $ImageHelper->remove($data['foto'], $this->Page->link);
        $data->delete();
        return response()->json(['status' => '1',
            'response' => trans('messages.crud.' . $this->genre . 'DS', ['name' => $this->name[0]])]);
    }

    public function exportar(ExcelFile $export)
    {
        $InstrumentoBase = InstrumentoBase::all();
        return $export->sheet('sheetName', function ($sheet) use ($InstrumentoBase) {

            $dados = array(
                'id',
                'marca/modelo',
                'descricao',
                'divisao',
                'portaria',
                'capacidade',
            ); //porcentagem

            $sheet->row(1, $dados);
            //'idpeca_tributacao',
//            dd($data_peca);

            $i = 2;
            foreach ($InstrumentoBase as $selecao) {
                $sheet->row($i, array(
                    $selecao->id,
                    $selecao->modelo->getMarcaModelo(),
                    $selecao->descricao,
                    $selecao->divisao,
                    $selecao->portaria,
                    $selecao->capacidade,
                ));
                $i++;
            }
        })->export('xls');
    }
}

<?php

namespace App\Http\Controllers;

use App\Instrumento;
use App\Models\ExcelFile;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Http\Request;



use App\Http\Requests;
use App\Http\Requests\Instrumentos\InstrumentoRequest;

class InstrumentosController extends Controller
{
    private $Page;
    private $idcolaborador;

    public function __construct()
    {
        /*
        $this->middleware('role:empresa');
        if(Auth::check()){
            $this->empresa_id = (Auth::user()->empresa == "")?'*':Auth::user()->empresa->EMP_ID;
            $this->Empresa = (Auth::user()->empresa == "")?'*':Auth::user()->empresa;
        }
        */
        $this->idcolaborador = Auth::user()->colaborador->idcolaborador;
        $this->Page = (object)[
            'link'              => "instrumentos",
            'Target'            => "Instrumento",
            'Targets'           => "Instrumentos",
            'Titulo'            => "Instrumentos",
            'titulo_primario'   => "",
            'titulo_secundario' => "",
            'search_no_results' => "Nenhum Instrumento encontrado!",
        ];
    }

    public function store(InstrumentoRequest $request)
    {
        $data = $request->all();
        //store Instrumento
        $img = new ImageController();
        if ($request->hasFile('etiqueta_inventario')) {
            $data['etiqueta_inventario'] = $img->store($request->file('etiqueta_inventario'), $this->Page->link);
        }
        if ($request->hasFile('etiqueta_inventario')) {
            $data['etiqueta_inventario'] = $img->store($request->file('etiqueta_inventario'), $this->Page->link);
        }
        $data['idcolaborador_criador'] = $this->idcolaborador;
        $data['idcolaborador_validador'] = $this->idcolaborador;
        $data['validated_at'] = Carbon::now()->toDateTimeString();
        $Instrumento = Instrumento::create($data);

        session()->forget('mensagem');
        session(['mensagem' => $this->Page->Target . ' adicionado com sucesso!']);
        return $this->RedirectCliente($Instrumento->idcliente, 'instrumentos');

    }

    public function RedirectCliente($id, $tab = 'instrumentos')
    {
        return redirect()->route('clientes.show', [$id, 'tab' => $tab]);
    }

    public function update(InstrumentoRequest $request, $id)
    {
        $dataUpdate = $request->all();
        $Instrumento = Instrumento::findOrFail($id);
        $img = new ImageController();
        if ($request->hasfile('etiqueta_identificacao')) {
            $dataUpdate['etiqueta_identificacao'] = $img->update($request->file('etiqueta_identificacao'), $this->Page->link, $Instrumento->etiqueta_identificacao);
        }
        if ($request->hasfile('etiqueta_inventario')) {
            $dataUpdate['etiqueta_inventario'] = $img->update($request->file('etiqueta_inventario'), $this->Page->link, $Instrumento->etiqueta_inventario);
        }

        //update Instrumento
        $Instrumento->update($dataUpdate);

        session()->forget('mensagem');
        session(['mensagem' => $this->Page->Target . ' atualizado com sucesso!']);
        return $this->RedirectCliente($Instrumento->idcliente, 'instrumentos');
    }

    public function destroy($id)
    {
        // Remover instrumento
        $Instrumento = Instrumento::find($id);
        $Instrumento->delete();
        return response()->json(['status' => '1',
            'response' => $this->Page->Target.' removido com sucesso!']);
    }

    public function exportar(ExcelFile $export)
    {
        $Instrumentos = Instrumento::where('id');
        return $export->sheet('sheetName', function ($sheet) use ($Instrumentos) {

            $dados = array(
                'idinstrumento',
                'idcliente',
                'cliente',
                'descricao',
                'marca',
                'modelo',
                'ano',
                'capacidade',
                'divisao',
                'portaria',
                'setor',
                '-',
                'numero_serie',
                'patrimonio',
                'inventario',
                'ip',
                'endereco',
                '-',
                'idsetor',
                'idinstrumentobase'
            ); //porcentagem

            $sheet->row(1, $dados);
            //'idpeca_tributacao',
//            dd($data_peca);

            $i = 2;
            foreach ($Instrumentos as $instrumento) {
                $sheet->row($i, array(
                    $instrumento->idinstrumento,
                    $instrumento->cliente->getType()->nome_principal . ' / ' . $instrumento->cliente->getType()->razao_social,
                    $instrumento->cliente->idcliente,
                    $instrumento->descricao,
                    $instrumento->marca->descricao,
                    strtoupper($instrumento->modelo),
                    $instrumento->ano,
                    strtoupper($instrumento->capacidade),
                    strtoupper($instrumento->divisao),
                    $instrumento->portaria,
                    strtoupper($instrumento->setor),
                    '-',
                    $instrumento->numero_serie,
                    $instrumento->patrimonio,
                    $instrumento->inventario,
                    $instrumento->ip,
                    $instrumento->endereco,
                    '-',
                    $instrumento->idsetor,
                    $instrumento->idbase,
                ));
                $i++;
            }
        })->export('xls');
    }
}

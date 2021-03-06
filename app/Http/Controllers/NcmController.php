<?php

namespace App\Http\Controllers;

use App\Ncm;
use Excel;
use Validator;
use Illuminate\Http\Request;

use App\Http\Requests;

class NcmController extends Controller
{
    private $Page;

    public function __construct()
    {
        $this->Page = (object)[
            'table'             => "ncm",
            'link'              => "ncm",
            'primaryKey'        => "idncm",
            'Target'            => "NCM",
            'Targets'           => "NCM",
            'Titulo'            => "NCM",
            'search_no_results' => "Nenhum NCM encontrado!",
            'msg_add'           => 'NCM adicionado com sucesso!',
            'msg_upd'           => 'NCM atualizado com sucesso!',
            'msg_rem'           => 'NCM removido com sucesso!',
            'msg_imp'           => 'NCM importado com sucesso!',
            'titulo_primario'   => "",
            'titulo_secundario' => "",
        ];
    }

    public function index(Request $request)
    {
        if(isset($request['busca'])){
            $busca = $request['busca'];
            $Buscas = Ncm::where('descricao', 'like', '%'.$busca.'%')
                ->orwhere('codigo', 'like', '%'.$busca.'%')
                ->paginate(10);
        } else {
            $Buscas = Ncm::paginate(10);
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
        $Ncm = Ncm::find($id);
        return view('pages.'.$this->Page->link.'.show')
            ->with('Ncm', $Ncm)
            ->with('Page', $this->Page);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'codigo'                => 'required|unique:'.$this->Page->table,
            'descricao'             => 'required',
            'aliquota_nacional'     => 'required',
            'aliquota_importacao'   => 'required'
        ]);
        if ($validator->fails()) {
            return redirect()->to($this->getRedirectUrl())
                ->withErrors($validator)
                ->withInput($request->all());
        } else {
            $data = $request->all();
            $Ncm = Ncm::create($data);
            session()->forget('mensagem');
            session(['mensagem' => $this->Page->msg_add]);
            return $this->show($Ncm->idncm);
        }
    }

    public function update(Request $request, $id)
    {
        $Ncm = Ncm::find($id);
        $validator = Validator::make($request->all(), [
            'codigo'                => 'unique:'.$this->Page->table.',codigo,'.$id.','.$this->Page->primaryKey,
            'descricao'             => 'required',
            'aliquota_nacional'     => 'required',
            'aliquota_importacao'   => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->to($this->getRedirectUrl())
                ->withErrors($validator)
                ->withInput($request->all());
        } else {
            $dataUpdate = $request->all();
            $Ncm->update($dataUpdate);

            session()->forget('mensagem');
            session(['mensagem' => $this->Page->msg_upd]);
            return $this->show($Ncm->idncm);
        }
    }

    public function destroy($id)
    {
        $data = Ncm::find($id);
        $data->delete();
        return response()->json(['status' => '1',
            'response' => $this->Page->msg_rem]);
    }
//
//    public function importar(Request $request)
//    {
//        $this->Page->titulo_primario    = "Importação de ";
//        $Importacao = NULL;
//        if ($request->hasFile('upload')) {
//            set_time_limit(3600);
//            $extensao = strtolower($request->file('upload')->getClientOriginalExtension());
//            if($extensao == 'xls' || $extensao == 'xlsx') {
//                $dir_temp = 'uploads/temp/';
//                $name_temp = 'import.'.$extensao;
//
//                $request->file('upload')->move(storage_path($dir_temp), $name_temp);
//
//                $file = storage_path($dir_temp).$name_temp;
//
//                // Loop through all rows
//                $start = microtime(true);
//                //fazer importação aqui:
//                $Importacao = Excel::load($file, function() {})->all();
//
//                $Importacao->each(function($row,$i) {
//
//                    $validator = Validator::make($row->toArray(), [
//                        'codigo'                => 'required',
//                        'aliquota_nacional'     => 'required',
//                        'aliquota_importacao'   => 'required',
//                    ]);
//                    if (!$validator->fails()) {
//                        //Teste para saber se já existe
//                        $up = Ncm::where('codigo',$row->codigo)->first();
//                        if($up == NULL){
//                            Ncm::create($row->toArray());
//                        }
//                    }
//                });
//
//                return round((microtime(true) - $start) * 1000, 3);
//            } else {
//                return redirect()->to($this->getRedirectUrl())
//                    ->withInput($request->all());
//            }
//
//
//
//        }
//
//        $this->Page->titulo_secundario  = "Arquivo do ".$this->Page->Target;
//        return view('pages.'.$this->Page->link.'.importar')
//            ->with('Importacao', $Importacao)
//            ->with('Page', $this->Page);
//    }


}

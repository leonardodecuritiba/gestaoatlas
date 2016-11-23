<?php

namespace App\Http\Controllers;

//use App\CategoriaTributacao;
//use App\CstIpi;
//use App\Ncm;
//use App\OrigemTributacao;
//use App\Tributacao;
use App\Grupo;
use App\Helpers\DataHelper;
use App\Marca;
use App\Peca;
use App\TabelaPreco;
use App\Unidade;
use Illuminate\Support\Facades\Redirect;
use Validator;
use App\Fornecedor;
use Illuminate\Http\Request;

use App\Http\Requests;

class PecasController extends Controller
{
    public $required;
    private $Page;

    public function __construct()
    {
        /*
        $this->middleware('role:empresa');
        if(Auth::check()){
            $this->empresa_id = (Auth::user()->empresa == "")?'*':Auth::user()->empresa->EMP_ID;
            $this->Empresa = (Auth::user()->empresa == "")?'*':Auth::user()->empresa;
        }
        */
        $this->idcolaborador = 1;
        $this->Page = (object)[
            'table'             => "pecas",
            'link'              => "pecas",
            'primaryKey'        => "idpeca",
            'Target'            => "Produto/Peça",
            'Targets'           => "Produtos/Peças",
            'Titulo'            => "Produtos/Peças",
            'search_no_results' => "Nenhuma Produto/Peça encontrada!",
            'msg_add'           => 'Produto/Peça adicionada com sucesso!',
            'msg_upd'           => 'Produto/Peça atualizada com sucesso!',
            'msg_rem'           => 'Produto/Peça removida com sucesso!',
            'titulo_primario'   => "",
            'titulo_secundario' => "",
        ];
    }

    public function index(Request $request)
    {
        $titulo = "Busca de ";
        if(isset($request['busca'])){
            $busca = $request['busca'];
            $Buscas = Peca::where('descricao', 'like', '%'.$busca.'%')
                ->paginate(10);
        } else {
            $Buscas = Peca::paginate(10);
        }
        return view('pages.'.$this->Page->link.'.index')
            ->with('Page', $this->Page)
            ->with('Buscas',$Buscas);
    }

    public function create()
    {
        $this->Page->titulo_primario    = "Cadastrar ";
        $this->Page->titulo_secundario  = "Dados da ".$this->Page->Target;
        $this->Page->extras = [
            'fornecedores'          => Fornecedor::all(),
            'marcas'                => Marca::all(),
            'unidades'              => Unidade::all(),
            'grupos'                => Grupo::all(),
//            'ncm'                   => Ncm::get()->take(100),
//            'categoria_tributacao'  => CategoriaTributacao::all(),
//            'origem_tributacao'     => OrigemTributacao::all(),
//            'cst_ipi'               => CstIpi::all(),
            'tabela_preco'          => TabelaPreco::all(),
        ];
        return view('pages.'.$this->Page->link.'.master')
            ->with('Page', $this->Page);
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'codigo'            => 'required',
            'tipo'              => 'required',
            'descricao'         => 'required',
            'descricao_tecnico' => 'required',
            'idmarca'           => 'required',
            'idgrupo'           => 'required',
            'idunidade'         => 'required',
            'comissao_tecnico'  => 'required',
            'comissao_vendedor' => 'required',
            'custo_final'       => 'required',
            /*
            'custo_compra'      => 'required',
            'custo_imposto'     => 'required',
            'idncm'                     => 'required',
            'idcategoria_tributacao'    => 'required',
            'idorigem_tributacao'       => 'required',
            'peso_liquido'              => 'required',
            'peso_bruto'                => 'required',
            'idcst_ipi'                 => 'required',
            'ipi'                       => 'required',
            'icms'                      => 'required',
            'reducao_bc_icms'           => 'required',
            'reducao_bc_icms_st'        => 'required',
            'aliquota_icms'             => 'required'
            */
        ]);
        if ($validator->fails()) {
            return redirect()->to($this->getRedirectUrl())
                ->withErrors($validator)
                ->withInput($request->all());
        } else {
            $data = $request->all();

//            $campos = ['comissao_tecnico','comissao_vendedor','custo_compra','custo_frete','custo_imposto'];
            $campos = ['comissao_tecnico','comissao_vendedor','custo_final'];
            foreach($campos as $val){
                if($data[$val] == ''){
                    $data[$val] = NULL;
                } else {
                    $data[$val] = str_replace(',','.',str_replace('.','',$data[$val]));
                }
            }

            /*
            //Calcular custo_final
            $data['custo_final'] = $data['custo_compra'] + $data['custo_frete'] + $data['custo_imposto'];

            //Calcular preco_final
            // zerando os custos de dolar se náo houver
            $campos = ['custo_dolar_cambio','custo_dolar','custo_dolar_frete','custo_dolar_imposto'];
            foreach($campos as $dolar){
                if($data[$dolar] == ''){
                    $data[$dolar] = NULL;
                }
            }
            //Cálculo do preço
            $data['preco'] = $data['custo_dolar_cambio'] * $data['custo_dolar'];
            $data['preco_frete'] = $data['custo_dolar_cambio'] * $data['custo_dolar_frete'];
            $data['preco_imposto'] = $data['custo_dolar_cambio'] * $data['custo_dolar_imposto'];
            $data['preco_final'] = $data['preco'] + $data['preco_frete'] + $data['preco_imposto'];

            // zerando as tributações de importação se náo houver
            $campos = ['aliquota_ii','icms_importacao','aliquota_cofins_importacao','aliquota_pis_importacao'];
            foreach($campos as $sel){
                if($data[$sel] == ''){
                    $data[$sel] = NULL;
                }
            }

            // testando as opções BOOL
            $nomes_bool = ['isencao_icms','ipi_venda','reducao_icms'];
            foreach($nomes_bool as $n){
                if(isset($data[$n])){
                    $data[$n] = 1;
                }else {
                    $data[$n] = 0;
                }
            }

            $ali_impotacao = ['aliquota_ii','icms_importacao','aliquota_cofins_importacao','aliquota_pis_importacao'];
            foreach($ali_impotacao as $n){
                if($data[$n] == '' || $data[$n] == 0){
                    $data[$n] = NULL;
                }
            }
            $Tributacao = Tributacao::create($data);
            $data['idtributacao'] = $Tributacao->idtributacao;
            */

            //store foto da peca
            if($request->hasfile('foto')){
                $img = new ImageController();
                $data['foto'] = $img->store($request->file('foto'), $this->Page->table);
            } else {
                $data['foto'] = NULL;
            }

            $Peca = Peca::create($data);

            session()->forget('mensagem');
            session(['mensagem' => $this->Page->msg_add]);
            return $this->show($Peca->idpeca);
        }
    }

    public function show($id)
    {
        $this->Page->titulo_primario = "Visualização de ";
        $Peca = Peca::find($id);
        $this->Page->extras = [
            'fornecedores' => Fornecedor::all(),
            'marcas' => Marca::all(),
            'unidades' => Unidade::all(),
            'grupos' => Grupo::all(),
//            'ncm'                   => Ncm::get()->take(100),
//            'categoria_tributacao'  => CategoriaTributacao::all(),
//            'origem_tributacao'     => OrigemTributacao::all(),
//            'cst_ipi'               => CstIpi::all(),
//            'tabela_preco'          => TabelaPreco::all(),
        ];
        return view('pages.' . $this->Page->link . '.show')
            ->with('Peca', $Peca)
            ->with('Page', $this->Page);
    }

    public function update(Request $request, $id)
    {
//        return $request->all();
        $Peca = Peca::find($id);
        $validator = Validator::make($request->all(), [
            'codigo'            => 'required',
            'tipo'              => 'required',
            'descricao'         => 'required',
            'descricao_tecnico' => 'required',
            'idmarca'           => 'required',
            'idgrupo'           => 'required',
            'idunidade'         => 'required',
            'comissao_tecnico'  => 'required',
            'comissao_vendedor' => 'required',
            'custo_final'       => 'required',
            /*
            'custo_compra'      => 'required',
            'custo_imposto'     => 'required',
            'idncm'                     => 'required',
            'idcategoria_tributacao'    => 'required',
            'idorigem_tributacao'       => 'required',
            'peso_liquido'              => 'required',
            'peso_bruto'                => 'required',
            'idcst_ipi'                 => 'required',
            'ipi'                       => 'required',
            'icms'                      => 'required',
            'reducao_bc_icms'           => 'required',
            'reducao_bc_icms_st'        => 'required',
            'aliquota_icms'             => 'required'
            */
        ]);

        if ($validator->fails()) {
            return redirect()->to($this->getRedirectUrl())
                ->withErrors($validator)
                ->withInput($request->all());
        } else {
            $dataUpdate = $request->all();

//            $campos = ['comissao_tecnico','comissao_vendedor','custo_compra','custo_frete','custo_imposto'];
            $campos = ['comissao_tecnico','comissao_vendedor','custo_final'];
            foreach($campos as $val){
                if($dataUpdate[$val] == ''){
                    $dataUpdate[$val] = NULL;
                }
            }

            //store da nova foto da peca
            if ($request->hasfile('foto')) {
                $img = new ImageController();
                $dataUpdate['foto'] = $img->update($request->file('foto'), $this->Page->table, $Peca->foto);
            }
//            return $dataUpdate;

            $Peca->update($dataUpdate);

            //ATUALIZANDO OS PREÇOS E MARGENS
            $margens = $request->get('margem');
            $margem_minimos = $request->get('margem_minimo');
            $custo_final = $Peca->custo_final_float();
            foreach ($Peca->tabela_preco as $tabela_preco) {
                $margem = DataHelper::getPercent2Float($margens[$tabela_preco->idtabela_preco]);
                $margem_minimo = DataHelper::getPercent2Float($margem_minimos[$tabela_preco->idtabela_preco]);

                $dataUpd = [
                    'preco' => $custo_final + ($custo_final * $margem) / 100,
                    'margem' => $margem,
                    'preco_minimo' => $custo_final + ($custo_final * $margem_minimo) / 100,
                    'margem_minimo' => $margem_minimo,
                ];
//                return $dataUpd;
                $tabela_preco->update($dataUpd);
            }
            session()->forget('mensagem');
            session(['mensagem' => $this->Page->msg_upd]);
            return Redirect::route('pecas.show', $Peca->idpeca);
            /*
            //Calcular custo_final
            $dataUpdate['custo_final'] = $dataUpdate['custo_compra'] + $dataUpdate['custo_frete'] + $dataUpdate['custo_imposto'];

            //Calcular preco_final
            // zerando os custos de dolar se náo houver
            $campos = ['custo_dolar_cambio','custo_dolar','custo_dolar_frete','custo_dolar_imposto'];
            foreach($campos as $dolar){
                if($dataUpdate[$dolar] == ''){
                    $dataUpdate[$dolar] = NULL;
                }
            }

            //Cálculo do preço
            $dataUpdate['preco'] = $dataUpdate['custo_dolar_cambio'] * $dataUpdate['custo_dolar'];
            $dataUpdate['preco_frete'] = $dataUpdate['custo_dolar_cambio'] * $dataUpdate['custo_dolar_frete'];
            $dataUpdate['preco_imposto'] = $dataUpdate['custo_dolar_cambio'] * $dataUpdate['custo_dolar_imposto'];
            $dataUpdate['preco_final'] = $dataUpdate['preco'] + $dataUpdate['preco_frete'] + $dataUpdate['preco_imposto'];

            // zerando as tributações de importação se náo houver
            $campos = ['aliquota_ii','icms_importacao','aliquota_cofins_importacao','aliquota_pis_importacao'];
            foreach($campos as $sel){
                if($dataUpdate[$sel] == ''){
                    $dataUpdate[$sel] = NULL;
                }
            }

//            return $dataUpdate;
            // testando as opções BOOL
            $nomes_bool = ['isencao_icms','ipi_venda','reducao_icms'];
            foreach($nomes_bool as $n){
                if(isset($dataUpdate[$n])){
                    $dataUpdate[$n] = 1;
                } else {
                    $dataUpdate[$n] = 0;
                }
            }

            $ali_impotacao = ['aliquota_ii','icms_importacao','aliquota_cofins_importacao','aliquota_pis_importacao'];
            foreach($ali_impotacao as $n){
                if($dataUpdate[$n] == '' || $dataUpdate[$n] == 0){
                    $dataUpdate[$n] = NULL;
                }
            }
            $Peca->tributacao->update($dataUpdate);

            */

        }
    }

    public function destroy($id)
    {
        // Remover peca
        $Peca = Peca::find($id);
//        $Peca->tributacao->delete();
        $Peca->delete();
        return response()->json(['status' => '1',
            'response' => $this->Page->msg_rem]);
    }

    public function RedirectFornecedor($id,$tab='pecas')
    {
        $FornecedoresController = new FornecedoresController();
        return $FornecedoresController->show($id,$tab);
    }
}

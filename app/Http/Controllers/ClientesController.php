<?php

namespace App\Http\Controllers;

use App\FormaPagamento;
use App\Marca;
use App\Models\ExcelFile;
use App\Models\PrazoPagamento;
use App\Models\TipoEmissaoFaturamento;
use App\Regiao;
use App\Segmento;
use App\TabelaPreco;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Maatwebsite\Excel\Facades\Excel;
use Validator;
use App\Cliente;
use App\PessoaJuridica;
use App\PessoaFisica;
use App\Contato;
use Illuminate\Http\Request;
use App\Http\Requests\ClientesRequest;

use App\Http\Requests;

class ClientesController extends Controller
{
    private $Page;
    private $colaborador;

    public function __construct()
    {
        $this->colaborador = Auth::user()->colaborador;
        $this->Page = (object)[
            'link'              => "clientes",
            'Target'            => "Cliente",
            'Targets'           => "Clientes",
            'Titulo'            => "Clientes",
            'titulo_primario'   => "",
            'titulo_secundario' => "",
            'search_no_results' => "Nenhum cliente encontrado!",
            'extras'            => NULL,
        ];
    }

    public function index(Request $request)
    {
        if (isset($request['busca'])) {
            $busca = $request['busca'];
            $Buscas = Cliente::getAll($busca)->paginate(10);
        } else {
            $Buscas = Cliente::paginate(10);
        }

        return view('pages.'.$this->Page->link.'.index')
            ->with('Page', $this->Page)
            ->with('Buscas',$Buscas);
    }

    public function create()
    {
        $this->Page->titulo_primario    = "Cadastrar ";
        $this->Page->titulo_secundario  = "Dados do Cliente";
        $this->Page->extras = [
            'centro_custo'      => Cliente::all(),
            'segmentos'         => Segmento::all(),
            'regioes'           => Regiao::all(),
            'tabela_precos'     => TabelaPreco::all(),
            'formas_pagamentos' => FormaPagamento::all(),
            'tipos_emissao_faturamento' => TipoEmissaoFaturamento::all(),
        ];
        return view('pages.'.$this->Page->link.'.master')
            ->with('Page', $this->Page);
    }

    public function show($id,$tab='sobre')
    {
        $this->Page->titulo_primario = "Visualização de ";
        $this->Page->tab = $tab;
        $this->Page->extras = [
            'centro_custo'  => Cliente::all(),
            'segmentos'     => Segmento::all(),
            'regioes'       => Regiao::all(),
            'tabela_precos' => TabelaPreco::all(),
            'marcas'        => Marca::all(),
            'formas_pagamentos' => FormaPagamento::all(),
            'tipos_emissao_faturamento' => TipoEmissaoFaturamento::all(),
        ];
        $Cliente = Cliente::find($id);
        return view('pages.'.$this->Page->link.'.show')
            ->with('Cliente', $Cliente)
            ->with('Page', $this->Page);
    }

    public function store(ClientesRequest $request)
    {
        return $request->all();
        $data['idcolaborador_criador'] = $this->colaborador->idcolaborador;
        if ($this->colaborador->hasRole('admin')) {
            $data['idcolaborador_validador'] = $this->colaborador->idcolaborador;
            $data['validated_at'] = Carbon::now()->toDateTimeString();
        }
        //store CONTATO
        $Contato = Contato::create($data);
        $data['idcontato'] = $Contato->idcontato;

//            return $data;
        if ($data['tipo_cliente'] == "0") {
            //store física
            $TipoCliente = PessoaFisica::create($data);
            $data['idpfisica'] = $TipoCliente->idpfisica;
        } else {
            //store juridica
            $TipoCliente = PessoaJuridica::create($data);
            $data['idpjuridica'] = $TipoCliente->idpjuridica;
        }
        if ($request->hasfile('foto')) {
            $img = new ImageController();
            $data['foto'] = $img->store($request->file('foto'), $this->Page->link);
        } else {
            $data['foto'] = NULL;
        }

        //store Cliente
        if ($data['centro_custo'] != '0') {
            $data['idcliente_centro_custo'] = ($data['idcliente_centro_custo'] == '') ? NULL : $data['idcliente_centro_custo'];
            $data['limite_credito_tecnica'] = NULL;
        } else {
            $data['idcliente_centro_custo'] = NULL;
        }

        foreach (['tecnica', 'comercial'] as $tipo) {
            $prazo_pagamento['id'] = $data['prazo_pagamento_' . $tipo];
            switch ($data['prazo_pagamento_' . $tipo]) {
                case PrazoPagamento::_STATUS_A_VISTA_:
                    $prazo_pagamento['extras'] = '';
                    break;
                case PrazoPagamento::_STATUS_PARCELADO_:
                    $prazo_pagamento['extras'] = $data['parcela_' . $tipo];
                    break;
            }
            $data['prazo_pagamento_' . $tipo] = json_encode($prazo_pagamento);
        }

        $Cliente = Cliente::create($data);

        session()->forget('mensagem');
        session(['mensagem' => $this->Page->Target . ' adicionado com sucesso!']);
        return Redirect::route('clientes.show', $Cliente->idcliente);

    }

    public function update(ClientesRequest $request, $id)
    {
        $Cliente = Cliente::find($id);
        $dataUpdate = $request->all();

        if($Cliente->getType()->tipo_cliente==0){
            //update física
            $Cliente->pessoa_fisica->update($dataUpdate);
        } else {
            //update juridica
            $Cliente->pessoa_juridica->update($dataUpdate);
        }

        //update foto
        if ($request->hasfile('foto')) {
            $img = new ImageController();
            $dataUpdate['foto'] = $img->store($request->file('foto'), $this->Page->link);
        }

        //update CLIENTE
        if ($dataUpdate['centro_custo'] != '0') {
            $dataUpdate['idcliente_centro_custo'] = ($dataUpdate['idcliente_centro_custo'] == '') ? NULL : $dataUpdate['idcliente_centro_custo'];
            $dataUpdate['limite_credito_tecnica'] = NULL;
        } else {
            $dataUpdate['idcliente_centro_custo'] = NULL;
        }

        //prazo_pagamento_tecnica
        $Cliente->updatePrazo($dataUpdate);
        unset($dataUpdate['prazo_pagamento_comercial']);
        unset($dataUpdate['prazo_pagamento_tecnica']);

        $Cliente->update($dataUpdate);
        //update CONTATO
        $Cliente->contato->update($dataUpdate);

        session()->forget('mensagem');
        session(['mensagem' => ($this->Page->Target . ' atualizado com sucesso!')]);
        return Redirect::route('clientes.show', $Cliente->idcliente);
    }

    public function destroy($id)
    {
        $data = Cliente::find($id);
        $data->delete();
        return response()->json(['status' => '1',
            'response' => $this->Page->msg_rem]);
    }

    public function validar($id)
    {
        $Cliente = Cliente::find($id);
        if ($this->colaborador->hasRole('admin')) {
            $data['idcolaborador_validador'] = $this->colaborador->idcolaborador;
            $data['validated_at'] = Carbon::now()->toDateTimeString();
            $Cliente->update($data);
            session()->forget('mensagem');
            session(['mensagem' => ($this->Page->Target . ' atualizado com sucesso!')]);
        }
        return Redirect::route('clientes.show', $id);
    }

    public function exportarCodMunicipio(ExcelFile $export)
    {
        $Clientes = Cliente::all();
        return $export->sheet('sheetName', function ($sheet) use ($Clientes) {

            $data = array(
                'idcontato',
                'nome_principal',
                'razao_social',
                'cpf_cnpj',
                'estado',
                'municipio',
                'codigo_municipio',
            ); //porcentagem

            $sheet->row(1, $data);
            $i = 2;
            foreach ($Clientes as $cliente) {
                $cliente_tipo = $cliente->getType();
                $sheet->row($i, array(
                    $cliente->idcontato,
                    $cliente_tipo->nome_principal,
                    $cliente_tipo->razao_social,
                    $cliente_tipo->documento,
                    $cliente->contato->estado,
                    $cliente->contato->cidade,
                    '',
                ));
                $i++;
            }
        })->export('xls');
    }

}

<?php

namespace App\Http\Controllers;

use App\AparelhoManutencao;
use App\Cliente;
use App\Colaborador;
use App\Helpers\DataHelper;
use App\Helpers\PrintHelper;
use App\Kit;
use App\KitsUtilizados;
use App\Lacre;
use App\LacreInstrumento;
use App\OrdemServico;
use App\Peca;
use App\PecasUtilizadas;
use App\PessoaFisica;
use App\PessoaJuridica;
use App\Selo;
use App\SeloInstrumento;
use App\Servico;
use App\ServicoPrestado;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Validator;
use Illuminate\Http\Request;

use App\Http\Requests;
use Zizaco\Entrust\Entrust;

class OrdemServicoController extends Controller
{
    private $Page;
    private $colaborador;
    private $tecnico;

    public function __construct()
    {
        $this->colaborador = Auth::user()->colaborador;
        $this->tecnico = $this->colaborador->tecnico;
        $this->Page = (object)[
            'table' => "ordem_servicos",
            'link' => "ordem_servicos",
            'primaryKey' => "idordem_servico",
            'Search' => "Buscar por CPF, CNPJ, Nome Fantasia ou Razão Social...",
            'Search_instrumento' => "Buscar Instrumento/Equipamento por Marca, Nº Série ou Descrição ...",
            'Target' => "Ordem de Serviços",
            'Targets' => "Ordens de Serviços",
            'Titulo' => "Ordem de Serviços",
            'search_no_results' => "Nenhuma Ordem de Serviços encontrada!",
            'search_results' => "Ordens de Serviços encontradas",
            'msg_val' => 'Cliente não validado!',
            'msg_abr' => 'Ordem de Serviços aberta com sucesso!',
            'msg_fec' => 'Ordem de Serviços finalizada com sucesso!',
            'msg_upd' => 'Ordem de Serviços atualizada com sucesso!',
            'msg_rem' => 'Ordem de Serviços removida com sucesso!',
            'msg_rea' => 'Ordem de Serviços reaberta com sucesso!',
            'titulo_primario' => "",
            'titulo_secundario' => "",
            'extras' => [],
        ];
    }

    public function index(Request $request)
    {
        $this->Page->extras['situacao_ordem_servico'] = OrdemServico::getSituacaoSelect();
        $now = Carbon::now();
        $OrdemServicosMesAtual = NULL;
        $OrdemServicosPassadas = NULL;
        if ($request->has('idordem_servico')) {
            $OrdemServicosMesAtual = OrdemServico::where('idordem_servico', $request->get('idordem_servico'))
                ->with('cliente', 'colaborador')
                ->get();
            $this->Page->extras['clientes'] = Cliente::all();
        } else {
            if (!$request->has('data')) {
                $request->merge(['data' => $now->format('d/m/Y')]);
            }
            $QuerySearch = OrdemServico::filter_situacao_cliente($request->all())->get();
            $this->Page->extras['clientes'] = $QuerySearch->pluck('cliente');
            $Search = $QuerySearch->map(function ($item) {
                $item->periodo = Carbon::createFromFormat("Y-m-d H:i:s", $item->created_at)->format('m/Y');
                return $item;
            })->groupBy('periodo');
            if ($Search->count() > 0) {
                $mes_atual = $now->format('m/Y');
                if (key_exists($mes_atual, $Search)) {
                    $OrdemServicosMesAtual = $Search[$mes_atual];
                    unset($Search[$mes_atual]);
                } else {
                    $OrdemServicosMesAtual = NULL;
                }
                $OrdemServicosPassadas = $Search;
            }
        }
        return view('pages.' . $this->Page->link . '.index')
            ->with('Page', $this->Page)
            ->with('OrdemServicosMesAtual', $OrdemServicosMesAtual)
            ->with('OrdemServicosPassadas', $OrdemServicosPassadas);
    }

    public function show_centro_custo(Request $request)
    {
        //{situacao_ordem_servico}/{data}/{idcentro_custo}
        //atualizando o valor total da OS
        $Buscas = OrdemServico::filter_situacao_centro_custo($request->all())->get();
        $CentroCusto = $Buscas[0]->centro_custo;
        return view('pages.' . $this->Page->link . '.show_centro_custo')
            ->with('Page', $this->Page)
            ->with('CentroCusto', $CentroCusto)
            ->with('Buscas', $Buscas);
    }

    public function show(Request $request, $idordem_servico)
    {
        //atualizando o valor total da OS
        $OrdemServico = OrdemServico::findOrFail($idordem_servico);
        $OrdemServico->updateValores();
        return $this->buscaInstrumentos($request, $idordem_servico);
    }

    public function buscaInstrumentos(Request $request, $idordem_servico)
    {
        $OrdemServico = OrdemServico::find($idordem_servico);
        $this->Page->search_no_results = "Nenhum Instrumento encontrado!";
        if ($OrdemServico->idsituacao_ordem_servico <= 2) {
            $Servicos = Servico::all();
            $Pecas = Peca::all();
            $Kits = Kit::all();
            $Instrumentos = $OrdemServico->cliente->instrumentos;
            $Equipamentos = $OrdemServico->cliente->equipamentos;
            return view('pages.' . $this->Page->link . '.show')
                ->with('Page', $this->Page)
                ->with('OrdemServico', $OrdemServico)
                ->with('Servicos', $Servicos)
                ->with('Pecas', $Pecas)
                ->with('Kits', $Kits)
                ->with('Instrumentos', $Instrumentos)
                ->with('Equipamentos', $Equipamentos);
        }
        return Redirect::route('ordem_servicos.resumo', $idordem_servico);
    }

    public function buscaClientes(Request $request)
    {
        $this->Page->Targets = "Clientes";
        $this->Page->Search = "Buscar Cliente por CPF, CNPJ, Nome Fantasia ou Razão Social...";
        $this->Page->search_no_results = "Nenhum Cliente encontrado!";
        if (isset($request['busca'])) {
            $busca = $request['busca'];
            $documento = preg_replace('#[^0-9]#', '', $busca);

            $query = PessoaJuridica::where('razao_social', 'like', '%' . $busca . '%')
                ->orwhere('nome_fantasia', 'like', '%' . $busca . '%');

            if ($documento != '') {
                $query = $query->orwhere('pjuridicas.cnpj', 'like', '%' . $documento . '%');
                $idpfisicas = PessoaFisica::where('cpf', 'like', '%' . $documento . '%')->pluck('idpfisica');
            }

            $query = Cliente::whereIn('idpjuridica', $query->pluck('idpjuridica'));

            if (isset($idpfisicas)) {
                $query = $query->orWhereIn('idpfisica', $idpfisicas);
            }
            $Buscas = $query->validos()->paginate(10);
        } else {
            $Buscas = NULL;
        }
        return view('pages.' . $this->Page->link . '.busca_cliente')
            ->with('Page', $this->Page)
            ->with('Buscas', $Buscas);
    }





    public function abrir($clienteid)
    {
        $Cliente = Cliente::findOrFail($clienteid);

	    //verify if is over client technical limit
	    $limit = $Cliente->getAvailableLimit('tecnica');
	    if($limit <= 0){
	    	$value = DataHelper::getFloat2RealMoeda($limit);
            $erros = ['Não foi possível abrir esta O.S. Limite Técnica atual (' . $value . ') foi atingido para esse cliente. Por favor, contate o Administrador!'];
            return redirect()->back()
		                     ->withErrors($erros);
	    } else if ($Cliente->isValidated()) {
            $OrdemServico = OrdemServico::abrir($Cliente, $this->colaborador->idcolaborador);
            session()->forget('mensagem');
            session(['mensagem' => $this->Page->msg_abr]);
            return Redirect::route('ordem_servicos.show', $OrdemServico->idordem_servico);
        } else {
            return Redirect::route('ordem_servicos.busca')->withErrors($this->Page->msg_val);

        }
    }

	public function resumo($idordem_servico)
	{
		return view('pages.' . $this->Page->link . '.resumo')
			->with('Page', $this->Page)
			->with('OrdemServico', OrdemServico::find($idordem_servico));
	}



    public function adicionaInstrumento(Request $request, $idordem_servico, $idinstrumento)
    {
        //teste se já foi adicionado
        if (AparelhoManutencao::check_instrumento_duplo($idordem_servico, $idinstrumento)) {
            $erro = 'Esse instrumento já está incluído nesta ordem de serviço!';
            return Redirect::route('ordem_servicos.show', $idordem_servico)
                ->withErrors($erro)
                ->withInput($request->all());
        }
        $data = [
            'idordem_servico' => $idordem_servico,
            'idinstrumento' => $idinstrumento,
            'numero_chamado' => $request->get('numero_chamado')
        ];
        AparelhoManutencao::create($data);
        session()->forget('mensagem');
        session(['mensagem' => $this->Page->msg_upd]);
        return Redirect::route('ordem_servicos.show', $idordem_servico);
    }

    public function adicionaEquipamento(Request $request, $idordem_servico, $idequipamento)
    {
        //teste se já foi adicionado
        if (AparelhoManutencao::check_equipamento_duplo($idordem_servico, $idequipamento)) {
            $erro = 'Esse equipamento já está incluído nesta ordem de serviço!';
            return Redirect::route('ordem_servicos.show', $idordem_servico)
                ->withErrors($erro)
                ->withInput($request->all());
        }
        $data = [
            'idordem_servico' => $idordem_servico,
            'idequipamento' => $idequipamento,
            'numero_chamado' => $request->get('numero_chamado')
        ];
        AparelhoManutencao::create($data);
        session()->forget('mensagem');
        session(['mensagem' => $this->Page->msg_upd]);
        return Redirect::route('ordem_servicos.show', $idordem_servico);
    }




    public function removeInstrumento($idaparelho_manutencao)
    {
        $AparelhoManutencao = AparelhoManutencao::find($idaparelho_manutencao);
        $idordem_servico = $AparelhoManutencao->idordem_servico;
	    $AparelhoManutencao->remover();

        session()->forget('mensagem');
        session(['mensagem' => $this->Page->msg_upd]);
        return Redirect::route('ordem_servicos.show', $idordem_servico);
    }

    public function removeEquipamento($idaparelho_manutencao)
    {
	    $AparelhoManutencao = AparelhoManutencao::findOrFail( $idaparelho_manutencao );
	    $AparelhoManutencao->remover();
	    $idordem_servico = $AparelhoManutencao->idordem_servico;

        session()->forget('mensagem');
        session(['mensagem' => $this->Page->msg_upd]);
        return Redirect::route('ordem_servicos.show', $idordem_servico);
    }





    public function updateAparelhoManutencao(Request $request, $idaparelho_manutencao)
    {
	    //Atualizando os dados do aparelho
	    $AparelhoManutencao = AparelhoManutencao::findOrFail($idaparelho_manutencao);
	    $AparelhoManutencao->update($request->only(['defeito','solucao']));

	    //Atualizando a situação da O.S
	    $AparelhoManutencao->ordem_servico->update( [
		    'idsituacao_ordem_servico' => 2
	    ]);

        if ($AparelhoManutencao->has_instrumento()) {
	        $AparelhoManutencao->updateSeloLacreInstrumento( $request->all());
        }

        session()->forget('mensagem');
        session(['mensagem' => $this->Page->msg_upd]);
        return Redirect::route('ordem_servicos.show', $AparelhoManutencao->idordem_servico);
    }





    public function add_insumos(Request $request, $idordem_servico)
    {
        $idaparelho_manutencao = $request->get('idaparelho_manutencao');
        $AparelhoManutencao = AparelhoManutencao::find($idaparelho_manutencao);
        if ($request->has('idservico_id')) {
            $id = $request->get('idservico_id');
            $valor = $request->get('idservico_valor');
            $quantidade = $request->get('idservico_quantidade');
            $desconto = $request->get('idservico_desconto');
            foreach ($id as $i => $v) {
                $idinsumo = $id[$i];
                $insumoUtilizado = $AparelhoManutencao->hasInsumoUtilizadoId($idinsumo, 'servicos');
                if ($insumoUtilizado != NULL) {
                    $insumoUtilizado->update([
                        'quantidade' => $insumoUtilizado->quantidade + $quantidade[$i],
                        'desconto' => $insumoUtilizado->desconto + $desconto[$i],
                    ]);
                } else {
                    $data = [
                        'idaparelho_manutencao' => $idaparelho_manutencao,
                        'idservico' => $id[$i],
                        'valor' => $valor[$i],
                        'quantidade' => $quantidade[$i],
                        'desconto' => $desconto[$i],
                    ];
                    ServicoPrestado::create($data);
                }
            }
        }
        if ($request->has('idpeca_id')) {
            $id = $request->get('idpeca_id');
            $valor = $request->get('idpeca_valor');
            $quantidade = $request->get('idpeca_quantidade');
            $desconto = $request->get('idpeca_desconto');
            foreach ($id as $i => $v) {
                $idinsumo = $id[$i];
                $insumoUtilizado = $AparelhoManutencao->hasInsumoUtilizadoId($idinsumo, 'pecas');
                if ($insumoUtilizado != NULL) {
                    $insumoUtilizado->update([
                        'quantidade' => $insumoUtilizado->quantidade + $quantidade[$i],
                        'desconto' => $insumoUtilizado->desconto + $desconto[$i],
                    ]);
                } else {
                    $data = [
                        'idaparelho_manutencao' => $idaparelho_manutencao,
                        'idpeca' => $id[$i],
                        'valor' => $valor[$i],
                        'quantidade' => $quantidade[$i],
                        'desconto' => $desconto[$i],
                    ];
                    PecasUtilizadas::create($data);
                }
//                $total += DataHelper::getReal2Float($valor[$i]);
            }
        }
        if ($request->has('idkit_id')) {
            $id = $request->get('idkit_id');
            $valor = $request->get('idkit_valor');
            $quantidade = $request->get('idkit_quantidade');
            $desconto = $request->get('idkit_desconto');
            foreach ($id as $i => $v) {
                $idinsumo = $id[$i];
                $insumoUtilizado = $AparelhoManutencao->hasInsumoUtilizadoId($idinsumo, 'kits');
                if ($insumoUtilizado != NULL) {
                    $insumoUtilizado->update([
                        'quantidade' => $insumoUtilizado->quantidade + $quantidade[$i],
                        'desconto' => $insumoUtilizado->desconto + $desconto[$i],
                    ]);
                } else {
                    $data = [
                        'idaparelho_manutencao' => $idaparelho_manutencao,
                        'idkit' => $id[$i],
                        'valor' => $valor[$i],
                        'quantidade' => $quantidade[$i],
                        'desconto' => $desconto[$i],
                    ];
                    KitsUtilizados::create($data);
                }
//                $total += DataHelper::getReal2Float($valor[$i]);
            }
        }
        return Redirect::route('ordem_servicos.show', $idordem_servico);
    }

    public function aplicarValores(Request $request, $idordem_servico)
    {
        $OrdemServico = OrdemServico::find($idordem_servico);
        $OrdemServico->updateValores($request->all());
        session()->forget('mensagem');
        session(['mensagem' => $this->Page->msg_fec]);
        return Redirect::route('ordem_servicos.resumo', $OrdemServico->idordem_servico);
    }

    public function reabrir($idordem_servico)
    {
        OrdemServico::reabrir($idordem_servico);
        session()->forget('mensagem');
        session(['mensagem' => $this->Page->msg_rea]);
        return Redirect::route('ordem_servicos.resumo', $idordem_servico);
        //
    }



    public function finalizar(Request $request, $idordem_servico)
    {
	    $OrdemServico = OrdemServico::find($idordem_servico);

	    //verify if is over client technical limit
	    if($OrdemServico->verifyOverTechnicalLimit()){
		    $limit = $OrdemServico->cliente->getAvailableLimitTecnicaFormatted();
		    $erros = ['Não foi possível finalizar esta O.S. Limite Técnica atual (' . $limit . ') foi atingido para esse cliente. Por favor, contate o Administrador!'];
		    return redirect()->back()
		                     ->withErrors($erros)
		                     ->withInput($request->all());
	    }

        $OrdemServico->finalizar($request->all());
        session()->forget('mensagem');
        session(['mensagem' => $this->Page->msg_fec]);
        return Redirect::route('ordem_servicos.resumo', $OrdemServico->idordem_servico);
    }

	public function destroy($id)
	{
		$OrdemServico = OrdemServico::find($id);
		$OrdemServico->remover();

		session()->forget('mensagem');
		session(['mensagem' => $this->Page->msg_rem]);
		return Redirect::route('ordem_servicos.index', ['1']);
	}




    public function imprimir($idordem_servico)
    {
        $OrdemServico = OrdemServico::find($idordem_servico);
        $PrintHelper = new PrintHelper();
        return $PrintHelper->printOS($OrdemServico);
    }

    public function exportar($idordem_servico)
    {
        $OrdemServico = OrdemServico::find($idordem_servico);
        $PrintHelper = new PrintHelper();
        return $PrintHelper->exportOS($OrdemServico);
    }





    public function get_ordem_servicos_cliente(Request $request, $idcliente)
    {
        $Cliente = Cliente::find($idcliente);
        if ($Cliente->has_ordem_servicos()) {
            $Buscas = $Cliente->ordem_servicos()->paginate(10);
        } else {
            $Buscas = NULL;
        }
        return view('pages.' . $this->Page->link . '.index')
            ->with('Page', $this->Page)
            ->with('Buscas', $Buscas);
    }





    public function encaminhar(Request $request, $idordem_servico)
    {
        return 'encaminhar por email';
        $OrdemServico = OrdemServico::find($idordem_servico);
        $OrdemServico->update([
            'data_finalizada' => Carbon::now()->toDateTimeString()
        ]);
        session()->forget('mensagem');
        session(['mensagem' => $this->Page->msg_fec]);
        return $this->resumo($request, $idordem_servico);
    }

    public function get_ordem_servicos_colaborador(Request $request, $idcliente)
    {
        return 'EM BREVE, =]';
    }

}

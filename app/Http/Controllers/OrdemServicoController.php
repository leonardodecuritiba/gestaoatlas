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
//            return $Search->toArray();
        }
//        return $OrdemServicosMesAtual;
//        return $OrdemServicosPassadas;
//        return $OrdemServicosMesAtual;

//        $Search = $Search->map(function($item){
//            $item->id = 0;
//            return $item;
//        });
//        foreach ($Search as $key => $search){
//            print_r($key);
//            print_r('<pre>');
//            print_r($search);
//            print_r('</pre>');
//        }
//        exit;
//        return 0;
        return view('pages.' . $this->Page->link . '.index')
            ->with('Page', $this->Page)
            ->with('OrdemServicosMesAtual', $OrdemServicosMesAtual)
            ->with('OrdemServicosPassadas', $OrdemServicosPassadas);
    }

//    public function index_centro_custo(Request $request)
//    {
//        $this->Page->extras['situacao_ordem_servico'] = OrdemServico::getSituacaoSelect();
//
//        $query = self::filter_situacao($data)
//            ->with('centro_custo')
//            ->whereNotNull('idcentro_custo')
//            ->groupBy('idcentro_custo');
//        if ($request->has('idcentro_custo') && ($request->get('idcentro_custo') != "")) {
//            $query->where('idcentro_custo', $request->get('idcentro_custo'));
//        }
//        return $query;
//
//
////        $data['idcentro_custo']
//
//        $Buscas = OrdemServico::filter_situacao_centro_custo($request->all())->get();
//        $Buscas = Cliente::whereIn('idcliente', $Buscas->pluck('idcentro_custo'))->get();
//        $this->Page->extras['centro_custos'] = $Buscas;
//        return view('pages.' . $this->Page->link . '.index_centro_custo')
//            ->with('Page', $this->Page)
//            ->with('Buscas', $Buscas);
//    }

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
        $OrdemServico->update_valores();
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
//            if (isset($request['busca'])) {
//                $busca = $request['busca'];
//                $documento = preg_replace('#[^0-9]#', '', $busca);
//                $Buscas = $OrdemServico->cliente->instrumentos()
//                    ->where('idmarca', 'like', '%' . $busca . '%')
//                    ->orwhere('numero_serie', 'like', '%' . $documento . '%')
//                    ->orwhere('descricao', 'like', '%' . $busca . '%')->get();
//            } else {
//                $Buscas = $OrdemServico->cliente->instrumentos;
//            }
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
            $Buscas = Cliente::getValidosOrdemServico()->whereIn('idcliente', function ($query) use ($busca, $documento) {
                $query->select('clientes.idcliente')
                    ->from('clientes')
                    ->join('pjuridicas', 'pjuridicas.idpjuridica', '=', 'clientes.idpjuridica')
                    ->where('pjuridicas.razao_social', 'like', '%' . $busca . '%')
                    ->orwhere('pjuridicas.nome_fantasia', 'like', '%' . $busca . '%');
                if ($documento != '') {
                    $query = $query->orwhere('pjuridicas.cnpj', 'like', '%' . $documento . '%');
                }
            })->orwhereIn('idcliente', function ($query) use ($documento) {
                $query->select('clientes.idcliente')
                    ->from('clientes')
                    ->join('pfisicas', 'pfisicas.idpfisica', '=', 'clientes.idpfisica')
                    ->where('pfisicas.cpf', 'like', '%' . $documento . '%');
            })->paginate(10);
        } else {
            $Buscas = Cliente::getValidosOrdemServico()->paginate(10);
        }
        return view('pages.' . $this->Page->link . '.busca_cliente')
            ->with('Page', $this->Page)
            ->with('Buscas', $Buscas);
    }

    public function abrir($clienteid)
    {
        $Cliente = Cliente::find($clienteid);
        $OrdemServico = OrdemServico::abrir($Cliente, $this->colaborador->idcolaborador);

        session()->forget('mensagem');
        session(['mensagem' => $this->Page->msg_abr]);
        return Redirect::route('ordem_servicos.show', $OrdemServico->idordem_servico);
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
        AparelhoManutencao::create([
            'idordem_servico' => $idordem_servico,
            'idinstrumento' => $idinstrumento
        ]);
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
        AparelhoManutencao::create([
            'idordem_servico' => $idordem_servico,
            'idequipamento' => $idequipamento
        ]);
        session()->forget('mensagem');
        session(['mensagem' => $this->Page->msg_upd]);
        return Redirect::route('ordem_servicos.show', $idordem_servico);
    }

    public function destroy($id)
    {
        $OrdemServico = OrdemServico::find($id);
        $OrdemServico->remover();

        session()->forget('mensagem');
        session(['mensagem' => $this->Page->msg_rem]);
        return Redirect::route('ordem_servicos.index', ['1']);
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
        $AparelhoManutencao = AparelhoManutencao::find($idaparelho_manutencao);
        $idordem_servico = $AparelhoManutencao->idordem_servico;
        $AparelhoManutencao->remover();

        session()->forget('mensagem');
        session(['mensagem' => $this->Page->msg_upd]);
        return Redirect::route('ordem_servicos.show', $idordem_servico);
    }

    public function updateAparelhoManutencao(Request $request, $idaparelho_manutencao)
    {
        $AparelhoManutencao = AparelhoManutencao::find($idaparelho_manutencao);
        $AparelhoManutencao->update([
            'defeito' => $request->get('defeito'),
            'solucao' => $request->get('solucao')
        ]);
        //atualiza o status da O.S.
        $AparelhoManutencao->ordem_servico->update([
            'idsituacao_ordem_servico' => 2
        ]);
        if ($AparelhoManutencao->has_instrumento()) {
            $this->updateInstrumento($request, $AparelhoManutencao);
        }

        session()->forget('mensagem');
        session(['mensagem' => $this->Page->msg_upd]);
        return Redirect::route('ordem_servicos.show', $AparelhoManutencao->idordem_servico);
    }

    public function updateInstrumento(Request $request, AparelhoManutencao $AparelhoManutencao)
    {
        //UPDATE DOS LACRES E SELOS
        //caso não tenha lacre rompido, só atualizar defeito/manutenção
        if ($request->has('lacre_rompido')) {
            $now = Carbon::now()->toDateTimeString();
            //Na primeira vez que o técnico for dar manutenção no instrumento, deverá marcar SELO OUTRO e LACRE OUTRO
//            return $request->all();

            /*** RETIRADA DOS LACRES ***/
            //Nesse caso quer dizer que os lacres está sendo editado pela segunda vez
            if ($request->has('lacres_retirado_hidden')) {
                $lacres_retirados = json_decode($request->get('lacres_retirado_hidden'));
                LacreInstrumento::retirar($lacres_retirados);
            }
//                dd($lacres_retirados);
            //Nesse caso os lacres são externos ou PRIMEIRA vez
            if ($request->has('lacre_outro')) {
                $lacres_retirado = explode(';', trim($request->get('lacre_retirado_livre')));

                foreach ($lacres_retirado as $lacre_retirado) {
                    //Nesse caso, criar um lacre novo na tabela lacress e atribuí-lo ao técnico em questão
                    if (Lacre::lacre_exists($lacre_retirado)) { // Testar para saber se já existe esse selo na base, por segurnaça
                        dd('ERRO: LACRE JÁ EXISTE');
                    }
                    $lacre = Lacre::create([ // se não existir, inserir e retornar o novo id
                        'idtecnico' => $this->tecnico->idtecnico,
                        'numeracao_externa' => $lacre_retirado,
                        'externo' => 1,
                        'used' => 1,
                    ]);
                    //Retirar o selo na tabela SeloInstrumento
                    LacreInstrumento::create([
                        'idlacre' => $lacre->idlacre,
                        'idaparelho_manutencao' => $AparelhoManutencao->idaparelho_manutencao,
                        'idinstrumento' => $AparelhoManutencao->idinstrumento,
                        'afixado_em' => $now,
                        'retirado_em' => $now,
                    ]);
                }
            }

            /*** AFIXAÇAO DOS LACRES ***/
            $idlacres_afixado = $request->get('lacre_afixado');

            //Afixar os lacres na tabela LacreInstrumento
            foreach ($idlacres_afixado as $idlacre_afixado) {
                LacreInstrumento::create([
                    'idlacre' => $idlacre_afixado,
                    'idaparelho_manutencao' => $AparelhoManutencao->idaparelho_manutencao,
                    'idinstrumento' => $AparelhoManutencao->idinstrumento,
                    'afixado_em' => $now,
                ]);
                Lacre::set_used($idlacre_afixado);
            }
            /*** RETIRADA DO SELO ***/
            //Nesse caso quer dizer que o selo está sendo editado pela segunda vez
            if ($request->has('selo_retirado_hidden')) {
                $selo_retirado = $request->get('selo_retirado_hidden');
                $selo = Selo::where('numeracao', $selo_retirado)->first();
                //Nesse caso, criar o SeloInstrumento já existe, vamos atualizar o retirado_em
                $SeloInstrumento = SeloInstrumento::retirar($selo->idselo);
            }
            //Nesse caso o selo é externo ou PRIMEIRA vez
            if ($request->has('selo_outro')) {
                $selo_retirado = $request->get('selo_retirado');
                //Nesse caso, criar um selo novo na tabela selos e atribuí-lo ao técnico em questão
                if (Selo::selo_exists($selo_retirado)) { // Testar para saber se já existe esse selo na base, por segurnaça
                    dd('ERRO: SELO JÁ EXISTE');
                }
                $selo = Selo::create([ // se não existir, inserir e retornar o novo id
                    'idtecnico' => $this->tecnico->idtecnico,
                    'numeracao_externa' => $selo_retirado,
                    'externo' => 1,
                    'used' => 1,
                ]);
                //Retirar o selo na tabela SeloInstrumento
                SeloInstrumento::create([
                    'idselo' => $selo->idselo,
                    'idaparelho_manutencao' => $AparelhoManutencao->idaparelho_manutencao,
                    'idinstrumento' => $AparelhoManutencao->idinstrumento,
                    'afixado_em' => $now,
                    'retirado_em' => $now,
                ]);
            }
            /*** AFIXAÇAO DO SELO ***/
            $idselo_afixado = $request->get('selo_afixado');
            //Afixar o selo na tabela SeloInstrumento
            Selo::set_used($idselo_afixado);
            SeloInstrumento::create([
                'idselo' => $idselo_afixado,
                'idaparelho_manutencao' => $AparelhoManutencao->idaparelho_manutencao,
                'idinstrumento' => $AparelhoManutencao->idinstrumento,
                'afixado_em' => $now,
            ]);
        }
        return;
    }

    public function add_insumos(Request $request, $idordem_servico)
    {
        $idaparelho_manutencao = $request->get('idaparelho_manutencao');
        if ($request->has('idservico_id')) {
            $id = $request->get('idservico_id');
            $valor = $request->get('idservico_valor');
            $quantidade = $request->get('idservico_quantidade');
            $desconto = $request->get('idservico_desconto');
            foreach ($id as $i => $v) {
                $data = [
                    'idaparelho_manutencao' => $idaparelho_manutencao,
                    'idservico' => $id[$i],
                    'valor' => $valor[$i],
                    'quantidade' => $quantidade[$i],
                    'desconto' => $desconto[$i],
                ];
                ServicoPrestado::create($data);
//                $total += DataHelper::getReal2Float($valor[$i]);
            }
        }
        if ($request->has('idpeca_id')) {
            $id = $request->get('idpeca_id');
            $valor = $request->get('idpeca_valor');
            $quantidade = $request->get('idpeca_quantidade');
            $desconto = $request->get('idpeca_desconto');
            foreach ($id as $i => $v) {
                $data = [
                    'idaparelho_manutencao' => $idaparelho_manutencao,
                    'idpeca' => $id[$i],
                    'valor' => $valor[$i],
                    'quantidade' => $quantidade[$i],
                    'desconto' => $desconto[$i],
                ];
                PecasUtilizadas::create($data);
//                $total += DataHelper::getReal2Float($valor[$i]);
            }
        }
        if ($request->has('idkit_id')) {
            $id = $request->get('idkit_id');
            $valor = $request->get('idkit_valor');
            $quantidade = $request->get('idkit_quantidade');
            $desconto = $request->get('idkit_desconto');
            foreach ($id as $i => $v) {
                $data = [
                    'idaparelho_manutencao' => $idaparelho_manutencao,
                    'idkit' => $id[$i],
                    'valor' => $valor[$i],
                    'quantidade' => $quantidade[$i],
                    'desconto' => $desconto[$i],
                ];
                KitsUtilizados::create($data);
//                $total += DataHelper::getReal2Float($valor[$i]);
            }
        }
        return Redirect::route('ordem_servicos.show', $idordem_servico);
    }

    public function aplicarValores(Request $request, $idordem_servico)
    {
        $OrdemServico = OrdemServico::find($idordem_servico);
        $OrdemServico->aplicaValores($request->all());
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
        $OrdemServico->finalizar($request->all());
        session()->forget('mensagem');
        session(['mensagem' => $this->Page->msg_fec]);
        return Redirect::route('ordem_servicos.resumo', $OrdemServico->idordem_servico);
    }

    public function imprimir($idordem_servico)
    {
        $PrintHelper = new PrintHelper();
        return $PrintHelper->printOS($idordem_servico);
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

    public function resumo($idordem_servico)
    {
        return view('pages.' . $this->Page->link . '.resumo')
            ->with('Page', $this->Page)
            ->with('OrdemServico', OrdemServico::find($idordem_servico));
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

    public function get_ordem_servicos_colaborador(Request $request, $idcliente)
    {
        return 'EM BREVE, =]';
    }

}

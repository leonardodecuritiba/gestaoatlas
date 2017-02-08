<?php

namespace App\Http\Controllers;

use App\AparelhoManutencao;
use App\Cliente;
use App\Colaborador;
use App\Helpers\DataHelper;
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
use Maatwebsite\Excel\Facades\Excel;
use Validator;
use Illuminate\Http\Request;

use App\Http\Requests;

class OrdemServicoController extends Controller
{
    private $Page;
    private $colaborador;
    private $tecnico;
    private $linha_xls;

    public function __construct()
    {
        $this->colaborador = Auth::user()->colaborador;
        $this->tecnico = $this->colaborador->tecnico;
        $this->Page = (object)[
            'table' => "ordem_servicos",
            'link' => "ordem_servicos",
            'primaryKey' => "idordem_servico",
            'Target' => "Ordem de Serviço",
            'Search' => "Buscar por CPF, CNPJ, Nome Fantasia ou Razão Social...",
            'Search_instrumento' => "Buscar Instrumento/Equipamento por Marca, Nº Série ou Descrição ...",
            'Targets' => "Ordem de Serviços",
            'Titulo' => "Ordem de Serviços",
            'search_no_results' => "Nenhuma Ordem de Serviço encontrada!",
            'msg_abr' => 'Ordem de Serviço aberta com sucesso!',
            'msg_fec' => 'Ordem de Serviço fechada com sucesso!',
            'msg_upd' => 'Ordem de Serviço atualizada com sucesso!',
            'msg_rem' => 'Ordem de Serviço removida com sucesso!',
            'titulo_primario' => "",
            'titulo_secundario' => "",
        ];
    }


    public function index(Request $request, $situacao_ordem_servico)
    {

//        if (isset($request['busca'])) {
//            $busca = $request['busca'];
//            $Buscas = OrdemServico::paginate(10)->orderBy('created_at','asc');
//        } else {
//            $Buscas = OrdemServico::paginate(10);
//        }
        $Buscas = OrdemServico::filter_situacao($situacao_ordem_servico)->paginate(10);
        return view('pages.' . $this->Page->link . '.index')
            ->with('Page', $this->Page)
            ->with('Buscas', $Buscas);
    }

    public function index_centro_custo(Request $request, $situacao_ordem_servico)
    {
        $ids = OrdemServico::filter_situacao($situacao_ordem_servico)
            ->whereNotNull('idcentro_custo')
            ->groupBy('idcentro_custo')
            ->pluck('idcentro_custo');
        $Buscas = Cliente::whereIn('idcliente', $ids)->paginate(10);
        return view('pages.' . $this->Page->link . '.index_centro_custo')
            ->with('Page', $this->Page)
            ->with('Buscas', $Buscas);
    }

    public function show_centro_custo(Request $request, $situacao_ordem_servico, $idcentro_custo)
    {
        //atualizando o valor total da OS
        $Buscas = OrdemServico::centro_custo_os($idcentro_custo, $situacao_ordem_servico)->paginate(10);
        $CentroCusto = Cliente::find($idcentro_custo);
        return view('pages.' . $this->Page->link . '.show_centro_custo')
            ->with('Page', $this->Page)
            ->with('CentroCusto', $CentroCusto)
            ->with('Buscas', $Buscas);
    }

    public function show(Request $request, $idordem_servico)
    {
        //atualizando o valor total da OS
        $OrdemServico = OrdemServico::find($idordem_servico);
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
            if (isset($request['busca'])) {
                $busca = $request['busca'];
                $documento = preg_replace('#[^0-9]#', '', $busca);
                $Buscas = $OrdemServico->cliente->instrumentos()
                    ->where('idmarca', 'like', '%' . $busca . '%')
                    ->orwhere('numero_serie', 'like', '%' . $documento . '%')
                    ->orwhere('descricao', 'like', '%' . $busca . '%')->get();
            } else {
                $Buscas = $OrdemServico->cliente->instrumentos;
            }
            return view('pages.' . $this->Page->link . '.show')
                ->with('Page', $this->Page)
                ->with('OrdemServico', $OrdemServico)
                ->with('Servicos', $Servicos)
                ->with('Pecas', $Pecas)
                ->with('Kits', $Kits)
                ->with('Buscas', $Buscas);
        }
        return redirect()->route('ordem_servicos.resumo', $idordem_servico);
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
            })->get();
        } else {
            $Buscas = Cliente::getValidosOrdemServico()->get();
        }
        return view('pages.' . $this->Page->link . '.busca_cliente')
            ->with('Page', $this->Page)
            ->with('Buscas', $Buscas);
    }

    public function abrir($clienteid)
    {
        $Cliente = Cliente::find($clienteid);
        $data = [
            'idcliente'                 => $clienteid,
            'idcolaborador' => $this->colaborador->idcolaborador,
            'idcentro_custo' => $Cliente->idcliente_centro_custo,
            'custos_deslocamento'       => $Cliente->custo_deslocamento(),
            'pedagios'                  => $Cliente->pedagios,
            'outros_custos'             => $Cliente->outros_custos,
            'idsituacao_ordem_servico'  => 1
        ];
        $OrdemServico = OrdemServico::create($data);
        session()->forget('mensagem');
        session(['mensagem' => $this->Page->msg_abr]);
        return redirect()->route('ordem_servicos.show', $OrdemServico->idordem_servico);
    }

    public function adicionaInstrumento(Request $request, $idordem_servico, $idinstrumento)
    {
        //teste se já foi adicionado
        if (AparelhoManutencao::check_equip_duplo($idordem_servico, $idinstrumento)) {
            $erro = 'Esse instrumento já está incluído nesta ordem de serviço!';
            return redirect()->route('ordem_servicos.show', $idordem_servico)
                ->withErrors($erro)
                ->withInput($request->all());
        }
        AparelhoManutencao::create([
            'idordem_servico' => $idordem_servico,
            'idinstrumento' => $idinstrumento
        ]);
        session()->forget('mensagem');
        session(['mensagem' => $this->Page->msg_upd]);
        return redirect()->route('ordem_servicos.show', $idordem_servico);
    }

    public function destroy($id)
    {
        $OrdemServico = OrdemServico::find($id);
        $OrdemServico->remover();

        session()->forget('mensagem');
        session(['mensagem' => $this->Page->msg_rem]);
        return redirect()->route('ordem_servicos.index');
    }

    public function removeInstrumento($idaparelho_manutencao)
    {
        $AparelhoManutencao = AparelhoManutencao::find($idaparelho_manutencao);
        $idordem_servico = $AparelhoManutencao->idordem_servico;
        $AparelhoManutencao->remover();

        session()->forget('mensagem');
        session(['mensagem' => $this->Page->msg_upd]);
        return redirect()->route('ordem_servicos.show', $idordem_servico);
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
        return redirect()->route('ordem_servicos.show', $AparelhoManutencao->idordem_servico);
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
            foreach ($id as $i => $v) {
                $data = [
                    'idaparelho_manutencao' => $idaparelho_manutencao,
                    'idservico' => $id[$i],
                    'valor' => $valor[$i],
                ];
                ServicoPrestado::create($data);
//                $total += DataHelper::getReal2Float($valor[$i]);
            }
        }
        if ($request->has('idpeca_id')) {
            $id = $request->get('idpeca_id');
            $valor = $request->get('idpeca_valor');
            foreach ($id as $i => $v) {
                $data = [
                    'idaparelho_manutencao' => $idaparelho_manutencao,
                    'idpeca' => $id[$i],
                    'valor' => $valor[$i],
                ];
                PecasUtilizadas::create($data);
//                $total += DataHelper::getReal2Float($valor[$i]);
            }
        }
        if ($request->has('idkit_id')) {
            $id = $request->get('idkit_id');
            $valor = $request->get('idkit_valor');
            foreach ($id as $i => $v) {
                $data = [
                    'idaparelho_manutencao' => $idaparelho_manutencao,
                    'idkit' => $id[$i],
                    'valor' => $valor[$i],
                ];
                KitsUtilizados::create($data);
//                $total += DataHelper::getReal2Float($valor[$i]);
            }
        }
        return redirect()->route('ordem_servicos.show', $idordem_servico);
    }

    public function fechar(Request $request, $idordem_servico)
    {
        $OrdemServico = OrdemServico::find($idordem_servico);
        $OrdemServico->fechar($request->get('numero_chamado'));
        session()->forget('mensagem');
        session(['mensagem' => $this->Page->msg_fec]);
        return redirect()->route('ordem_servicos.resumo', $OrdemServico->idordem_servico);
    }

    public function imprimir(Request $request, $idordem_servico)
    {

        $OrdemServico = OrdemServico::find($idordem_servico);
        $Cliente = $OrdemServico->cliente;
        $atlas = array(
            'endereco' => 'Rua Triunfo, 400',
            'bairro' => 'Santa Cruz',
            'cidade' => 'Ribeirão Preto',
            'cep' => '14020-670',
            'cnpj' => '10.555.180/0001-21',
            'razao_social' => 'MACEDO AUTOMAÇAO COMERCIAL LTDA',
            'ie' => '797.146.934.117',
            'n_autorizacao' => '10002180',
            'fone' => '(16)3011-8448',
            'email' => 'os@atlastecnologia.com.br');
        $empresa = array(
            'nome' => 'ORDEM DE SERVIÇO - #' . $OrdemServico->idordem_servico,
            'descricao' => 'Manutenção e venda de equipamentos de automação comercial',
            'dados' => $atlas,
            'logo' => public_path('uploads/institucional/logo_atlas.png'),
        );
        $aviso_txt = ['ASSINATURA: CLIENTE CONFIRMA A EXECUÇÃO DOS SERVIÇOS E TROCA DE PEÇAS ACIMA SITADOS, E TAMBEM APROVA OS PREÇOS COBRADOS. INSTRUMENTOS - EQUIPAMANTOS DEIXADOS POR CLIENTES NA EMPRESA: O CLIENTE AUTORIZA PREVIA E EXPRESSAMANTE UMA VEZ QUE ORÇAMANTOS NÃO FOREM APROVADOS A NÃO RETIRADA DOS INSTRUMENTOS - EQUIPAMANTOS NO PRAZO DE 90 DIAS DA ASSINATURA DESSA ORDEM OS MESMOS SERÃO DESCARTADOS PARA LIXO OU SUCATA.'];

        if ($Cliente->is_pjuridica()) {
            //empresa
            $Pessoa_juridica = $Cliente->pessoa_juridica;
            $Contato = $Cliente->contato;

            $dados_cliente = [
                array(
                    'Cliente / Razão Social:', $Pessoa_juridica->razao_social,
                    'Fantasia:', $Pessoa_juridica->nome_fantasia,
                    'HR / DATA I', $OrdemServico->created_at
                ),
                array(
                    'CNPJ:', $Pessoa_juridica->cnpj,
                    'I.E:', $Pessoa_juridica->ie,
                    'DATA / HR F', $OrdemServico->fechamento
                ),
                array(
                    'Endereço:', $Contato->getRua(),
                    'CEP: ' . $Contato->cep, 'UF: ' . $Contato->estado,
                    'Cidade: ' . $Contato->cidade
                ),
                array(
                    'Telefone:', $Contato->telefone,
                    'Contato:', $Cliente->nome_responsavel
                ),
                array(
                    'Email:', $Cliente->email_nota,
                    'Nº Chamado Sist. Cliente:', $OrdemServico->numero_chamado
                ),
            ];
        } else {
            $dados_cliente = [
                array('Cliente / Razão Social:', 'Cliente sem CNPJ',
                    'Fantasia:', 'Cliente sem CNPJ',
                    'DATA / HR I', '31/10/2016 - 10:00'
                ),
                array('CNPJ:', '10.555.180/0001-21',
                    'I.E:', '222.222.222.222',
                    'DATA / HR F', '31/10/2016 - 13:00'
                ),
                array('Endereço:', 'Rua Platina, 121',
                    'CEP: 14020-670', 'UF: SP',
                    'Cidade: Ribeirão Preto'
                ),
                array('Telefone:', '(16)3329-4365'
                ),
                array('Contato:', 'Willian/ Daniela'
                ),
                array('Email:', 'financeiro@atlastecnologia.com.br',
                    'Nº Chamado Sist. Cliente:', '12 -13 -14 -15'
                ),
            ];
        }


        $font = [
            'nome' => array(
                'family' => 'Bookman Old Style',
                'size' => '20',
            ),
            'descricao' => array(
                'size' => '12',
                'bold' => true
            ),
            'endereco' => array(
                'size' => '9'
            ),
            'quebra' => array(
                'size' => '12',
                'bold' => true
            )
        ];

        $data = [
            'empresa' => $empresa,
            'dados_cliente' => $dados_cliente,
            'ordem_servico' => $OrdemServico,
            'aviso_txt' => $aviso_txt,
            'fonts' => $font
        ];

        $now = Carbon::now()->format('H-i_d-m-Y');
        Excel::create('OrdemServico_' . $now, function ($excel) use ($data) {

//            dd($data['empresa']['cabecalho']);
            $excel->sheet('Sheetname', function ($sheet) use ($data) {
                $sheet->setPageMargin(0.25);

                $cabecalho = $data['empresa']['dados'];

                $sheet->mergeCells('A1:B1');
                $sheet->mergeCells('A2:B2');

                $sheet->cell('A1', function ($cell) use ($data) {
                    // manipulate the cell
                    $cell->setValue(strtoupper($data['empresa']['nome']));
                    $cell->setFont($data['fonts']['nome']);
                    $cell->setFontFamily('Bookman Old Style');
                });
                $sheet->cell('A2', function ($cell) use ($data) {
                    // manipulate the cell
                    $cell->setValue($data['empresa']['descricao']);
                    $cell->setFont($data['fonts']['descricao']);
                });

                $sheet->rows(array(
                    array($cabecalho['razao_social'] . ' / CNPJ: ' . $cabecalho['cnpj']),
                    array('I.E: ' . $cabecalho['ie']),
                    array('N° de Autorização: ' . $cabecalho['n_autorizacao']),
                    array($cabecalho['endereco'] . ' - ' . $cabecalho['bairro'] . ' - CEP: ' . $cabecalho['bairro']),
                    array('Fone: ' . $cabecalho['fone']),
                    array('E-mail: ' . $cabecalho['email']),
                ));
                $sheet->mergeCells('A3:B3');
                $sheet->mergeCells('A4:B4');
                $sheet->mergeCells('A5:B5');
                $sheet->mergeCells('A6:B6');
                $sheet->mergeCells('A7:B7');
                $sheet->mergeCells('A8:B8');
                $sheet->cells('A3:B8', function ($cells) use ($data) {
                    // manipulate the range of cells
                    $cells->setFont($data['fonts']['endereco']);
                });

                $sheet->mergeCells('C1:G8');
                $objDrawing = new \PHPExcel_Worksheet_Drawing();
                $objDrawing->setPath($data['empresa']['logo']); //your image path
                $objDrawing->setCoordinates('C1');
                $objDrawing->setWorksheet($sheet);


                //QUEBRA -------------------------------------------------------------------------------
                //********************************************************************************************//
                //********************************************************************************************//
                $OrdemServico = $data['ordem_servico'];
                $this->linha_xls = 9;
                $info = ['Ordem Serviço n° ' . $OrdemServico->idordem_servico];
                $sheet->mergeCells('A' . $this->linha_xls . ':G' . $this->linha_xls);
                $sheet->row($this->linha_xls, function ($row) use ($data) {
                    // call cell manipulation methods
                    $row->setBackground('#d9d9d9');
                    $row->setAlignment('center');
                    $row->setFont($data['fonts']['quebra']);
                });
                $sheet->row($this->linha_xls, $info);
                $this->linha_xls++;
                //\QUEBRA ------------------------------------------

                //CABEÇALHO DADOS CLIENTE
                $sheet->rows($data['dados_cliente']);
                $this->linha_xls += 6;

                //********************************************************************************************//
                //INSTRUMENTOS ------------------------------------------
                $InstrumentosManutencao = $OrdemServico->instrumentos_manutencao;
                foreach ($InstrumentosManutencao as $instrumentoManutencao) {
                    $Instrumento = $instrumentoManutencao->instrumento;
                    $dados_instrumento = [
                        array(
                            'Marca: ' . $Instrumento->marca->descricao,
                            'Modelo: ' . $Instrumento->modelo,
                            'N° de Série: ' . $Instrumento->numero_serie,
                            'Patrimônio: ' . $Instrumento->patrimonio,
                            'Ano: ' . $Instrumento->ano,
                            'Inventário: ' . $Instrumento->inventario
                        ),
                        array(
                            'Portaria: ' . $Instrumento->portaria,
                            'Capacidade: ' . $Instrumento->capacidade,
                            'Divisão: ' . $Instrumento->divisao,
                            'Setor: ' . $Instrumento->setor,
                            'IP: ' . $Instrumento->ip,
                            'Endereço: ' . $Instrumento->endereco
                        )
                    ];
                    $selo_lacre = [
                        array(
//                            'Selo retirado: '.$Instrumento->selo_afixado()->numeracao,
                            'Selo Afixado: ' . $Instrumento->selo_afixado()->numeracao,
//                            'Lacres Retirados: '.$Instrumento->selo_afixado()->numeracao,
                            'Lacres Afixados: ' . $Instrumento->lacres_afixados_valores()
                        )
                    ];
                    $defeitos_solucao = [
                        array(
                            'Defeito:', $instrumentoManutencao->defeito,
                            '',
                            'Solução:', $instrumentoManutencao->solucao
                        )
                    ];
                    $instrumento = [
                        'line' => $this->linha_xls,
                        'info' => ['Instrumento'],
                        'dados_instrumentos' => $dados_instrumento,
                        'selo_lacre' => $selo_lacre,
                        'defeito_solucao' => $defeitos_solucao,
                    ];
                    $this->linha_xls += 5;
                    $sheet = self::setInstrumento($sheet, $data, $instrumento);

                    //********************************************************************************************//
                    //************************** PEÇAS ***********************************************************//
                    //********************************************************************************************//
                    if ($instrumentoManutencao->has_pecas_utilizadas()) {
                        $total = 0;
                        foreach ($instrumentoManutencao->pecas_utilizadas as $Peca_utilizada) {
                            $Peca = $Peca_utilizada->peca;
//                            $tabela_preco   = $Peca->tabela_cliente($OrdemServico->cliente->idtabela_preco);
                            $data['pecas'][] = [
                                $Peca->idpeca,
                                $Peca->descricao,
                                '1',
                                'R$ ' . $Peca_utilizada->valor,
                                'R$ ' . $Peca_utilizada->valor,
                                '-',
                                '-'
                            ];
                            $total += $Peca_utilizada->valor_float();
                        }
                        $data['total_pecas'] = 'R$ ' . DataHelper::getFloat2Real($total);
                        $cabecalho = [
                            'line' => $this->linha_xls,
                            'info' => ['Peças'],
                            'cabecalho' => ['Codigo', 'Peça', 'Qtde', 'V. un', 'V. Total', 'Garantia', 'Garantia Negada'],
                            'values' => $data['pecas'],
                        ];
                        $sheet = self::setData($sheet, $data, $cabecalho);
                        $this->linha_xls += count($data['pecas']) + 2;
                    }
                    //********************************************************************************************//
                    //************************** KITS ************************************************************//
                    //********************************************************************************************//
                    if ($instrumentoManutencao->has_kits_utilizados()) {
                        $total = 0;
                        foreach ($instrumentoManutencao->kits_utilizados as $Kit_utilizado) {
                            $Kit = $Kit_utilizado->kit;
//                            $tabela_preco   = $Peca->tabela_cliente($OrdemServico->cliente->idtabela_preco);
                            $data['kits'][] = [
                                $Kit->idkit,
                                $Kit->descricao,
                                '1',
                                'R$ ' . $Kit_utilizado->valor,
                                'R$ ' . $Kit_utilizado->valor,
                                '-',
                                '-'
                            ];
                            $total += $Kit_utilizado->valor_float();
                        }
                        $data['total_kits'] = 'R$ ' . DataHelper::getFloat2Real($total);;
                        $cabecalho = [
                            'line' => $this->linha_xls,
                            'info' => ['Kits'],
                            'cabecalho' => ['Codigo', 'Peça', 'Qtde', 'V. un', 'V. Total', 'Garantia', 'Garantia Negada'],
                            'values' => $data['kits'],
                        ];
                        $sheet = self::setData($sheet, $data, $cabecalho);
                        $this->linha_xls += count($data['kits']) + 2;
                    }
                    //********************************************************************************************//
                    //************************** SERVIÇOS ********************************************************//
                    //********************************************************************************************//
                    if ($instrumentoManutencao->has_servico_prestados()) {
                        $total = 0;
                        foreach ($instrumentoManutencao->servico_prestados as $Servico_prestados) {
                            $Servico = $Servico_prestados->servico;
//                            $tabela_preco   = $Peca->tabela_cliente($OrdemServico->cliente->idtabela_preco);
                            $data['servicos'][] = [
                                $Servico->idservico,
                                $Servico->descricao,
                                '1',
                                'R$ ' . $Servico_prestados->valor,
                                'R$ ' . $Servico_prestados->valor
                            ];
                            $total += $Servico_prestados->valor_float();
                        }
                        $data['total_servicos'] = 'R$ ' . DataHelper::getFloat2Real($total);;
                        $cabecalho = [
                            'line' => $this->linha_xls,
                            'info' => ['Serviços'],
                            'cabecalho' => ['Codigo', 'Kit', 'Qtde', 'V. un', 'V. Total'],
                            'values' => $data['servicos'],
                        ];
                        $sheet = self::setData($sheet, $data, $cabecalho);
                        $this->linha_xls += count($data['servicos']) + 2;
                    }
                }

                //********************************************************************************************//
                //************************** FECHAMENTO ******************************************************//
                //********************************************************************************************//
                $this->linha_xls++;
                $cabecalho = [
                    'line' => $this->linha_xls,
                    'info' => ['Fechamento de Valores'],
                ];
                $sheet = self::setCabecalho($sheet, $data, $cabecalho);

                //************************* PEÇAS ***********************************************************//
                if (isset($data['pecas'])) {
                    $this->linha_xls += 2;
                    $cabecalho = [
                        'line' => $this->linha_xls,
                        'info' => ['Peças'],
                        'cabecalho' => ['Codigo', 'Peça', 'Qtde', 'V. un', 'V. Total', 'Garantia', 'Garantia Negada'],
                        'values' => $data['pecas'],
                    ];
                    $sheet = self::setData($sheet, $data, $cabecalho);
                    $this->linha_xls += count($data['pecas']) + 2;

                    //total
                    $sheet->cell('E' . $this->linha_xls, function ($cell) use ($data) {
                        $cell->setFontWeight(true);
                        $cell->setValue($data['total_pecas']);
                    });
                }
                //************************* KITS ***********************************************************//
                if (isset($data['kits'])) {
                    $this->linha_xls += 2;
                    $cabecalho = [
                        'line' => $this->linha_xls,
                        'info' => ['Kits'],
                        'cabecalho' => ['Codigo', 'Peça', 'Qtde', 'V. un', 'V. Total', 'Garantia', 'Garantia Negada'],
                        'values' => $data['kits'],
                    ];
                    $sheet = self::setData($sheet, $data, $cabecalho);
                    $this->linha_xls += count($data['kits']) + 2;

                    //total
                    $sheet->cell('E' . $this->linha_xls, function ($cell) use ($data) {
                        $cell->setFontWeight(true);
                        $cell->setValue($data['total_kits']);
                    });
                }
                //************************* SERVIÇOS *******************************************************//
                if (isset($data['servicos'])) {
                    $this->linha_xls += 2;
                    $cabecalho = [
                        'line' => $this->linha_xls,
                        'info' => ['Serviços'],
                        'cabecalho' => ['Codigo', 'Kit', 'Qtde', 'V. un', 'V. Total'],
                        'values' => $data['servicos'],
                    ];
                    $sheet = self::setData($sheet, $data, $cabecalho);
                    $this->linha_xls += count($data['servicos']) + 2;

                    //total
                    $sheet->cell('E' . $this->linha_xls, function ($cell) use ($data) {
                        $cell->setFontWeight(true);
                        $cell->setValue($data['total_servicos']);
                    });
                }
                //************************* OUTROS *********************************************************//
                $dados_fechamento = [
                    array('Deslocamento', '', '', '', 'R$ ' . $OrdemServico->custos_deslocamento),
                    array('Pedagios', '', '', '', 'R$ ' . $OrdemServico->pedagios),
                    array('Outros Custos', '', '', '', 'R$ ' . $OrdemServico->outros_custos),
                ];
                $data['outros'] = $dados_fechamento;
                //
                $this->linha_xls += 2;
                $cabecalho = [
                    'line' => $this->linha_xls,
                    'info' => ['Outros'],
                    'cabecalho' => ['Descrição', '', '', '', 'V. Total'],
                    'values' => $data['outros'],
                ];
                $sheet = self::setData($sheet, $data, $cabecalho);
                $this->linha_xls += count($data['outros']) + 3;

                //************************* FECHAMENTO FINAL *********************************************//
                $sheet->row($this->linha_xls, function ($row) {
                    $row->setFontWeight(true);
                });
                $sheet->row($this->linha_xls, ['TOTAL  DA ORDEM SERVIÇO', '', '', '', 'R$ ' . $OrdemServico->valor_final]);
                $this->linha_xls++;
                $sheet->row($this->linha_xls, ['TÉCNICO', '', '', '', $OrdemServico->colaborador->nome]);
                $this->linha_xls += 2;

                $sheet->mergeCells('A' . $this->linha_xls . ':G' . $this->linha_xls);
                $sheet->row($this->linha_xls, $data['aviso_txt']);
//:	RONI CAMARGO RG:26148976
//RESPONSAVEL PELO ESTABELECIMENTO:	WILLIAN T MACEDO RG: 14079508 	ASSINATURA - CARIMBO - FOTO


            });

        })->export('xls');
        return 'imprimir';

        $OrdemServico = OrdemServico::find($idordem_servico);
        $OrdemServico->update([
            'fechamento' => Carbon::now()->toDateTimeString()
        ]);
        session()->forget('mensagem');
        session(['mensagem' => $this->Page->msg_fec]);
        return redirect()->route('ordem_servicos.resumo', $idordem_servico);
    }

//    static public function setLinhaVazia($sheet, $texto)
//    {
//        $linha = $dados['line'];
//        $sheet->mergeCells('A' . $linha . ':G' . $linha);
//        $sheet->row($linha, function ($row) use ($data) {
//            // call cell manipulation methods
//            $row->setBackground('#d9d9d9');
//            $row->setAlignment('center');
//            $row->setFont($data['fonts']['quebra']);
//        });
//        $sheet->row($linha, $dados['info']);
//
//        $sheet->rows($dados['dados_instrumentos']);
//        $sheet->rows($dados['selo_lacre']);
//        $sheet->rows($dados['defeito_solucao']);
//
//        $linha += 4;
//        $sheet->mergeCells('B' . $linha . ':C' . $linha);
//        $sheet->mergeCells('E' . $linha . ':G' . $linha);
//        return $sheet;
//    }

    static public function setInstrumento($sheet, $data, $dados)
    {
        //LINHA ------------------------------------------
        $sheet = self::setCabecalho($sheet, $data, $dados);
        //CABEÇALHO ------------------------------------------
        $linha = $dados['line']++;
        $sheet->rows($dados['dados_instrumentos']);
        $sheet->rows($dados['selo_lacre']);
        $sheet->rows($dados['defeito_solucao']);

        $linha += 4;
        $sheet->mergeCells('B' . $linha . ':C' . $linha);
        $sheet->mergeCells('E' . $linha . ':G' . $linha);
        return $sheet;
    }

    static public function setCabecalho($sheet, $data, $dados)
    {
        $linha = $dados['line'];
        //LINHA ------------------------------------------
        $sheet->mergeCells('A' . $linha . ':G' . $linha);
        $sheet->row($linha, function ($row) use ($data) {
            $row->setBackground('#d9d9d9');
            $row->setAlignment('center');
            $row->setFont($data['fonts']['quebra']);
        });
        $sheet->row($linha, $dados['info']);
        return $sheet;
    }

    static public function setData($sheet, $data, $dados)
    {
        //LINHA ------------------------------------------
        $sheet = self::setCabecalho($sheet, $data, $dados);
        //CABEÇALHO ------------------------------------------
        $linha = $dados['line'] + 1;
        $sheet->row($linha, function ($row) {
            $row->setFontWeight(true);
        });
        $sheet->row($linha, $dados['cabecalho']);

        // DADOS ------------------------------------------
        $sheet->rows($dados['values']);
        return $sheet;
    }

    public function encaminhar(Request $request, $idordem_servico)
    {
        return 'encaminhar por email';
        $OrdemServico = OrdemServico::find($idordem_servico);
        $OrdemServico->update([
            'fechamento' => Carbon::now()->toDateTimeString()
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

    /*
   public function store(Request $request)
   {
       $validator = Validator::make($request->all(), [
           'descricao'         => 'required|unique:'.$this->Page->table,
       ]);
       if ($validator->fails()) {
           return redirect()->to($this->getRedirectUrl())
               ->withErrors($validator)
               ->withInput($request->all());
       } else {
           $data = $request->all();
           $OrdemServico = OrdemServico::create($data);
           session()->forget('mensagem');
           session(['mensagem' => $this->Page->msg_abr]);
           return view('pages.'.$this->Page->link.'.master')
               ->with('OrdemServico', $OrdemServico)
               ->with('Page', $this->Page);
       }
   }

   public function update(Request $request, $id)
   {
       $OrdemServico = OrdemServico::find($id);
       $validator = Validator::make($request->all(), [
           'descricao' => 'unique:'.$this->Page->table.',descricao,'.$id.','.$this->Page->primaryKey,
       ]);

       if ($validator->fails()) {
           return redirect()->to($this->getRedirectUrl())
               ->withErrors($validator)
               ->withInput($request->all());
       } else {
           $dataUpdate = $request->all();
           $OrdemServico->update($dataUpdate);

           session()->forget('mensagem');
           session(['mensagem' => $this->Page->msg_upd]);
           return $this->show($OrdemServico->idordem_servico);
       }
   }
   */


}

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

    public function index(Request $request)
    {
        if (isset($request['busca'])) {
            $busca = $request['busca'];
            $Buscas = OrdemServico::paginate(10);
        } else {
            $Buscas = OrdemServico::paginate(10);
        }
        return view('pages.' . $this->Page->link . '.index')
            ->with('Page', $this->Page)
            ->with('Buscas', $Buscas);
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
        //teste se já
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

    public function updateAparelhoManutencao(Request $request, $idaparelho_manutencao)
    {
        $AparelhoManutencao = AparelhoManutencao::find($idaparelho_manutencao);
        $AparelhoManutencao->update([
            'defeito' => $request->get('defeito'),
            'solucao' => $request->get('solucao'),
            'idsituacao_ordem_servico' => 2
        ]);
        if ($AparelhoManutencao->has_instrumento()) {
            $this->updateInstrumento($request, $AparelhoManutencao->idinstrumento);
        }

        session()->forget('mensagem');
        session(['mensagem' => $this->Page->msg_upd]);
        return redirect()->route('ordem_servicos.show', $AparelhoManutencao->idordem_servico);
    }

    public function updateInstrumento(Request $request, $idinstrumento)
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
                        'idinstrumento' => $idinstrumento,
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
                    'idinstrumento' => $idinstrumento,
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
                    'idinstrumento' => $idinstrumento,
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
                'idinstrumento' => $idinstrumento,
                'afixado_em' => $now,
            ]);

        }
        return;
    }

    public function show(Request $request, $idordem_servico)
    {
        return $this->buscaInstrumentos($request, $idordem_servico);
    }

    public function buscaInstrumentos(Request $request, $idordem_servico)
    {
        $OrdemServico = OrdemServico::find($idordem_servico);
        $this->Page->search_no_results = "Nenhum Instrumento encontrado!";
        if ($OrdemServico->idsituacao_ordem_servico == 1) {
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

    public function fechar(Request $request, $idordem_servico)
    {
        $OrdemServico = OrdemServico::find($idordem_servico);
        $OrdemServico->update([
            'numero_chamado'            => $request->get('numero_chamado'),
            'fechamento' => Carbon::now()->toDateTimeString(),
            'idsituacao_ordem_servico' => 3,
        ]);
        session()->forget('mensagem');
        session(['mensagem' => $this->Page->msg_fec]);
        return redirect()->route('ordem_servicos.resumo', $OrdemServico->idordem_servico);
    }

    public function imprimir(Request $request, $idordem_servico)
    {
        $atlas = array(
            'endereco' => 'Rua Triunfo, 400',
            'bairro' => 'Santa Cruz',
            'cidade' => 'Ribeirão Preto',
            'cep' => '14020-670',
            'cnpj' => '10.555.180/0001-21',
            'ie' => '797.146.934.117',
            'n_autorizacao' => '10002180',
            'fone' => '(16)3011-8448',
            'email' => 'comercial@hotmail.com.br');

        $empresa = array(
            'nome' => 'Grupo Atlas Tecnologia',
            'descricao' => 'Manutenção e venda de equipamentos de automação comercial',
            'dados_texto' =>
                $atlas['endereco'] . ' - ' . $atlas['bairro'] . '\n' .
                $atlas['cidade'] . ' - CEP ' . $atlas['cep'] . '\n' .
                'CNPJ: ' . $atlas['cnpj'] . '\n' .
                'I.E: ' . $atlas['ie'] . '\n' .
                'N° de Autorização: ' . $atlas['n_autorizacao'] . '\n' .
                'Fone: ' . $atlas['fone'] . '\n' .
                'E-mail: ' . $atlas['email'] . '\n',
            'dados' => $atlas,
            'logo' => storage_path('uploads\institucional\logo_atlas.png'),
        );

        $dados_cliente = [
            array('Cliente / Razão Social:', 'Macedo automação Comercial -ME',
                'Fantasia:', 'Grupo Atlas Tecnologia',
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

        $font = [
            'nome' => array(
                'family' => 'Bookman Old Style',
                'size' => '24',
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
            'fonts' => $font
        ];

        Excel::create('Filename', function ($excel) use ($data) {

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
                    array($cabecalho['endereco'] . ' - ' . $cabecalho['bairro']),
                    array('CNPJ: ' . $cabecalho['cnpj']),
                    array('I.E: ' . $cabecalho['ie']),
                    array('N° de Autorização: ' . $cabecalho['n_autorizacao']),
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


                //QUEBRA ------------------------------------------
                $linha = 9;
                $info = ['Ordem Serviço n° 631288'];
                $sheet->mergeCells('A' . $linha . ':G' . $linha);
                $sheet->row($linha, function ($row) use ($data) {
                    // call cell manipulation methods
                    $row->setBackground('#d9d9d9');
                    $row->setAlignment('center');
                    $row->setFont($data['fonts']['quebra']);
                });
                $sheet->row($linha, $info);
                //\QUEBRA ------------------------------------------

                //CABEÇALHO DADOS CLIENTE
                $sheet->rows($data['dados_cliente']);

                //********************************************************************************************//
                //QUEBRA ------------------------------------------
                //\QUEBRA ------------------------------------------

                //INSTRUMENTOS ------------------------------------------
                $dados_instrumento = [
                    array('Marca: Toledo', 'Modelo: Prix 5 Plus', 'N° de Série: 123456', 'Patrimônio: 123154', 'Ano: 2008', 'Inventário: 123131'),
                    array('Portaria: 12220', 'Capacidade: 10KG', 'Divisão: 101/12', 'Setor: 12', 'IP: 100.100.100.1', 'Endereço: 100')
                ];
                $selo_lacre = [
                    array('Selo retirado: 12345',
                        'Selo Afixado: 122211',
                        'Lacres Retirados: 123;111;102',
                        'Lacres Afixados: 10222;222;233')
                ];
                $defeitos_solucao = [
                    array('Defeito:', 'TECLADO, CABEÇA IMPRESSÃO, CABO FORÇA',
                        '', 'Solução:', 'TROCA DOS MESMOS.')
                ];
                $instrumento = [
                    'line' => 16,
                    'info' => ['Instrumento'],
                    'dados_instrumentos' => $dados_instrumento,
                    'selo_lacre' => $selo_lacre,
                    'defeito_solucao' => $defeitos_solucao,
                ];
                $sheet = self::setInstrumento($sheet, $data, $instrumento);
                ///INSTRUMENTOS ------------------------------------------

                //********************************************************************************************//
                $data['pecas'] = [
                    array('1', 'TECLADO PRIX 5 PLUS PT', '1', '-', '-', 'SIM', '-'),
                    array('33', 'CABEÇA TERMICA TOLEDO', '1', 'R$ 390,00', 'R$ 390,00', 'NÃO', '-'),
                    array('50', 'CABO FORÇA TOLEDO', '1', 'R$ 45,00', 'R$ 45,00', 'SIM', 'CABO DE FORÇA FOI CORTADO, MAU USO.')
                ];
                $kits = [
                    'line' => 21,
                    'info' => ['Peças'],
                    'cabecalho' => ['Codigo', 'Peça', 'Qtde', 'V. un', 'V. Total', 'Garantia', 'Garantia Negada'],
                    'values' => $data['pecas'],
                ];
                $sheet = self::setData($sheet, $data, $kits);

                //********************************************************************************************//

                $data['kits'] = [
                    array('33', 'KIT X', '1', 'R$ 300,00', 'R$ 200,00', 'NÃO', '-')
                ];
                $kits = [
                    'line' => 26,
                    'info' => ['Kits'],
                    'cabecalho' => ['Codigo', 'Peça', 'Qtde', 'V. un', 'V. Total', 'Garantia', 'Garantia Negada'],
                    'values' => $data['kits'],
                ];
                $sheet = self::setData($sheet, $data, $kits);

                //********************************************************************************************//
                $data['servicos'] = [
                    array('1', 'SERVIÇO 1º BALANÇA SAVEGNAGO ATÉ 30 KG', 'R$ 120,00')
                ];
                $servicos = [
                    'line' => 29,
                    'info' => ['Serviços'],
                    'cabecalho' => ['Codigo', 'Kit', 'V. Total'],
                    'values' => $data['servicos'],
                ];
                $sheet = self::setData($sheet, $data, $servicos);

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

    static public function setInstrumento($sheet, $data, $dados)
    {

        $linha = $dados['line'];
        $sheet->mergeCells('A' . $linha . ':G' . $linha);
        $sheet->row($linha, function ($row) use ($data) {
            // call cell manipulation methods
            $row->setBackground('#d9d9d9');
            $row->setAlignment('center');
            $row->setFont($data['fonts']['quebra']);
        });
        $sheet->row($linha, $dados['info']);

        $sheet->rows($dados['dados_instrumentos']);
        $sheet->rows($dados['selo_lacre']);
        $sheet->rows($dados['defeito_solucao']);
        $sheet->mergeCells('B20:C20');
        $sheet->mergeCells('E20:G20');
        return $sheet;
    }

    static public function setData($sheet, $data, $dados)
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

        //CABEÇALHO ------------------------------------------
        $linha++;
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

    public function add_insumos(Request $request, $idordem_servico)
    {
        $idaparelho_manutencao = $request->get('idaparelho_manutencao');
        $total = 0;
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
                $total += DataHelper::getReal2Float($valor[$i]);
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
                $total += DataHelper::getReal2Float($valor[$i]);
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
                $total += DataHelper::getReal2Float($valor[$i]);
            }
        }

        //atualizando o valor total da OS
        $OrdemServico = OrdemServico::find($idordem_servico);
        $OrdemServico->update_valores($total);
        return redirect()->route('ordem_servicos.show', $idordem_servico);
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

    public function destroy($id)
    {
        $data = OrdemServico::find($id);
        $data->delete();
        return response()->json(['status' => '1',
            'response' => $this->Page->msg_rem]);
    }

}

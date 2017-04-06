<?php

namespace App\Models;

use Carbon\Carbon;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Yaml\Yaml;

/**
 * LaravelFocusnfe
 *
 * @author Flávio H. Ferreira <flaviometalvale@gmail.com>
 * @author Jansen Felipe <jansen.felipe@gmail.com>
 */
class NFSe extends NF
{
    private $_EMPRESA_;
    private $_FECHAMENTO_;

    private $now;
    private $nfe_cabecalho;
    private $prestador;
    private $tomador;
    private $nfe_itens;

    function __construct($debug, Fechamento $fechamento)
    {
        $this->debug = $debug;
        if ($this->debug) {
            $this->_SERVER_ = parent::_URL_HOMOLOGACAO_;
            $this->_TOKEN_ = parent::_TOKEN_HOMOLOGACAO_;
            $this->_REF_ = $fechamento->idnfe_homologacao;
        } else {
            $this->_SERVER_ = parent::_URL_PRODUCAO_;
            $this->_TOKEN_ = parent::_TOKEN_PRODUCAO_;
            $this->_REF_ = $fechamento->idnfe_producao;
        }
        $this->_NF_TYPE_ = parent::_URL_NFSe_;
        $this->now = Carbon::now();
        $this->_FECHAMENTO_ = $fechamento;
        $this->_EMPRESA_ = new Empresa();
        $this->setParams();
    }

    public function setParams()
    {
        //Configurando o cabeçalho - OK
        $this->setCabecalho();

        //Configurando o prestador - OK
        $this->setPrestador();

        //Configurando o tomador - OK
        $this->setTomador();
        if ($this->debug) {
            $this->tomador["razao_social"] = 'NF-E EMITIDA EM AMBIENTE DE HOMOLOGACAO - SEM VALOR FISCAL';
        }

        //Configurando itens - OK
        $this->setItens();

        $this->NFe_params = array_merge(
            $this->nfe_cabecalho,
            $this->prestador,
            $this->tomador,
            $this->nfe_tributacao,
            $this->nfe_transportadora,
            ["items" => $this->nfe_itens]
        );
        $fp = fopen('results.json', 'w');
        fwrite($fp, json_encode($this->NFe_params));
        fclose($fp);
    }

    public function setCabecalho()
    {
        $this->nfe_cabecalho = [
            "data_emissao" => $this->now->toW3cString(),//Data e hora de emissão. (obrigatório) Tag XML dhEmi
            "status" => 1,//: Status da NFS-e, informar 1 – Normal ou 2 – Cancelado. (Valor padrão: 1).
            "natureza_operacao" => 1, //(*): Natureza da operação. Informar um dos códigos abaixo.
            // Campo ignorado para o município de São Paulo.
            // 1 – Tributação no município
            // 2 – Tributação fora do município
            // 3 – Isenção
            // 4 – Imune
            // 5 – Exigibilidade suspensa por decisão judicial
            // 6 – Exigibilidade suspensa por procedimento administrativo (Valor padrão: 1)
            "regime_especial_tributacao" => 6, // Informar o código de identificação do regime especial de tributação conforme abaixo.
            // Campo ignorado para o município de São Paulo.
            // 1 – Microempresa municipal
            // 2 – Estimativa
            // 3 – Sociedade de profissionais
            // 4 – Cooperativa
            // 5 – MEI – Simples Nacional
            // 6 – ME EPP – Simples Nacional
            "optante_simples_nacional" => 1, //(*): Informar verdadeiro ou falso se a empresa for optante pelo Simples Nacional. Campo ignorado pelo município de São Paulo.
            "incentivador_cultural" => false,//: Informe verdadeiro ou falso. Valor padrão: falso. Campo ignorado para o município de São Paulo.
            "tipo_documento" => "T", //tributacao_rps: Usado apenas pelo município de São Paulo.
            // Informe o tipo de tributação:
            // T – Operação normal (tributação conforme documento emitido);
            // I – Operação isenta ou não tributável, executadas no Município de São Paulo;
            // F – Operação isenta ou não tributável pelo Município de São Paulo, executada em outro Município;
            // J – ISS Suspenso por Decisão Judicial (neste caso, informar no campo Discriminação dos Serviços, o número do processo judicial na 1a. instância). (Valor padrão “T”)
        ];
    }

    public function setPrestador() //PRESTADOR
    {
        $this->prestador = [
            "cnpj" => $this->_EMPRESA_->cnpj, //(*): CNPJ do prestador de serviços. Caracteres não numéricos são ignorados.
            "codigo_municipio" => $this->_EMPRESA_->ie, //(*): Código IBGE do município do prestador (consulte lista aqui)
            "inscricao_municipal" => $this->_EMPRESA_->im, //: Inscrição municipal do prestador de serviços. Caracteres não numéricos são ignorados.
        ];
    }

    public function setTomador() //TOMADOR
    {

        $Cliente = $this->_FECHAMENTO_->cliente;
        if ($Cliente->idpjuridica != NULL) { //1:PJ, 0: PF
            $PessoaJuridica = $Cliente->pessoa_juridica;
            $this->tomador["cnpj"] = $PessoaJuridica->getCnpj(); //(*): CNPJ do tomador, se aplicável. Caracteres não numéricos são ignorados.
            $this->tomador["razao_social"] = $PessoaJuridica->razao_social; //: Razão social ou nome do tomador. Tamanho: 115 caracteres.
            if (!$PessoaJuridica->isencao_ie) {
                $this->tomador["inscricao_municipal"] = $PessoaJuridica->getIe(); //inscricao_municipal: Inscrição municipal do tomador. Caracteres não numéricos são ignorados.
            }
        } else {
            $PessoaFisica = $Cliente->pessoa_fisica;
            $this->tomador["cpf"] = $PessoaFisica->getCpf();//(*): CPF do tomador, se aplicável. Caracteres não numéricos são ignorados.
            $this->tomador["razao_social"] = $Cliente->nome_responsavel; //: Razão social ou nome do tomador. Tamanho: 115 caracteres.
        }

        $Contato = $Cliente->contato;

        $endereco["logradouro"] = $Contato->logradouro; // Nome do logradouro. Tamanho: 125 caracteres.
        $endereco["tipo_logradouro"] = ""; //Tipo do logradouro. Usado apenas para o município de São Paulo. Valor padrão: os 3 primeiros caracteres do logradouro. Tamanho: 3 caracteres.
        $endereco["numero"] = $Contato->numero; //: Número do endereço. Tamanho: 10 caracteres.
        $endereco["complemento"] = ""; //: Complemento do endereço. Tamanho: 60 caracteres.
        $endereco["bairro"] = $Contato->bairro; //: Bairro. Tamanho: 60 caracteres.
        $endereco["codigo_municipio"] = $Contato->getCodCidade(); //: código IBGE do município.
        $endereco["uf"] = $Contato->estado; //: UF do endereço. Tamanho: 2 caracteres.
        $endereco["cep"] = $Contato->getCep(); //: CEP do endereço. Caracteres não numéricos são ignorados.

        $this->tomador["endereco"] = $endereco;
        $this->tomador["telefone"] = $Contato->getTelefone();
        if (($Cliente->email_nota != NULL) && ($Cliente->email_nota != "")) {
            $this->tomador["email"] = $Cliente->email_nota;
        }
    }

    public function setItens()
    {
        $item_n = 1;
        foreach ($this->_FECHAMENTO_->getAparelhoManutencaos() as $aparelho_manutencao) {
            foreach ($aparelho_manutencao->pecas_utilizadas as $pecas_utilizada) {
                $NfeItens[] = [
                    "numero_item" => $item_n, //Número (índice) do item na nota fiscal, começando por 1. (obrigatório) Integer[1-3] Tag XML nItem
                    "codigo_produto" => $pecas_utilizada->peca->idpeca, //Código interno do produto. Se não existir deve ser usado o CFOP no formato CFOP9999. (obrigatório) String[1-60] Tag XML cProd
//                    "codigo_barras_comercial" => $pecas_utilizada->peca->codigo_barras, //Código GTIN/EAN do produto. Integer[0,8,12,13,14] Tag XML cEAN
                    "descricao" => $pecas_utilizada->peca->descricao, //Descrição do produto. (obrigatório) String[1-120] Tag XML xProd
                    "codigo_ncm" => $pecas_utilizada->peca->peca_tributacao->ncm->codigo, //Código NCM do produto. Integer[2,8] Tag XML NCM
                    "codigo_cest" => $pecas_utilizada->peca->peca_tributacao->cest, //Código Especificador da Substituição Tributária. Integer[7] Tag XML CEST
//                    "codigo_ex_tipi " => **, //Código EX TIPI do produto. Integer[2-3] Tag XML EXTIPI
                    "cfop" => $pecas_utilizada->peca->peca_tributacao->cfop->numeracao, //CFOP do produto. (obrigatório) Integer[4] Tag XML CFOP
                    "unidade_comercial" => $pecas_utilizada->peca->unidade->codigo, //Unidade comercial. (obrigatório) String[1-6] Tag XML uCom

                    //MESMA COISA DO CAMPO quantidade_comercial E unidade_tributave

                    "quantidade_comercial" => $pecas_utilizada->quantidade, //Quantidade comercial. (obrigatório) Decimal[11.0-4] Tag XML qCom
                    "valor_unitario_comercial" => $pecas_utilizada->valor_float(), //Valor unitário comercial. (obrigatório) Decimal[11.0-10] Tag XML vUnCom
                    "valor_bruto" => $pecas_utilizada->valor_bruto(), //Valor bruto. Deve ser igual ao produto de Valor unitário comercial com quantidade comercial. Decimal[13.2] Tag XML vProd
//                    "codigo_barras_tributavel" => "**", //Código GTIN/EAN tributável. Integer[0,8,12,13,14] Tag XML cEANTrib
                    "unidade_tributavel" => $pecas_utilizada->peca->unidade->codigo, //Unidade tributável. (obrigatório) String[1-6] Tag XML uTrib
                    "quantidade_tributavel" => $pecas_utilizada->quantidade, //Quantidade tributável. (obrigatório) Decimal[11.0-4] Tag XML qTrib
                    "valor_unitario_tributavel" => $pecas_utilizada->valor_float(), //Valor unitário tributável. (obrigatório) Decimal[11.0-10] Tag XML vUnTrib

                    //O valor do frete vai ser incluído dentro do produto mesmo (compo é hoje) ou vai depender da O.S?
                    "valor_frete" => $pecas_utilizada->peca->peca_tributacao->valor_frete_float(), //Valor do frete. Decimal[13.2] Tag XML vFrete
                    "valor_seguro" => $pecas_utilizada->peca->peca_tributacao->valor_seguro_float(), //Valor do seguro. Decimal[13.2] Tag XML vSeg
//                    "valor_desconto" =>  ***, //Valor do desconto. Decimal[13.2] Tag XML vSeg
//                    "valor_outras_despesas" =>  ***, //Valor de outras despesas acessórias. Decimal[13.2] Tag XML vOutro


                    "inclui_no_total" => "1", //Valor do item (valor_bruto) compõe valor total da NFe (valor_produtos)? (obrigatório) Tag XML indTot
                    //Valores permitidos:
                    // 0: não
                    // 1: sim
                    "icms_origem" => $pecas_utilizada->peca->peca_tributacao->icms_origem, //Origem da mercadoria. (obrigatório)
                    //Valores permitidos:
                    //0: nacional
                    //1: estrangeira (importação direta)
                    //2: estrangeira (adquirida no mercado interno)
                    //3: nacional com mais de 40% de conteúdo estrangeiro
                    //4: nacional produzida através de processos produtivos básicos
                    //5: nacional com menos de 40% de conteúdo estrangeiro
                    //6: estrangeira (importação direta) sem produto nacional similar
                    //7: estrangeira (adquirida no mercado interno) sem produto nacional similar
                    "icms_situacao_tributaria" => $pecas_utilizada->peca->peca_tributacao->icms_situacao_tributaria,
//                    "icms_situacao_tributaria" => $pecas_utilizada->peca->peca_tributacao->icms_situacao_tributaria, //Situação tributária do ICMS. (obrigatório)
                    //Valores permitidos
                    //00: tributada integralmente
                    //10: tributada e com cobrança do ICMS por substituição tributária
                    //20: tributada com redução de base de cálculo
                    //30: isenta ou não tributada e com cobrança do ICMS por substituição tributária
                    //40: isenta
                    //41: não tributada
                    //50: suspensão
                    //51: diferimento (a exigência do preenchimento das informações do ICMS diferido fica a critério de cada UF)
                    //60: cobrado anteriormente por substituição tributária
                    //70: tributada com redução de base de cálculo e com cobrança do ICMS por substituição tributária
                    //90: outras (regime Normal)
                    //101: tributada pelo Simples Nacional com permissão de crédito
                    //102: tributada pelo Simples Nacional sem permissão de crédito
                    //103: isenção do ICMS no Simples Nacional para faixa de receita bruta
                    //201: tributada pelo Simples Nacional com permissão de crédito e com cobrança do ICMS por substituição tributária
                    //202: tributada pelo Simples Nacional sem permissão de crédito e com cobrança do ICMS por substituição tributária
                    //203: isenção do ICMS nos Simples Nacional para faixa de receita bruta e com cobrança do ICMS por substituição tributária
                    //300: imune
                    //400: não tributada pelo Simples Nacional
                    //500: ICMS cobrado anteriormente por substituição tributária (substituído) ou por antecipação
                    //900: outras (regime Simples Nacional)
                    "pis_situacao_tributaria" => $pecas_utilizada->peca->peca_tributacao->pis_situacao_tributaria,
//                    "pis_situacao_tributaria" => $pecas_utilizada->peca->peca_tributacao->pis_situacao_tributaria, //Situação tributária do PIS.(obrigatório)
                    //Valores permitidos
                    //01: operação tributável: base de cálculo = valor da operação (alíquota normal - cumulativo/não cumulativo)
                    //02: operação tributável: base de cálculo = valor da operação (alíquota diferenciada)
                    //03: operação tributável: base de cálculo = quantidade vendida × alíquota por unidade de produto
                    //04: operação tributável: tributação monofásica (alíquota zero)
                    //05: operação tributável: substituição tributária
                    //06: operação tributável: alíquota zero
                    //07: operação isenta da contribuição
                    //08: operação sem incidência da contribuição
                    //09: operação com suspensão da contribuição
                    //49: outras operações de saída
                    //50: operação com direito a crédito: vinculada exclusivamente a receita tributada no mercado interno
                    //51: operação com direito a crédito: vinculada exclusivamente a receita não tributada no mercado interno
                    //52: operação com direito a crédito: vinculada exclusivamente a receita de exportação
                    //53: operação com direito a crédito: vinculada a receitas tributadas e não-tributadas no mercado interno
                    //54: operação com direito a crédito: vinculada a receitas tributadas no mercado interno e de exportação
                    //55: operação com direito a crédito: vinculada a receitas não-tributadas no mercado interno e de exportação
                    //56: operação com direito a crédito: vinculada a receitas tributadas e não-tributadas no mercado interno e de exportação
                    //60: crédito presumido: operação de aquisição vinculada exclusivamente a receita tributada no mercado interno
                    //61: crédito presumido: operação de aquisição vinculada exclusivamente a receita não-tributada no mercado interno
                    //62: crédito presumido: operação de aquisição vinculada exclusivamente a receita de exportação
                    //63: crédito presumido: operação de aquisição vinculada a receitas tributadas e não-tributadas no mercado interno
                    //64: crédito presumido: operação de aquisição vinculada a receitas tributadas no mercado interno e de exportação
                    //65: crédito presumido: operação de aquisição vinculada a receitas não-tributadas no mercado interno e de exportação
                    //66: crédito presumido: operação de aquisição vinculada a receitas tributadas e não-tributadas no mercado interno e de exportação
                    //67: crédito presumido: outras operações
                    //70: operação de aquisição sem direito a crédito
                    //71: operação de aquisição com isenção
                    //72: operação de aquisição com suspensão
                    //73: operação de aquisição a alíquota zero
                    //74: operação de aquisição sem incidência da contribuição
                    //75: operação de aquisição por substituição tributária
                    //98: outras operações de entrada
                    //99: outras operações
                    "cofins_situacao_tributaria" => $pecas_utilizada->peca->peca_tributacao->cofins_situacao_tributaria
//                    "cofins_situacao_tributaria" => $pecas_utilizada->peca->peca_tributacao->cofins_situacao_tributaria //(obrigatório)
                    //Valores permitidos
                    //01: operação tributável: base de cálculo = valor da operação (alíquota normal - cumulativo/não cumulativo)
                    //02: operação tributável: base de cálculo = valor da operação (alíquota diferenciada)
                    //03: operação tributável: base de cálculo = quantidade vendida × alíquota por unidade de produto
                    //04: operação tributável: tributação monofásica (alíquota zero)
                    //05: operação tributável: substituição tributária
                    //06: operação tributável: alíquota zero
                    //07: operação isenta da contribuição
                    //08: operação sem incidência da contribuição
                    //09: operação com suspensão da contribuição
                    //49: outras operações de saída
                    //50: operação com direito a crédito: vinculada exclusivamente a receita tributada no mercado interno
                    //51: operação com direito a crédito: vinculada exclusivamente a receita não tributada no mercado interno
                    //52: operação com direito a crédito: vinculada exclusivamente a receita de exportação
                    //53: operação com direito a crédito: vinculada a receitas tributadas e não-tributadas no mercado interno
                    //54: operação com direito a crédito: vinculada a receitas tributadas no mercado interno e de exportação
                    //55: operação com direito a crédito: vinculada a receitas não-tributadas no mercado interno e de exportação
                    //56: operação com direito a crédito: vinculada a receitas tributadas e não-tributadas no mercado interno e de exportação
                    //60: crédito presumido: operação de aquisição vinculada exclusivamente a receita tributada no mercado interno
                    //61: crédito presumido: operação de aquisição vinculada exclusivamente a receita não-tributada no mercado interno
                    //62: crédito presumido: operação de aquisição vinculada exclusivamente a receita de exportação
                    //63: crédito presumido: operação de aquisição vinculada a receitas tributadas e não-tributadas no mercado interno
                    //64: crédito presumido: operação de aquisição vinculada a receitas tributadas no mercado interno e de exportação
                    //65: crédito presumido: operação de aquisição vinculada a receitas não-tributadas no mercado interno e de exportação
                    //66: crédito presumido: operação de aquisição vinculada a receitas tributadas e não-tributadas no mercado interno e de exportação
                    //67: crédito presumido: outras operações
                    //70: operação de aquisição sem direito a crédito
                    //71: operação de aquisição com isenção
                    //72: operação de aquisição com suspensão
                    //73: operação de aquisição a alíquota zero
                    //74: operação de aquisição sem incidência da contribuição
                    //75: operação de aquisição por substituição tributária
                    //98: outras operações de entrada
                    //99: outras operações
                ];
                $item_n++;
            }
        }

        $this->nfe_itens = $NfeItens;
        return true;
//        echo json_encode($NfeItens);


        $this->nfe_itens = array(
            [
                "numero_item" => "1",
                "codigo_produto" => "15",
                "descricao" => "Bateria 6.0V, 4.5Ah IND- 9098C -CT",
                "codigo_ncm" => "85078000",
                "codigo_cest" => "0106400",
                "cfop" => "5405",
                "unidade_comercial" => "und",
                "quantidade_comercial" => "2.0000",
                "valor_unitario_comercial" => "92.0000000000",
                "valor_bruto" => "184.00",
                "unidade_tributavel" => "92.00",
                "quantidade_tributavel" => "2.0000",
                "valor_unitario_tributavel" => "92.0000000000",
                "inclui_no_total" => "1",
                "icms_origem" => "0",
                "icms_situacao_tributaria" => "500",
                "pis_situacao_tributaria" => "07",
                "cofins_situacao_tributaria" => "07"
            ], [
                "numero_item" => "2",
                "codigo_produto" => "05",
                "descricao" => "Cabe\u00e7ote Termico Prix 5 - 4 - due",
                "codigo_ncm" => "84439942",
                "codigo_cest" => "0106400",
                "cfop" => "5405",
                "unidade_comercial" => "und",
                "quantidade_comercial" => "8.0000",
                "valor_unitario_comercial" => "390.1000000000",
                "valor_bruto" => "3120.80",
                "unidade_tributavel" => "390.10",
                "quantidade_tributavel" => "8.0000",
                "valor_unitario_tributavel" => "390.1000000000",
                "inclui_no_total" => "1",
                "icms_origem" => "0",
                "icms_situacao_tributaria" => "500",
                "pis_situacao_tributaria" => "07",
                "cofins_situacao_tributaria" => "07"
            ], [
                "numero_item" => "3",
                "codigo_produto" => "01",
                "descricao" => "Teclado Elgim",
                "codigo_ncm" => "84239029",
                "codigo_cest" => "0106400",
                "cfop" => "5405",
                "unidade_comercial" => "und",
                "quantidade_comercial" => "2.0000",
                "valor_unitario_comercial" => "127.4000000000",
                "valor_bruto" => "254.80",
                "unidade_tributavel" => "127.40",
                "quantidade_tributavel" => "2.0000",
                "valor_unitario_tributavel" => "127.4000000000",
                "inclui_no_total" => "1",
                "icms_origem" => "0",
                "icms_situacao_tributaria" => "500",
                "pis_situacao_tributaria" => "07",
                "cofins_situacao_tributaria" => "07"
            ], [
                "numero_item" => "4",
                "codigo_produto" => "02",
                "descricao" => "Placa Fonte Elgim",
                "codigo_ncm" => "85340019",
                "codigo_cest" => "0106400",
                "cfop" => "5405",
                "unidade_comercial" => "und",
                "quantidade_comercial" => "1.0000",
                "valor_unitario_comercial" => "339.2900000000",
                "valor_bruto" => "339.29",
                "unidade_tributavel" => "339.29",
                "quantidade_tributavel" => "1.0000",
                "valor_unitario_tributavel" => "339.2900000000",
                "inclui_no_total" => "1",
                "icms_origem" => "0",
                "icms_situacao_tributaria" => "500",
                "pis_situacao_tributaria" => "07",
                "cofins_situacao_tributaria" => "07"
            ], [
                "numero_item" => "5",
                "codigo_produto" => "03",
                "descricao" => "Teclado Prix 5 Toledo",
                "codigo_ncm" => "84239029",
                "codigo_cest" => "0106400",
                "cfop" => "5405",
                "unidade_comercial" => "und",
                "quantidade_comercial" => "12.0000",
                "valor_unitario_comercial" => "182.2400000000",
                "valor_bruto" => "2186.88",
                "unidade_tributavel" => "182.24",
                "quantidade_tributavel" => "12.0000",
                "valor_unitario_tributavel" => "182.2400000000",
                "inclui_no_total" => "1",
                "icms_origem" => "0",
                "icms_situacao_tributaria" => "500",
                "pis_situacao_tributaria" => "07",
                "cofins_situacao_tributaria" => "07"
            ], [
                "numero_item" => "6",
                "codigo_produto" => "04",
                "descricao" => "CABO FOR\u00c7A PRIX 5",
                "codigo_ncm" => "85444200",
                "codigo_cest" => "0106400",
                "cfop" => "5405",
                "unidade_comercial" => "und",
                "quantidade_comercial" => "4.0000",
                "valor_unitario_comercial" => "33.5000000000",
                "valor_bruto" => "134.00",
                "unidade_tributavel" => "35.50",
                "quantidade_tributavel" => "4.0000",
                "valor_unitario_tributavel" => "33.5000000000",
                "inclui_no_total" => "1",
                "icms_origem" => "0",
                "icms_situacao_tributaria" => "500",
                "pis_situacao_tributaria" => "07",
                "cofins_situacao_tributaria" => "07"
            ], [
                "numero_item" => "7",
                "codigo_produto" => "24",
                "descricao" => "SENSOR PRIX 5 - 6 - DUE, UNO",
                "codigo_ncm" => "84239029",
                "codigo_cest" => "0106400",
                "cfop" => "5405",
                "unidade_comercial" => "und",
                "quantidade_comercial" => "5.0000",
                "valor_unitario_comercial" => "182.5000000000",
                "valor_bruto" => "912.50",
                "unidade_tributavel" => "182.50",
                "quantidade_tributavel" => "5.0000",
                "valor_unitario_tributavel" => "182.5000000000",
                "inclui_no_total" => "1",
                "icms_origem" => "0",
                "icms_situacao_tributaria" => "500",
                "pis_situacao_tributaria" => "07",
                "cofins_situacao_tributaria" => "07"
            ], [
                "numero_item" => "8",
                "codigo_produto" => "08",
                "descricao" => "PCI PRINCIPAL PRIX 5 PLUS - 5 - ETHERNET REV",
                "codigo_ncm" => "84239029",
                "codigo_cest" => "0106400",
                "cfop" => "5405",
                "unidade_comercial" => "und",
                "quantidade_comercial" => "1.0000",
                "valor_unitario_comercial" => "625.4100000000",
                "valor_bruto" => "625.41",
                "unidade_tributavel" => "625.41",
                "quantidade_tributavel" => "1.0000",
                "valor_unitario_tributavel" => "625.4100000000",
                "inclui_no_total" => "1",
                "icms_origem" => "0",
                "icms_situacao_tributaria" => "500",
                "pis_situacao_tributaria" => "07",
                "cofins_situacao_tributaria" => "07"
            ], [
                "numero_item" => "9",
                "codigo_produto" => "09",
                "descricao" => "Teclado IDM",
                "codigo_ncm" => "84239029",
                "codigo_cest" => "0106400",
                "cfop" => "5405",
                "unidade_comercial" => "und",
                "quantidade_comercial" => "1.0000",
                "valor_unitario_comercial" => "65.0000000000",
                "valor_bruto" => "65.00",
                "unidade_tributavel" => "65.00",
                "quantidade_tributavel" => "1.0000",
                "valor_unitario_tributavel" => "65.0000000000",
                "inclui_no_total" => "1",
                "icms_origem" => "0",
                "icms_situacao_tributaria" => "500",
                "pis_situacao_tributaria" => "07",
                "cofins_situacao_tributaria" => "07"
            ], [
                "numero_item" => "10",
                "codigo_produto" => "10",
                "descricao" => "CELULA S CURIO 500KG",
                "codigo_ncm" => "90318060",
                "codigo_cest" => "0106400",
                "cfop" => "5405",
                "unidade_comercial" => "und",
                "quantidade_comercial" => "1.0000",
                "valor_unitario_comercial" => "801.0000000000",
                "valor_bruto" => "801.00",
                "unidade_tributavel" => "801.00",
                "quantidade_tributavel" => "1.0000",
                "valor_unitario_tributavel" => "801.0000000000",
                "inclui_no_total" => "1",
                "icms_origem" => "0",
                "icms_situacao_tributaria" => "500",
                "pis_situacao_tributaria" => "07",
                "cofins_situacao_tributaria" => "07"
            ], [
                "numero_item" => "11",
                "codigo_produto" => "11",
                "descricao" => "PCI PRINCIPAL PLATINA UNIFIC",
                "codigo_ncm" => "84239029",
                "codigo_cest" => "0106400",
                "cfop" => "5405",
                "unidade_comercial" => "und",
                "quantidade_comercial" => "3.0000",
                "valor_unitario_comercial" => "522.5900000000",
                "valor_bruto" => "1567.77",
                "unidade_tributavel" => "522.59",
                "quantidade_tributavel" => "3.0000",
                "valor_unitario_tributavel" => "522.5900000000",
                "inclui_no_total" => "1",
                "icms_origem" => "0",
                "icms_situacao_tributaria" => "500",
                "pis_situacao_tributaria" => "07",
                "cofins_situacao_tributaria" => "07"
            ], [
                "numero_item" => "12",
                "codigo_produto" => "12",
                "descricao" => "TECLADO PLATINA CUSTOM",
                "codigo_ncm" => "84239029",
                "codigo_cest" => "0106400",
                "cfop" => "5405",
                "unidade_comercial" => "und",
                "quantidade_comercial" => "1.0000",
                "valor_unitario_comercial" => "127.4000000000",
                "valor_bruto" => "127.40",
                "unidade_tributavel" => "127.40",
                "quantidade_tributavel" => "1.0000",
                "valor_unitario_tributavel" => "127.4000000000",
                "inclui_no_total" => "1",
                "icms_origem" => "0",
                "icms_situacao_tributaria" => "500",
                "pis_situacao_tributaria" => "07",
                "cofins_situacao_tributaria" => "07"
            ], [
                "numero_item" => "13",
                "codigo_produto" => "13",
                "descricao" => "Cabo Interl Filtro Linha/PCI Fonte",
                "codigo_ncm" => "85363000",
                "codigo_cest" => "0106400",
                "cfop" => "5405",
                "unidade_comercial" => "und",
                "quantidade_comercial" => "1.0000",
                "valor_unitario_comercial" => "55.0000000000",
                "valor_bruto" => "55.00",
                "unidade_tributavel" => "55.00",
                "quantidade_tributavel" => "1.0000",
                "valor_unitario_tributavel" => "55.0000000000",
                "inclui_no_total" => "1",
                "icms_origem" => "0",
                "icms_situacao_tributaria" => "500",
                "pis_situacao_tributaria" => "07",
                "cofins_situacao_tributaria" => "07"
            ], [
                "numero_item" => "14",
                "codigo_produto" => "14",
                "descricao" => "FONTE FULL RANGE BALN\u00c7AS MF-8217-IDM-9098-BP",
                "codigo_ncm" => "85299040",
                "codigo_cest" => "0106400",
                "cfop" => "5405",
                "unidade_comercial" => "und",
                "quantidade_comercial" => "8.0000",
                "valor_unitario_comercial" => "75.0000000000",
                "valor_bruto" => "600.00",
                "unidade_tributavel" => "75.00",
                "quantidade_tributavel" => "8.0000",
                "valor_unitario_tributavel" => "75.0000000000",
                "inclui_no_total" => "1",
                "icms_origem" => "0",
                "icms_situacao_tributaria" => "500",
                "pis_situacao_tributaria" => "07",
                "cofins_situacao_tributaria" => "07"
            ], [
                "numero_item" => "15",
                "codigo_produto" => "16",
                "descricao" => "PCI de Juncao Montada",
                "codigo_ncm" => "85340019",
                "codigo_cest" => "0106400",
                "cfop" => "5405",
                "unidade_comercial" => "und",
                "quantidade_comercial" => "1.0000",
                "valor_unitario_comercial" => "582.8500000000",
                "valor_bruto" => "582.85",
                "unidade_tributavel" => "582.85",
                "quantidade_tributavel" => "1.0000",
                "valor_unitario_tributavel" => "582.8500000000",
                "valor_desconto" => "77.76",
                "inclui_no_total" => "1",
                "icms_origem" => "0",
                "icms_situacao_tributaria" => "500",
                "pis_situacao_tributaria" => "07",
                "cofins_situacao_tributaria" => "07"
            ], [
                "numero_item" => "16",
                "codigo_produto" => "20",
                "descricao" => "Cel Carga 1100kg \"Carcara\"",
                "codigo_ncm" => "90318060",
                "codigo_cest" => "0106400",
                "cfop" => "5405",
                "unidade_comercial" => "und",
                "quantidade_comercial" => "2.0000",
                "valor_unitario_comercial" => "1886.1800000000",
                "valor_bruto" => "3772.36",
                "unidade_tributavel" => "188618",
                "quantidade_tributavel" => "2.0000",
                "valor_unitario_tributavel" => "1886.1800000000",
                "inclui_no_total" => "1",
                "icms_origem" => "0",
                "icms_situacao_tributaria" => "500",
                "pis_situacao_tributaria" => "07",
                "cofins_situacao_tributaria" => "07"
            ], [
                "numero_item" => "17",
                "codigo_produto" => "18",
                "descricao" => "TECLADO 9091 INOX AZUL",
                "codigo_ncm" => "84733099",
                "codigo_cest" => "0106400",
                "cfop" => "5405",
                "unidade_comercial" => "und",
                "quantidade_comercial" => "1.0000",
                "valor_unitario_comercial" => "159.0000000000",
                "valor_bruto" => "159.00",
                "unidade_tributavel" => "159.00",
                "quantidade_tributavel" => "1.0000",
                "valor_unitario_tributavel" => "159.0000000000",
                "inclui_no_total" => "1",
                "icms_origem" => "0",
                "icms_situacao_tributaria" => "500",
                "pis_situacao_tributaria" => "07",
                "cofins_situacao_tributaria" => "07"
            ], [
                "numero_item" => "18",
                "codigo_produto" => "06",
                "descricao" => "CABO INTERL. PCI FONTE FILTRO PRIX 5 - 4 - DUE - UNO",
                "codigo_ncm" => "85363000",
                "codigo_cest" => "0106400",
                "cfop" => "5405",
                "unidade_comercial" => "und",
                "quantidade_comercial" => "1.0000",
                "valor_unitario_comercial" => "502.5400000000",
                "valor_bruto" => "502.54",
                "unidade_tributavel" => "502.54",
                "quantidade_tributavel" => "1.0000",
                "valor_unitario_tributavel" => "502.5400000000",
                "inclui_no_total" => "1",
                "icms_origem" => "0",
                "icms_situacao_tributaria" => "500",
                "pis_situacao_tributaria" => "07",
                "cofins_situacao_tributaria" => "07"
            ], [
                "numero_item" => "19",
                "codigo_produto" => "21",
                "descricao" => "Placa principal com entrada bateria - 9098C REV",
                "codigo_ncm" => "85340019",
                "codigo_cest" => "0106400",
                "cfop" => "5405",
                "unidade_comercial" => "und",
                "quantidade_comercial" => "1.0000",
                "valor_unitario_comercial" => "920.0000000000",
                "valor_bruto" => "920.00",
                "unidade_tributavel" => "920.00",
                "quantidade_tributavel" => "1.0000",
                "valor_unitario_tributavel" => "920.0000000000",
                "inclui_no_total" => "1",
                "icms_origem" => "0",
                "icms_situacao_tributaria" => "500",
                "pis_situacao_tributaria" => "07",
                "cofins_situacao_tributaria" => "07"
            ], [
                "numero_item" => "20",
                "codigo_produto" => "07",
                "descricao" => "TRANSFORMADOR PRIX3-BP-MF-DP",
                "codigo_ncm" => "85043199",
                "codigo_cest" => "0106400",
                "cfop" => "5405",
                "unidade_comercial" => "und",
                "quantidade_comercial" => "1.0000",
                "valor_unitario_comercial" => "127.5300000000",
                "valor_bruto" => "127.53",
                "unidade_tributavel" => "127.53",
                "quantidade_tributavel" => "1.0000",
                "valor_unitario_tributavel" => "127.5300000000",
                "inclui_no_total" => "1",
                "icms_origem" => "0",
                "icms_situacao_tributaria" => "500",
                "pis_situacao_tributaria" => "07",
                "cofins_situacao_tributaria" => "07"
            ], [
                "numero_item" => "21",
                "codigo_produto" => "23",
                "descricao" => "Cabo Int. PCI Principal com cabe\u00e7ote PRIX5 PRIX6 PRIX DUE",
                "codigo_ncm" => "85444200",
                "codigo_cest" => "0106400",
                "cfop" => "5405",
                "unidade_comercial" => "und",
                "quantidade_comercial" => "2.0000",
                "valor_unitario_comercial" => "222.3000000000",
                "valor_bruto" => "444.60",
                "unidade_tributavel" => "222.30",
                "quantidade_tributavel" => "2.0000",
                "valor_unitario_tributavel" => "222.3000000000",
                "inclui_no_total" => "1",
                "icms_origem" => "0",
                "icms_situacao_tributaria" => "500",
                "pis_situacao_tributaria" => "07",
                "cofins_situacao_tributaria" => "07"
            ]);
        return true;
    }



//    public static function consulta1()
//    {
//        //extract data from the post
//        //set POST variables
//        $url = 'https://www.codigocest.com.br/consulta-codigo-cest-pelo-ncm';
//        $fields = array(
//            'ncmsh' => '8507.80.00',
//        );
//
//        $fields_string = '';
//        //url-ify the data for the POST
//        foreach ($fields as $key => $value) {
//            $fields_string .= $key . '=' . $value . '&';
//        }
//        rtrim($fields_string, '&');
//
//        //open connection
//        $ch = curl_init();
//
//        //set the url, number of POST vars, POST data
//        curl_setopt($ch, CURLOPT_URL, $url);
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//        curl_setopt($ch, CURLOPT_POST, count($fields));
//        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
//
//        //execute post
//        $html = curl_exec($ch);
//        $result = curl_getinfo($ch, CURLINFO_HTTP_CODE);
//        curl_close($ch);
//        //as três linhas abaixo imprimem as informações retornadas pela API, aqui o seu sistema deverá
//        //interpretar e lidar com o retorno
//        print("STATUS: " . $result . "<br>");
//        print("BODY <br><br>");
////        print($html);
////        exit;
//
//        $crawler = new Crawler($html);
//
//        //or something like this
//        $body = $crawler->filter('body')->text();
//        dd($body);
//    }
//
//    public static function consulta2()
//    {
//
//
//        $client = new Client();//(['base_uri' => 'https://www.codigocest.com.br/', 'timeout'  => 2.0]);
////        $client->setHeader('Host', 'codigocest.com.br');
////        $client->setHeader('User-Agent', 'Mozilla/5.0 (Windows NT 6.1; rv:32.0) Gecko/20100101 Firefox/32.0');
////        $client->setHeader('Accept', 'text/html,application/xhtml+xml,application/xml;q=0.9, */* ;q=0.8');
////        $client->setHeader('Accept-Language', 'pt-BR,pt;q=0.8,en-US;q=0.5,en;q=0.3');
////        $client->setHeader('Accept-Encoding', 'gzip, deflate');
//////        $client->setHeader('Referer', 'http://www.codigocest.com.br');
////        $client->setHeader('Connection', 'keep-alive');
//        $param = array(
//            'ncmsh' => '8507.80.00',
//        );
//        try {
//            $response = $client->request('POST', 'https://www.codigocest.com.br/consulta-codigo-cest-pelo-ncm', $param);
//            dd($response);
//            echo $client->getResponse();
//            echo $client->getStatusCode(); // "200"
//            echo $client->getHeader('content-type'); // 'application/json; charset=utf8'
//            echo $client->getBody();
//            exit;
//        } catch (RequestException $e) {
//            echo "RequestException: <br><br>";
//            if ($e->hasResponse()) {
//                echo Psr7\str($e->getResponse());
//            }
//        } catch (ClientException $e) {
//            echo ">ClientException: <br><br>";
//            echo Psr7\str($e->getRequest());
//            echo Psr7\str($e->getResponse());
//        }
//        exit;
//    }
//
//    public static function getParams()
//    {
//
//        $client = new Client();
//        //https://www.cadesp.fazenda.sp.gov.br/Pages/Cadastro/Consultas/ConsultaPublica/ConsultaPublica.aspx
//        $crawler = $client->request('GET', 'http://pfeserv1.fazenda.sp.gov.br/sintegrapfe/consultaSintegraServlet');
//        $response = $client->getResponse();
//        $input = $crawler->filter('input[name="paramBot"]');
//        $paramBot = trim($input->attr('value'));
//        $headers = $response->getHeaders();
//        $cookie = $headers['Set-Cookie'][0];
//        $paramBotURL = urlencode($paramBot);
//        $ch = curl_init("http://pfeserv1.fazenda.sp.gov.br/sintegrapfe/imageGenerator?keycheck=" . $paramBotURL);
//        $options = array(
//            CURLOPT_COOKIEJAR => 'cookiejar',
//            CURLOPT_HTTPHEADER => array(
//                "Pragma: no-cache",
//                "Origin: http://pfeserv1.fazenda.sp.gov.br",
//                "Host: pfeserv1.fazenda.sp.gov.br",
//                "User-Agent: Mozilla/5.0 (Windows NT 6.1; rv:32.0) Gecko/20100101 Firefox/32.0",
//                "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8",
//                "Accept-Language: pt-BR,pt;q=0.8,en-US;q=0.5,en;q=0.3",
//                "Accept-Encoding: gzip, deflate",
//                "Referer: http://pfeserv1.fazenda.sp.gov.br/sintegrapfe/consultaSintegraServlet",
//                "Cookie: flag=1; $cookie",
//                "Connection: keep-alive"
//            ),
//            CURLOPT_RETURNTRANSFER => true,
//            CURLOPT_BINARYTRANSFER => true
//        );
////            CURLOPT_FOLLOWLOCATION => true,
//        curl_setopt_array($ch, $options);
//        $img = curl_exec($ch);
//        curl_close($ch);
//        if (@imagecreatefromstring($img) == false) {
//            throw new Exception('Não foi possível capturar o captcha');
//        }
//        return array(
//            'cookie' => $cookie,
//            'captchaBase64' => 'data:image/png;base64,' . base64_encode($img),
//            'paramBot' => $paramBot
//        );
//    }
//////
//    public static function consulta()
//    {
//
//        $client = new Client();
//        #$client->getClient()->setDefaultOption('timeout', 120);
////        $client->getClient()->setDefaultOption('config/curl/'.CURLOPT_TIMEOUT, 0);
////        $client->getClient()->setDefaultOption('config/curl/'.CURLOPT_TIMEOUT_MS, 0);
////        $client->getClient()->setDefaultOption('config/curl/'.CURLOPT_CONNECTTIMEOUT, 0);
////        $client->getClient()->setDefaultOption('config/curl/'.CURLOPT_RETURNTRANSFER, true);
////        $client->setHeader('Host', 'codigocest.com.br');
////        $client->setHeader('User-Agent', 'Mozilla/5.0 (Windows NT 6.1; rv:32.0) Gecko/20100101 Firefox/32.0');
////        $client->setHeader('Accept', 'text/html,application/xhtml+xml,application/xml;q=0.9, */* ;q=0.8');
////        $client->setHeader('Accept-Language', 'pt-BR,pt;q=0.8,en-US;q=0.5,en;q=0.3');
////        $client->setHeader('Accept-Encoding', 'gzip, deflate');
////        $client->setHeader('Referer', 'http://www.codigocest.com.br');
////        $client->setHeader('Connection', 'keep-alive');
//        $param = array(
//            'ncmsh' => '8507.80.00',
//        );
//        $crawler = $client->request('POST', 'https://www.codigocest.com.br/consulta-codigo-cest-pelo-ncm', $param);
//
//        return $crawler;
//
//
//        $imageError = 'O valor da imagem esta incorreto ou expirou. Verifique novamente a imagem e digite exatamente os 5 caracteres exibidos.';
//        $checkError = $crawler->filter('body > center')->eq(1)->count();
//        if ($checkError && $imageError == trim($crawler->filter('body > center')->eq(1)->text())) {
//            $erro_msg = $imageError;
//            return (['status' => 0, 'response' => $erro_msg]);
////            throw new Exception($imageError, 99);
//        }
//        $center_ = $crawler->filter('body > center');
//        if (count($center_) == 0) {
//            $erro_msg = 'Serviço indisponível!. Tente novamente.';
//            return (['status' => 0, 'response' => $erro_msg]);
////            throw new Exception('Serviço indisponível!. Tente novamente.', 99);
//        }
//        //self::saveFile($client);
//        $html = self::parseContent($client->getResponse()->__toString());
//        $crawler = new  \Symfony\Component\DomCrawler\Crawler($html);
//        $data = self::parseSelectors($crawler);
//        return $data;
//    }
//    public function send()
//    {
//        $client = new Client(['base_uri' => $this->SERVER]);
//        try {
//            $response = $client->request('POST', $this->SERVER . "/autorizar.json?token=" . $this->TOKEN . "&ref=" . $this->ref, ['json' => $this->$NFe_params]);
//            echo $client->getResponse();
//            echo $client->getStatusCode(); // "200"
//            echo $client->getHeader('content-type'); // 'application/json; charset=utf8'
//            echo $client->getBody();
//            exit;
//        } catch (RequestException $e) {
//            echo "RequestException: <br><br>";
//            if ($e->hasResponse()) {
//                echo Psr7\str($e->getResponse());
//            }
//        } catch (ClientException $e) {
//            echo ">ClientException: <br><br>";
//            echo Psr7\str($e->getRequest());
//            echo Psr7\str($e->getResponse());
//        }
//        exit;
//    }


}
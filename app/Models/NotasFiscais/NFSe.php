<?php

namespace App\Models\NotasFiscais;

use App\Helpers\DataHelper;
use App\Models\Empresa;
use App\Models\Faturamento;
use Carbon\Carbon;

/**
 * LaravelFocusnfe
 *
 * @author Leonardo Zanin <silva.zanin@gmail.com>
 */
class NFSe extends NF
{
    public $servico_params_fixos = [

        //3. A aliquota não deve ser enviada para optantes do simples nacional
        'aliquota' => 3.84,
        'porcentagem_tributos_float' => 11.31,
        'porcentagem_tributos_real' => '11,31%',

        'item_lista_servico' => '14.01', //'14.01/14.01.11',
        'codigo_cnae' => '3314710', //'3314-7/10',

        //4. Faltava um dígido no codigo_tributacao_municipio
        'codigo_tributario_municipio' => '14.01.11 / 00140111', //'14.01',

        //1. Alterei a configuração para remover automaticamente os acentos
        'discriminacao' => 'SERVIÇOS PRESTADOS EM BALANÇAS, MÍDIAS, COLETORES DE DADOS, CFTV, SERVIDORES, FATIADORES, REDES DE DADOS E OUTROS EQUIPAMANTOS DE AUTOMAÇÃO COMERCIAL \ INDUSTRIAL.\n\nVALOR APROXIMADO DOS TRIBUTOS',
//        'discriminacao' => 'SERVI\u00c7OS PRESTADOS EM BALAN\u00c7AS, M\u00cdDIAS, COLETORES DE DADOS, CFTV, SERVIDORES, FATIADORES, REDES DE DADOS E OUTROS EQUIPAMANTOS DE AUTOM\u00c7\u00c3O COMERCIAL \\ INDUSTRIA',
        'codigo_municipio' => '3543402', //cliente
    ];
    private $_EMPRESA_;
    private $_FATURAMENTO_;
    private $now;
    private $cabecalho;
    private $prestador;
    private $tomador;
    private $servico;

    function __construct($debug, Faturamento $faturamento)
    {
        $this->debug = $debug;
        if ($this->debug) {
            $this->_SERVER_ = parent::_URL_HOMOLOGACAO_;
            $this->_TOKEN_ = parent::_TOKEN_HOMOLOGACAO_;
            $this->_REF_ = $faturamento->idnfse_homologacao;
            $this->servico_params_fixos['aliquota'] = 2.50;
        } else {
            $this->_SERVER_ = parent::_URL_PRODUCAO_;
            $this->_TOKEN_ = parent::_TOKEN_PRODUCAO_;
            $this->_REF_ = $faturamento->idnfse_producao;
            $this->servico_params_fixos['aliquota'] = 3.84;
        }
        $this->_NF_TYPE_ = parent::_URL_NFSe_;
        $this->now = Carbon::now();
        $this->_FATURAMENTO_ = $faturamento;
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
        $this->setServico();

        $this->_PARAMS_NF_ = array_merge(
            $this->cabecalho,
            ['prestador' => $this->prestador],
            ['tomador' => $this->tomador],
            ["servico" => $this->servico]
        );

        $this->writeJson();
    }

    public function setCabecalho()
    {
        $this->cabecalho = [
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
            "regime_especial_tributacao" => $this->_EMPRESA_->regime_especial_tributacao, // Informar o código de identificação do regime especial de tributação conforme abaixo.
            // Campo ignorado para o município de São Paulo.
            // 1 – Microempresa municipal
            // 2 – Estimativa
            // 3 – Sociedade de profissionais
            // 4 – Cooperativa
            // 5 – MEI – Simples Nacional
            // 6 – ME EPP – Simples Nacional
            "optante_simples_nacional" => $this->_EMPRESA_->optante_simples_nacional, //(*): Informar verdadeiro ou falso se a empresa for optante pelo Simples Nacional. Campo ignorado pelo município de São Paulo.
            "incentivador_cultural" => $this->_EMPRESA_->incentivador_cultural,//: Informe verdadeiro ou falso. Valor padrão: falso. Campo ignorado para o município de São Paulo.
            "tributacao_rps" => $this->_EMPRESA_->tributacao_rps, //tributacao_rps: Usado apenas pelo município de São Paulo.
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
            "codigo_municipio" => $this->_EMPRESA_->icms_codigo_municipio, //(*): Código IBGE do município do prestador (consulte lista aqui)
            "inscricao_municipal" => $this->_EMPRESA_->im, //: Inscrição municipal do prestador de serviços. Caracteres não numéricos são ignorados.
        ];
    }

    public function setTomador() //TOMADOR
    {

        $Cliente = $this->_FATURAMENTO_->cliente;
        if ($Cliente->idpjuridica != NULL) { //1:PJ, 0: PF
            $PessoaJuridica = $Cliente->pessoa_juridica;
            $this->tomador["cnpj"] = $PessoaJuridica->getCnpj(); //(*): CNPJ do tomador, se aplicável. Caracteres não numéricos são ignorados.
            $this->tomador["razao_social"] = $PessoaJuridica->razao_social; //: Razão social ou nome do tomador. Tamanho: 115 caracteres.
//            if (!$PessoaJuridica->isencao_ie) {
//                $this->tomador["inscricao_municipal"] = $PessoaJuridica->getIe(); //inscricao_municipal: Inscrição municipal do tomador. Caracteres não numéricos são ignorados.
//            }
        } else {
            $PessoaFisica = $Cliente->pessoa_fisica;
            $this->tomador["cpf"] = $PessoaFisica->getCpf();//(*): CPF do tomador, se aplicável. Caracteres não numéricos são ignorados.
            $this->tomador["razao_social"] = $Cliente->nome_responsavel; //: Razão social ou nome do tomador. Tamanho: 115 caracteres.
        }

        $Contato = $Cliente->contato;

        $endereco["logradouro"] = $Contato->logradouro; // Nome do logradouro. Tamanho: 125 caracteres.
        $endereco["tipo_logradouro"] = ""; //Tipo do logradouro. Usado apenas para o município de São Paulo. Valor padrão: os 3 primeiros caracteres do logradouro. Tamanho: 3 caracteres.
        $endereco["numero"] = $Contato->numero; //: Número do endereço. Tamanho: 10 caracteres.
        if ($Contato->complemento != "") {
            $endereco["complemento"] = $Contato->complemento; //: Complemento do endereço. Tamanho: 60 caracteres.
        }
        $endereco["bairro"] = $Contato->bairro; //: Bairro. Tamanho: 60 caracteres.
//        $endereco["codigo_municipio"] = $this->servico_params_fixos['codigo_municipio']; //: código IBGE do município.
        $endereco["codigo_municipio"] = $Contato->codigo_municipio; //: código IBGE do município.
        $endereco["uf"] = $Contato->estado; //: UF do endereço. Tamanho: 2 caracteres.
        $endereco["cep"] = $Contato->getCep(); //: CEP do endereço. Caracteres não numéricos são ignorados.

        $this->tomador["telefone"] = $Contato->getTelefone();
        if (($Cliente->email_nota != NULL) && ($Cliente->email_nota != "")) {
            $this->tomador["email"] = $Cliente->email_nota;
        }
        $this->tomador["endereco"] = $endereco;
    }

    public function setServico()
    {
        //# VALOR DE DEDUÇOES ate BASE DE CALCULO= SO SAO USADAS QNDO EMPRESA NAO E SIMPLES. CASO CONTRARIO EM BRANCO OU ZERO.

        $valores = $this->_FATURAMENTO_->getValores();
        $valor_aproximado_tributos = ($valores->valor_nfse_float * $this->servico_params_fixos['porcentagem_tributos_float']) / 100;
        $discriminacao = $this->servico_params_fixos['discriminacao'] .
            ' (' . $this->servico_params_fixos['porcentagem_tributos_real'] . ') - ' .
            DataHelper::getFloat2RealMoeda($valor_aproximado_tributos);
        $this->servico = [
            "valor_liquido" => $valores->valor_nfse_float,
            "valor_servicos" => $valores->valor_nfse_float,//valor_servicos(*): Valor dos serviços.
            "valor_deducoes" => 0,//valor_deducoes: Valor das deduções.
            "valor_pis" => 0,//valor_pis: Valor do PIS.
            "valor_cofins" => 0,//valor_cofins: Valor do COFINS.
            "valor_inss" => 0,//valor_inss: Valor do INSS.
            "valor_ir" => 0,//valor_ir: Valor do IS.
            "valor_csll" => 0,//valor_csll: Valor do CSLL
            "iss_retido" => 0,//iss_retido(*): Informar verdadeiro ou falso se o ISS foi retido.
            "valor_iss" => 0,//valor_iss: Valor do ISS. Campo ignorado pelo município de São Paulo.
            "valor_iss_retido" => 0,//valor_iss_retido: Valor do ISS Retido. Campo ignorado pelo município de São Paulo.
            "outras_retencoes" => 0,//outras_retencoes: Valor de outras retenções.  Campo ignorado pelo município de São Paulo.

            "base_calculo" => 0,//base_calculo: Base de cálculo do ISS, valor padrão igual ao valor_servicos. Campo ignorado pelo município de São Paulo.

            //3. A aliquota não deve ser enviada para optantes do simples nacional
            "aliquota" => $this->servico_params_fixos['aliquota'],//aliquota: Aliquota do ISS.

            "desconto_incondicionado" => 0,//desconto_incondicionado: Valor do desconto incondicionado. Campo ignorado pelo município de São Paulo.
            "desconto_condicionado" => 0,//desconto_condicionado: Valor do desconto incondicionado. Campo ignorado pelo município de São Paulo.
            "item_lista_servico" => $this->servico_params_fixos['item_lista_servico'],//item_lista_servico (*): informar o código da lista de serviços, de acordo com a Lei Complementar 116/2003. Utilize outra tabela para o município de São Paulo.
            //2. Não deve ser enviado o código cnae para esta prefeitura
//            "codigo_cnae" => $this->servico_params_fixos['codigo_cnae'],//codigo_cnae: Informar o código CNAE. Campo ignorado pelo município de São Paulo.

            "codigo_tributario_municipio" => $this->servico_params_fixos['codigo_tributario_municipio'],//codigo_tributario_municipio: Informar o código tributário de acordo com a tabela de cada município (não há um padrão). Campo ignorado pelo município de São Paulo.
            "discriminacao" => $discriminacao,//discriminacao(*): Discriminação dos serviços. Tamanho: 2000 caracteres.
//            "codigo_municipio" => $this->servico_params_fixos['codigo_municipio'],//codigo_municipio(*): Informar o código IBGE do município de prestação do serviço.
            "codigo_municipio" => $this->_EMPRESA_->icms_codigo_municipio,//codigo_municipio(*): Informar o código IBGE do município de prestação do serviço.
            "percentual_total_tributos" => 0,//percentual_total_tributos: Percentual aproximado de todos os impostos, de acordo com a Lei da Transparência. No momento disponível apenas para São Paulo.
//                    "fonte_total_tributos" =>0 ,//fonte_total_tributos: Fonte de onde foi retirada a informação de total de impostos, por exemplo, “IBPT”. No momento disponível apenas para São Paulo.
        ];

        return true;
//        echo json_encode($NfeItens);
    }

}
<?php

namespace App\Models\NotasFiscais;

use App\Models\Empresa;
use App\Models\Faturamento;
use Carbon\Carbon;

/**
 * LaravelFocusnfe
 *
 * @author Leonardo Zanin <silva.zanin@gmail.com>
 */
class NFe extends NF
{
//  http://homologacao.acrasnfe.acras.com.br/panel/dashboard
//  https://api.focusnfe.com.br/panel/login

    public $params_fixos = [
        'cest_default' => '0106400',
    ];
    private $now;
    private $cabecalho;
    private $emitente;
    private $destinatario;
    private $transportadora;
    private $tributacao;
    private $itens;

    function __construct($debug, Faturamento $faturamento)
    {
        $this->debug = $debug;
        if ($this->debug) {
            $this->_SERVER_ = parent::_URL_HOMOLOGACAO_;
            $this->_TOKEN_ = parent::_TOKEN_HOMOLOGACAO_;
            $this->_REF_ = $faturamento->idnfe_homologacao;
        } else {
            $this->_SERVER_ = parent::_URL_PRODUCAO_;
            $this->_TOKEN_ = parent::_TOKEN_PRODUCAO_;
            $this->_REF_ = $faturamento->idnfe_producao;
        }

        $this->_NF_TYPE_ = parent::_URL_NFe_;
        $this->now = Carbon::now();
        $this->_FATURAMENTO_ = $faturamento;

        $this->_EMPRESA_ = new Empresa();
        $this->setParams();
    }

    public function setParams()
    {
        //Configurando o cabeçalho - OK
        $this->setCabecalho();

        //Configurando o emitente - OK
        $this->setEmitente();

        //Configurando o destinatário - OK
        $this->setDestinatario();
        if ($this->debug) {
            $this->destinatario["nome_destinatario"] = 'NF-E EMITIDA EM AMBIENTE DE HOMOLOGACAO - SEM VALOR FISCAL';
        }

        //Configurando a tributação - OK
        $this->setTributacao();

        //Configurando o transportadora - OK
        $this->setTransportadora();

        //Configurando itens - OK
        $this->setItens();

        $this->_PARAMS_NF_ = array_merge(
            $this->cabecalho,
            $this->emitente,
            $this->destinatario,
            $this->tributacao,
            $this->transportadora,
            ["items" => $this->itens]
        );
        $this->writeJson();
    }

    public function setCabecalho()
    {
        $this->cabecalho = [
            "natureza_operacao" => 'Venda c/ ST VENDA', //Descrição da natureza de operação. (obrigatório) String[1-60] Tag XML natOp
            "forma_pagamento" => 1, //Forma de pagamento. Valores permitidos 0: a vista. 1: a prazo. 2: outros. (obrigatório) Tag XML indPag
            "local_destino" => 1, //Identificador de local de destino da operação. (obrigatório) Tag XML idDest
            // Valores permitidos:
            // 1: Operação interna
            // 2: Operação interestadual
            // 3: Operação com exterior
            "data_emissao" => $this->now->toW3cString(),//Data e hora de emissão. (obrigatório) Tag XML dhEmi
            "data_entrada_saida" => $this->now->toW3cString(),//Data e hora de entrada (em notas de entrada) ou saída (em notas de saída). Tag XML dhSaiEnt
            "tipo_documento" => 1, //Tipo da nota fiscal. (obrigatório) Tag XML tpNF
            // Valores permitidos:
            // 0: Nota de entrada.
            // 1: Nota de saída.
            "finalidade_emissao" => 1, // Finalidade da nota fiscal. (obrigatório) Tag XML finNFe
            // Valores permitidos 1:
            // Nota normal. 2:
            // Nota complementar. 3:
            // Nota de ajuste. 4:
            // Devolução de mercadoria.
            "consumidor_final" => 1, //Indica operação com consumidor (obrigatório) Tag XML indFinal  final
            // Valores permitidos:
            // 0: Normal
            // 1: Consumidor final
            "presenca_comprador" => 0, //Indicador de presença do comprador no estabelecimento comercial no momento da operação. (obrigatório) Tag XML indPres
            // Valores permitidos:
            // 0: Não se aplica (por exemplo, para a Nota Fiscal complementar ou de ajuste);
            // 1: Operação presencial
            // 2: Operação não presencial, pela Internet
            // 3: Operação não presencial, Teleatendimento
            // 4: NFC-e em operação com entrega em domicílio
            // 9: Operação não presencial, outros
        ];
    }

    public function setEmitente()
    {
        $this->emitente = [
            "cnpj_emitente" => $this->_EMPRESA_->cnpj,
            "inscricao_estadual_emitente" => $this->_EMPRESA_->ie,
            "inscricao_municipal_emitente" => $this->_EMPRESA_->im,
            "cnae_fiscal_emitente" => $this->_EMPRESA_->cnae_fiscal,
            "regime_tributario_emitente" => $this->_EMPRESA_->regime_tributario,
            "nome_emitente" => $this->_EMPRESA_->razao_social,
            "nome_fantasia_emitente" => $this->_EMPRESA_->nome_fantasia,

            "logradouro_emitente" => $this->_EMPRESA_->logradouro,
            "numero_emitente" => $this->_EMPRESA_->numero,
            "bairro_emitente" => $this->_EMPRESA_->bairro,
            "municipio_emitente" => $this->_EMPRESA_->cidade,
            "uf_emitente" => $this->_EMPRESA_->estado,
            "cep_emitente" => $this->_EMPRESA_->cep,
            "telefone_emitente" => $this->_EMPRESA_->telefone,
        ];
    }

    public function setDestinatario()
    {
        $Cliente = $this->_FATURAMENTO_->cliente;
        if ($Cliente->idpjuridica != NULL) { //1:PJ, 0: PF
            $PessoaJuridica = $Cliente->pessoa_juridica;
            $this->destinatario["nome_destinatario"] = $PessoaJuridica->razao_social;
            $this->destinatario["cnpj_destinatario"] = $PessoaJuridica->getCnpj();
            if ($PessoaJuridica->isencao_ie) {
                $this->destinatario["indicador_inscricao_estadual_destinatario"] = '9';
                $this->destinatario["inscricao_estadual_destinatario"] = 'ISENTO';
            } else {
                $this->destinatario["indicador_inscricao_estadual_destinatario "] = '1';
                $this->destinatario["inscricao_estadual_destinatario"] = $PessoaJuridica->getIe();
            }
        } else {
            $PessoaFisica = $Cliente->pessoa_fisica;
            $this->destinatario["nome_destinatario"] = $Cliente->nome_responsavel;
            $this->destinatario["cpf_destinatario"] = $PessoaFisica->getCpf();
            $this->destinatario["inscricao_estadual_destinatario"] = 'ISENTO';
        }
        $Contato = $Cliente->contato;
        if (($Cliente->email_nota != NULL) && ($Cliente->email_nota != "")) {
            $this->destinatario["email_destinatario"] = $Cliente->email_nota;
        }
        $this->destinatario["telefone_destinatario"] = $Contato->getTelefone();
        $this->destinatario["logradouro_destinatario"] = $Contato->logradouro;
        $this->destinatario["numero_destinatario"] = $Contato->numero;
        $this->destinatario["bairro_destinatario"] = $Contato->bairro;
        $this->destinatario["municipio_destinatario"] = $Contato->cidade;
        $this->destinatario["uf_destinatario"] = $Contato->estado;
//        $this->destinatario["pais_destinatario"]                = 'Brasil';
        $this->destinatario["cep_destinatario"] = $Contato->getCep();

        if ($this->debug) {
            $this->destinatario["nome_destinatario"] = 'NF-E EMITIDA EM AMBIENTE DE HOMOLOGACAO - SEM VALOR FISCAL';
        }
        return true;
        $this->destinatario = [
            "nome_destinatario" => 'NF-E EMITIDA EM AMBIENTE DE HOMOLOGACAO - SEM VALOR FISCAL', //Nome ou razão social do destinatário. (obrigatório) String[2-60] Tag XML xNome
            "cnpj_destinatario" => '71322150003932', //CNPJ do destinatário. Se este campo for informado não deve ser informado o CPF. Integer[14] Tag XML CNPJ
//            "cpf_destinatario"                              => '10812933000137', //CPF do destinatário. Se este campo for informado não deve ser informado o CNPJ. Integer[11] Tag XML CPF
            "indicador_inscricao_estadual_destinatario " => '9', // Inscrição Estadual do destinatário. (obrigatório) Tag XML indIEDest
            // Valores permitidos
            // 1: Contribuinte ICMS (informar a IE do destinatário).
            // 2: Contribuinte isento de Inscrição no cadastro de Contribuintes do ICMS
            // 9: Não Contribuinte, que pode ou não possuir Inscrição Estadual no Cadastro de Contribuintes do ICMS.
            "inscricao_estadual_destinatario" => '664199114114', //Inscrição Estadual do destinatário.  Integer[2-14] Tag XML IE
            "email_destinatario" => 'sonia.felicio@savegnago.com', // E-mail do destinatário. String[1-60] Tag XML email
            // O destinatário receberá um e-mail com o XML e a DANFE gerados,
            // adicione mais de um email separando por vírgulas.
            // Será informado apenas o primeiro email no XML da NFe mas todos os emails informados receberão os arquivos.

            "telefone_destinatario" => '1639462088',
            "logradouro_destinatario" => 'AV. NOSSA SENHORA APARECIDA',
            "numero_destinatario" => '2021',
            "bairro_destinatario" => 'SAO JOAO',
            "municipio_destinatario" => 'Sertaozinho',
            "uf_destinatario" => 'SP',
//            "pais_destinatario"                             => 'Brasil', //Nome do país do destinatário. (Apenas se diferente de Brasil). Integer[2-4] Tag XML xPais
            "cep_destinatario" => '14170150',
        ];
    }

    public function setTributacao()
    {
        $valores = $this->_FATURAMENTO_->getValores();
        $this->tributacao = [
            "icms_base_calculo" => "0.00", //Valor total da base de cálculo do ICMS. (obrigatório) Decimal[13.2] Tag XML vBC
            "icms_valor_total" => "0.00", //Valor total do ICMS. (obrigatório) Decimal[13.2] Tag XML vICMS
            "icms_valor_total_desonerado" => "0.00", //Valor total do ICMS.Desonerado. (obrigatório) Decimal[13.2] Tag XML vICMSDeson
            "icms_base_calculo_st" => "0.00", //Valor total da base de cálculo do ICMS do substituto tributário. (obrigatório) Decimal[13.2] Tag XML vBCST
            "icms_valor_total_st" => "0.00", //Valor total do ICMS do substituto tributário. (obrigatório) Decimal[13.2] Tag XML vST

            "valor_seguro" => "0.00", //Valor total do seguro. (obrigatório) Decimal[13.2] Tag XML vSeg
            "valor_total_ii" => "0.00", //Valor total do imposto de importação. (obrigatório) Decimal[13.2] Tag XML vII
            "valor_ipi" => "0.00", //Valor total do IPI. (obrigatório) Decimal[13.2] Tag XML vIPI
            "valor_pis" => "0.00", //Valor do PIS. (obrigatório) Decimal[13.2] Tag XML vPIS
            "valor_cofins" => "0.00", //Valor do COFINS. (obrigatório) Decimal[13.2] Tag XML vCOFINS
            "valor_outras_despesas" => "0.00", //Valor das despesas acessórias. (obrigatório) Decimal[13.2] Tag XML vOutro

//            "valor_produtos" => $valores->valor_total_pecas_float, //Valor total dos produtos. (obrigatório) Decimal[13.2] Tag XML vProd
//            "valor_total" => $valores->valor_total_pecas_float, //Valor total da nota fiscal. (obrigatório) Decimal[13.2] Tag XML vNF

            "valor_total" => $valores['valor_total_pecas_float'], //Valor total da nota fiscal. (obrigatório) Decimal[13.2] Tag XML vNF
            "valor_desconto" => $valores['valor_desconto_pecas_float'], //Valor total do desconto. (obrigatório) Decimal[13.2] Tag XML vDesc
            "valor_produtos" => $valores['valor_total_pecas_float'] + $valores['valor_desconto_pecas_float'], //Valor total dos produtos. (obrigatório) Decimal[13.2] Tag XML vProd
        ];
    }

    public function setTransportadora()
    {
        $this->transportadora = [
            "modalidade_frete" => $this->_EMPRESA_->modalidade_frete, // Modalidade do frete.(obrigatório) Tag XML modFrete.
            // Valores permitidos
            // 0: por conta do emitente
            // 1: por conta do destinatário
            // 2: por conta de terceiros
            // 9: sem frete
            "nome_transportador" => $this->_EMPRESA_->razao_social, //Nome ou razão social do transportador. String[2-60] Tag XML xNome
            "cnpj_transportador" => $this->_EMPRESA_->cnpj, //CNPJ do transportador. Se este campo for informado não deverá ser informado o CPF. Integer[14] Tag XML CNPJ
            "inscricao_estadual_transportador" => $this->_EMPRESA_->ie, //Inscrição Estadual do transportador. String[2-14] Tag XML IE
//            "cpf_transportador "                    => '48740351000165', //CPF do transportador. Se este campo for informado não deverá ser informado o CNPJ. Integer[11] Tag XML CNPJ
            "endereco_transportador" => $this->_EMPRESA_->logradouro, //Endereço (logradouro, número, complemento e bairro) do transportador. String[1-60] Tag XML xEnder
            "municipio_transportador" => $this->_EMPRESA_->cidade, //Município do transportador. String[1-60] Tag XML xMun
            "uf_transportador" => $this->_EMPRESA_->estado, //UF do transportador. String[2] Tag XML UF

            "transporte_icms_servico" => $this->_EMPRESA_->icms_servico, //Valor do serviço de transporte. Decimal[13.2] Tag XML vServ
            "transporte_icms_base_calculo" => $this->_EMPRESA_->icms_base_calculo, //Base de cálculo da retenção do ICMS de transporte. Decimal[13.2] Tag XML vBCRet
            "transporte_icms_aliquota" => $this->_EMPRESA_->icms_aliquota, //Alíquota da retenção do ICMS de transporte. Decimal[3.2-4] Tag XML pICMSRet
            "transporte_icms_valor" => $this->_EMPRESA_->icms_valor, //Valor retido do ICMS de transporte. Decimal[13.2] Tag XML vICMSRet
            "transporte_icms_cfop" => $this->_EMPRESA_->icms_cfop, //CFOP do serviço de transporte. Integer[4] Tag XML CFOP
            // (Valores aceitos: 5351, 5352, 5353, 5354, 5355, 5356, 5357,
            // 5359, 5360, 5931, 5932, 6351, 6352, 6353, 6354,
            // 6355, 6356, 6357, 6359, 6360, 6931, 6932, 7358)
            "transporte_icms_codigo_municipio" => $this->_EMPRESA_->icms_codigo_municipio, //Código do município de ocorrência do fato gerador. Integer[7] Tag XML cMunFG

            // "informacoes_adicionais_contribuinte" => 'Não Incidência ICMS conforme Decisão...', //Informações adicionais de interesse do contribuinte. String[1-5000] Tag XML infCpl

        ];
    }

    public function setItens()
    {
        $item_n = 1;

//        return $this->_FATURAMENTO_->getAllPecas();
        foreach ($this->_FATURAMENTO_->getAllPecas() as $item) {
            $NfeItens[] = [
                "numero_item" => $item_n, //Número (índice) do item na nota fiscal, começando por 1. (obrigatório) Integer[1-3] Tag XML nItem
                "codigo_produto" => $item->idpeca, //Código interno do produto. Se não existir deve ser usado o CFOP no formato CFOP9999. (obrigatório) String[1-60] Tag XML cProd
//                    "codigo_barras_comercial" => $pecas_utilizada->peca->codigo_barras, //Código GTIN/EAN do produto. Integer[0,8,12,13,14] Tag XML cEAN
                "descricao" => $item->peca->descricao, //Descrição do produto. (obrigatório) String[1-120] Tag XML xProd
                "codigo_ncm" => $item->peca->peca_tributacao->ncm->codigo, //Código NCM do produto. Integer[2,8] Tag XML NCM
                "codigo_cest" => $item->peca->peca_tributacao->cest, //Código Especificador da Substituição Tributária. Integer[7] Tag XML CEST
//                    "codigo_ex_tipi " => **, //Código EX TIPI do produto. Integer[2-3] Tag XML EXTIPI
                "cfop" => $item->peca->peca_tributacao->cfop->numeracao, //CFOP do produto. (obrigatório) Integer[4] Tag XML CFOP
                "unidade_comercial" => $item->peca->unidade->codigo, //Unidade comercial. (obrigatório) String[1-6] Tag XML uCom

                //MESMA COISA DO CAMPO quantidade_comercial E unidade_tributave

                "quantidade_comercial" => $item->quantidade_comercial, //Quantidade comercial. (obrigatório) Decimal[11.0-4] Tag XML qCom
                "valor_unitario_comercial" => $item->valor_float(), //Valor unitário comercial. (obrigatório) Decimal[11.0-10] Tag XML vUnCom
                "valor_bruto" => $item->quantidade_comercial * $item->valor_float(), //Valor bruto. Deve ser igual ao produto de Valor unitário comercial com quantidade comercial. Decimal[13.2] Tag XML vProd
//                    "codigo_barras_tributavel" => "**", //Código GTIN/EAN tributável. Integer[0,8,12,13,14] Tag XML cEANTrib
                "unidade_tributavel" => $item->peca->unidade->codigo, //Unidade tributável. (obrigatório) String[1-6] Tag XML uTrib
                "quantidade_tributavel" => $item->quantidade_comercial, //Quantidade tributável. (obrigatório) Decimal[11.0-4] Tag XML qTrib
                "valor_unitario_tributavel" => $item->valor_float(), //Valor unitário tributável. (obrigatório) Decimal[11.0-10] Tag XML vUnTrib

                //O valor do frete vai ser incluído dentro do produto mesmo (compo é hoje) ou vai depender da O.S?
                "valor_frete" => $item->peca->peca_tributacao->valor_frete_float(), //Valor do frete. Decimal[13.2] Tag XML vFrete
                "valor_seguro" => $item->peca->peca_tributacao->valor_seguro_float(), //Valor do seguro. Decimal[13.2] Tag XML vSeg
                "valor_desconto" => $item->desconto, //Valor do desconto. Decimal[13.2] Tag XML vSeg
//                    "valor_outras_despesas" =>  ***, //Valor de outras despesas acessórias. Decimal[13.2] Tag XML vOutro


                "inclui_no_total" => "1", //Valor do item (valor_bruto) compõe valor total da NFe (valor_produtos)? (obrigatório) Tag XML indTot
                //Valores permitidos:
                // 0: não
                // 1: sim
                "icms_origem" => $item->peca->peca_tributacao->icms_origem, //Origem da mercadoria. (obrigatório)
                //Valores permitidos:
                //0: nacional
                //1: estrangeira (importação direta)
                //2: estrangeira (adquirida no mercado interno)
                //3: nacional com mais de 40% de conteúdo estrangeiro
                //4: nacional produzida através de processos produtivos básicos
                //5: nacional com menos de 40% de conteúdo estrangeiro
                //6: estrangeira (importação direta) sem produto nacional similar
                //7: estrangeira (adquirida no mercado interno) sem produto nacional similar
                "icms_situacao_tributaria" => $item->peca->peca_tributacao->icms_situacao_tributaria,
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
                "pis_situacao_tributaria" => $item->peca->peca_tributacao->pis_situacao_tributaria,
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
                "cofins_situacao_tributaria" => $item->peca->peca_tributacao->cofins_situacao_tributaria
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

        $this->itens = $NfeItens;
        return true;
    }

}
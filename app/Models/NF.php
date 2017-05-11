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
class NF
{
//  http://homologacao.acrasnfe.acras.com.br/panel/dashboard
//  https://api.focusnfe.com.br/panel/login

    CONST _URL_NFe_ = 'nfe2';
    CONST _URL_NFSe_ = 'nfse';
    CONST _URL_HOMOLOGACAO_ = 'http://homologacao.acrasnfe.acras.com.br';
//    CONST _URL_PRODUCAO_ = 'http://homologacao.acrasnfe.acras.com.br/nfe2';
    CONST _URL_PRODUCAO_ = 'https://api.focusnfe.com.br';

    CONST _TOKEN_HOMOLOGACAO_ = 'eR7XfUMdytg6J4nSkirtIf7jPMtc7vzK';
//    CONST _TOKEN_PRODUCAO_ = 'eR7XfUMdytg6J4nSkirtIf7jPMtc7vzK';
    CONST _TOKEN_PRODUCAO_ = 'QmPEhv5PrVGmkXpUtZIw7nvx0ZUOrDos';

    CONST _STATUS_AUTORIZADO_ = 'autorizado';//autorizado – Neste caso a consulta irá conter os demais dados da nota fiscal
    CONST _STATUS_PROCESSANDO_AUTORIZACAO_ = 'processando_autorizacao';//processando_autorizacao – A nota ainda está em processamento. Não será devolvido mais nenhum campo além do status
    CONST _STATUS_ERRO_AUTORIZACAO_ = 'erro_autorizacao';//erro_autorizacao – A nota foi enviada ao SEFAZ mas houve um erro no momento da autorização.O campo status_sefaz e mensagem_sefaz irão detalhar o erro ocorrido. O SEFAZ valida apenas um erro de cada vez.
    CONST _STATUS_ERRO_CANCELAMENTO_ = 'erro_cancelamento';//erro_cancelamento – Foi enviada uma tentativa de cancelamento que foi rejeitada pelo SEFAZ. Os campos status_sefaz_cancelamento e mensagem_sefaz_cancelamento irão detalhar o erro ocorrido. Perceba que a nota neste estado continua autorizada.
    CONST _STATUS_CANCELADO_ = 'cancelado';//cancelado – A nota foi cancelada. Além dos campos devolvidos quanto a nota é autorizada, é disponibilizado o campo caminho_xml_cancelamento que contém o protocolo de cancelamento. O campo caminho_danfe deixa de existir quando a nota é cancelada.

    public $_REF_;
    public $debug;
    public $_PARAMS_NF_;
    protected $_SERVER_;
    protected $_NF_TYPE_;
    protected $_TOKEN_;

    static public function consultar($ref, $testing = true, $type = 'nfse')
    {
        if ($testing) {
            $_SERVER_ = self::_URL_HOMOLOGACAO_;
            $_TOKEN_ = self::_TOKEN_HOMOLOGACAO_;
        } else {
            $_SERVER_ = self::_URL_PRODUCAO_;
            $_TOKEN_ = self::_TOKEN_PRODUCAO_;
        }
        $ch = curl_init();
//        curl_setopt($ch, CURLOPT_URL, "http://homologacao.acrasnfe.acras.com.br/nfse/" . $ref . "?token=" . $token);
        curl_setopt($ch, CURLOPT_URL, $_SERVER_ . "/" . $type . "/" . $ref . "?token=" . $_TOKEN_);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array());

        $retorno = [
            'ref' => $ref,
            'type' => $type,
            'url' => $_SERVER_,
            'body' => Yaml::parse(curl_exec($ch)),
            'status' => curl_getinfo($ch, CURLINFO_HTTP_CODE),
        ];
        curl_close($ch);
        return ($retorno);
    }

    static public function cancelar($ref, $testing = true, $type = 'nfse')
    {
        if ($testing) {
            $_SERVER_ = parent::_URL_HOMOLOGACAO_;
            $_TOKEN_ = parent::_TOKEN_HOMOLOGACAO_;
        } else {
            $_SERVER_ = parent::_URL_PRODUCAO_;
            $_TOKEN_ = parent::_TOKEN_PRODUCAO_;
        }
        $ch = curl_init();
//        curl_setopt($ch, CURLOPT_URL, "http://homologacao.acrasnfe.acras.com.br/nfse/" . $ref . "?token=" . $token);
        curl_setopt($ch, CURLOPT_URL, $_SERVER_ . "/" . $type . "/" . $ref . "&token=" . $_TOKEN_);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array());
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");

        $retorno = [
            'type' => $type,
            'url' => $_SERVER_,
            'body' => Yaml::parse(curl_exec($ch)),
            'status' => curl_getinfo($ch, CURLINFO_HTTP_CODE),
        ];
        curl_close($ch);
        return ($retorno);
    }

    public function emitir()
    {
        $SERVER = $this->_SERVER_;
        $URL = "http://homologacao.acrasnfe.acras.com.br/" . $this->_NF_TYPE_ . ".json?token=" . $this->_TOKEN_ . "&ref=" . $this->_REF_;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $URL);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($this->_PARAMS_NF_));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/plain'));
        $body = curl_exec($ch);
        $result = curl_getinfo($ch, CURLINFO_HTTP_CODE);
//as três linhas abaixo imprimem as informações retornadas pela API, aqui o seu sistema deverá
//interpretar e lidar com o retorno

        $retorno = (object)[
            'type' => $this->_NF_TYPE_,
            'body' => json_decode(curl_exec($ch)),
            'result' => curl_getinfo($ch, CURLINFO_HTTP_CODE),
        ];
        curl_close($ch);

        return ($retorno);

//        echo $URL.'<BR>';
//        print("STATUS: ".$result."\n");
//        print("BODY: ".$body."\n\n");
//        curl_close($ch);

    }

//    public function ITENS()
//    {
//        $this->nfe_itens = array(
//            [
//                "numero_item" => "1",
//                "codigo_produto" => "15",
//                "descricao" => "Bateria 6.0V, 4.5Ah IND- 9098C -CT",
//                "codigo_ncm" => "85078000",
//                "codigo_cest" => "0106400",
//                "cfop" => "5405",
//                "unidade_comercial" => "und",
//                "quantidade_comercial" => "2.0000",
//                "valor_unitario_comercial" => "92.0000000000",
//                "valor_bruto" => "184.00",
//                "unidade_tributavel" => "92.00",
//                "quantidade_tributavel" => "2.0000",
//                "valor_unitario_tributavel" => "92.0000000000",
//                "inclui_no_total" => "1",
//                "icms_origem" => "0",
//                "icms_situacao_tributaria" => "500",
//                "pis_situacao_tributaria" => "07",
//                "cofins_situacao_tributaria" => "07"
//            ], [
//                "numero_item" => "2",
//                "codigo_produto" => "05",
//                "descricao" => "Cabe\u00e7ote Termico Prix 5 - 4 - due",
//                "codigo_ncm" => "84439942",
//                "codigo_cest" => "0106400",
//                "cfop" => "5405",
//                "unidade_comercial" => "und",
//                "quantidade_comercial" => "8.0000",
//                "valor_unitario_comercial" => "390.1000000000",
//                "valor_bruto" => "3120.80",
//                "unidade_tributavel" => "390.10",
//                "quantidade_tributavel" => "8.0000",
//                "valor_unitario_tributavel" => "390.1000000000",
//                "inclui_no_total" => "1",
//                "icms_origem" => "0",
//                "icms_situacao_tributaria" => "500",
//                "pis_situacao_tributaria" => "07",
//                "cofins_situacao_tributaria" => "07"
//            ], [
//                "numero_item" => "3",
//                "codigo_produto" => "01",
//                "descricao" => "Teclado Elgim",
//                "codigo_ncm" => "84239029",
//                "codigo_cest" => "0106400",
//                "cfop" => "5405",
//                "unidade_comercial" => "und",
//                "quantidade_comercial" => "2.0000",
//                "valor_unitario_comercial" => "127.4000000000",
//                "valor_bruto" => "254.80",
//                "unidade_tributavel" => "127.40",
//                "quantidade_tributavel" => "2.0000",
//                "valor_unitario_tributavel" => "127.4000000000",
//                "inclui_no_total" => "1",
//                "icms_origem" => "0",
//                "icms_situacao_tributaria" => "500",
//                "pis_situacao_tributaria" => "07",
//                "cofins_situacao_tributaria" => "07"
//            ], [
//                "numero_item" => "4",
//                "codigo_produto" => "02",
//                "descricao" => "Placa Fonte Elgim",
//                "codigo_ncm" => "85340019",
//                "codigo_cest" => "0106400",
//                "cfop" => "5405",
//                "unidade_comercial" => "und",
//                "quantidade_comercial" => "1.0000",
//                "valor_unitario_comercial" => "339.2900000000",
//                "valor_bruto" => "339.29",
//                "unidade_tributavel" => "339.29",
//                "quantidade_tributavel" => "1.0000",
//                "valor_unitario_tributavel" => "339.2900000000",
//                "inclui_no_total" => "1",
//                "icms_origem" => "0",
//                "icms_situacao_tributaria" => "500",
//                "pis_situacao_tributaria" => "07",
//                "cofins_situacao_tributaria" => "07"
//            ], [
//                "numero_item" => "5",
//                "codigo_produto" => "03",
//                "descricao" => "Teclado Prix 5 Toledo",
//                "codigo_ncm" => "84239029",
//                "codigo_cest" => "0106400",
//                "cfop" => "5405",
//                "unidade_comercial" => "und",
//                "quantidade_comercial" => "12.0000",
//                "valor_unitario_comercial" => "182.2400000000",
//                "valor_bruto" => "2186.88",
//                "unidade_tributavel" => "182.24",
//                "quantidade_tributavel" => "12.0000",
//                "valor_unitario_tributavel" => "182.2400000000",
//                "inclui_no_total" => "1",
//                "icms_origem" => "0",
//                "icms_situacao_tributaria" => "500",
//                "pis_situacao_tributaria" => "07",
//                "cofins_situacao_tributaria" => "07"
//            ], [
//                "numero_item" => "6",
//                "codigo_produto" => "04",
//                "descricao" => "CABO FOR\u00c7A PRIX 5",
//                "codigo_ncm" => "85444200",
//                "codigo_cest" => "0106400",
//                "cfop" => "5405",
//                "unidade_comercial" => "und",
//                "quantidade_comercial" => "4.0000",
//                "valor_unitario_comercial" => "33.5000000000",
//                "valor_bruto" => "134.00",
//                "unidade_tributavel" => "35.50",
//                "quantidade_tributavel" => "4.0000",
//                "valor_unitario_tributavel" => "33.5000000000",
//                "inclui_no_total" => "1",
//                "icms_origem" => "0",
//                "icms_situacao_tributaria" => "500",
//                "pis_situacao_tributaria" => "07",
//                "cofins_situacao_tributaria" => "07"
//            ], [
//                "numero_item" => "7",
//                "codigo_produto" => "24",
//                "descricao" => "SENSOR PRIX 5 - 6 - DUE, UNO",
//                "codigo_ncm" => "84239029",
//                "codigo_cest" => "0106400",
//                "cfop" => "5405",
//                "unidade_comercial" => "und",
//                "quantidade_comercial" => "5.0000",
//                "valor_unitario_comercial" => "182.5000000000",
//                "valor_bruto" => "912.50",
//                "unidade_tributavel" => "182.50",
//                "quantidade_tributavel" => "5.0000",
//                "valor_unitario_tributavel" => "182.5000000000",
//                "inclui_no_total" => "1",
//                "icms_origem" => "0",
//                "icms_situacao_tributaria" => "500",
//                "pis_situacao_tributaria" => "07",
//                "cofins_situacao_tributaria" => "07"
//            ], [
//                "numero_item" => "8",
//                "codigo_produto" => "08",
//                "descricao" => "PCI PRINCIPAL PRIX 5 PLUS - 5 - ETHERNET REV",
//                "codigo_ncm" => "84239029",
//                "codigo_cest" => "0106400",
//                "cfop" => "5405",
//                "unidade_comercial" => "und",
//                "quantidade_comercial" => "1.0000",
//                "valor_unitario_comercial" => "625.4100000000",
//                "valor_bruto" => "625.41",
//                "unidade_tributavel" => "625.41",
//                "quantidade_tributavel" => "1.0000",
//                "valor_unitario_tributavel" => "625.4100000000",
//                "inclui_no_total" => "1",
//                "icms_origem" => "0",
//                "icms_situacao_tributaria" => "500",
//                "pis_situacao_tributaria" => "07",
//                "cofins_situacao_tributaria" => "07"
//            ], [
//                "numero_item" => "9",
//                "codigo_produto" => "09",
//                "descricao" => "Teclado IDM",
//                "codigo_ncm" => "84239029",
//                "codigo_cest" => "0106400",
//                "cfop" => "5405",
//                "unidade_comercial" => "und",
//                "quantidade_comercial" => "1.0000",
//                "valor_unitario_comercial" => "65.0000000000",
//                "valor_bruto" => "65.00",
//                "unidade_tributavel" => "65.00",
//                "quantidade_tributavel" => "1.0000",
//                "valor_unitario_tributavel" => "65.0000000000",
//                "inclui_no_total" => "1",
//                "icms_origem" => "0",
//                "icms_situacao_tributaria" => "500",
//                "pis_situacao_tributaria" => "07",
//                "cofins_situacao_tributaria" => "07"
//            ], [
//                "numero_item" => "10",
//                "codigo_produto" => "10",
//                "descricao" => "CELULA S CURIO 500KG",
//                "codigo_ncm" => "90318060",
//                "codigo_cest" => "0106400",
//                "cfop" => "5405",
//                "unidade_comercial" => "und",
//                "quantidade_comercial" => "1.0000",
//                "valor_unitario_comercial" => "801.0000000000",
//                "valor_bruto" => "801.00",
//                "unidade_tributavel" => "801.00",
//                "quantidade_tributavel" => "1.0000",
//                "valor_unitario_tributavel" => "801.0000000000",
//                "inclui_no_total" => "1",
//                "icms_origem" => "0",
//                "icms_situacao_tributaria" => "500",
//                "pis_situacao_tributaria" => "07",
//                "cofins_situacao_tributaria" => "07"
//            ], [
//                "numero_item" => "11",
//                "codigo_produto" => "11",
//                "descricao" => "PCI PRINCIPAL PLATINA UNIFIC",
//                "codigo_ncm" => "84239029",
//                "codigo_cest" => "0106400",
//                "cfop" => "5405",
//                "unidade_comercial" => "und",
//                "quantidade_comercial" => "3.0000",
//                "valor_unitario_comercial" => "522.5900000000",
//                "valor_bruto" => "1567.77",
//                "unidade_tributavel" => "522.59",
//                "quantidade_tributavel" => "3.0000",
//                "valor_unitario_tributavel" => "522.5900000000",
//                "inclui_no_total" => "1",
//                "icms_origem" => "0",
//                "icms_situacao_tributaria" => "500",
//                "pis_situacao_tributaria" => "07",
//                "cofins_situacao_tributaria" => "07"
//            ], [
//                "numero_item" => "12",
//                "codigo_produto" => "12",
//                "descricao" => "TECLADO PLATINA CUSTOM",
//                "codigo_ncm" => "84239029",
//                "codigo_cest" => "0106400",
//                "cfop" => "5405",
//                "unidade_comercial" => "und",
//                "quantidade_comercial" => "1.0000",
//                "valor_unitario_comercial" => "127.4000000000",
//                "valor_bruto" => "127.40",
//                "unidade_tributavel" => "127.40",
//                "quantidade_tributavel" => "1.0000",
//                "valor_unitario_tributavel" => "127.4000000000",
//                "inclui_no_total" => "1",
//                "icms_origem" => "0",
//                "icms_situacao_tributaria" => "500",
//                "pis_situacao_tributaria" => "07",
//                "cofins_situacao_tributaria" => "07"
//            ], [
//                "numero_item" => "13",
//                "codigo_produto" => "13",
//                "descricao" => "Cabo Interl Filtro Linha/PCI Fonte",
//                "codigo_ncm" => "85363000",
//                "codigo_cest" => "0106400",
//                "cfop" => "5405",
//                "unidade_comercial" => "und",
//                "quantidade_comercial" => "1.0000",
//                "valor_unitario_comercial" => "55.0000000000",
//                "valor_bruto" => "55.00",
//                "unidade_tributavel" => "55.00",
//                "quantidade_tributavel" => "1.0000",
//                "valor_unitario_tributavel" => "55.0000000000",
//                "inclui_no_total" => "1",
//                "icms_origem" => "0",
//                "icms_situacao_tributaria" => "500",
//                "pis_situacao_tributaria" => "07",
//                "cofins_situacao_tributaria" => "07"
//            ], [
//                "numero_item" => "14",
//                "codigo_produto" => "14",
//                "descricao" => "FONTE FULL RANGE BALN\u00c7AS MF-8217-IDM-9098-BP",
//                "codigo_ncm" => "85299040",
//                "codigo_cest" => "0106400",
//                "cfop" => "5405",
//                "unidade_comercial" => "und",
//                "quantidade_comercial" => "8.0000",
//                "valor_unitario_comercial" => "75.0000000000",
//                "valor_bruto" => "600.00",
//                "unidade_tributavel" => "75.00",
//                "quantidade_tributavel" => "8.0000",
//                "valor_unitario_tributavel" => "75.0000000000",
//                "inclui_no_total" => "1",
//                "icms_origem" => "0",
//                "icms_situacao_tributaria" => "500",
//                "pis_situacao_tributaria" => "07",
//                "cofins_situacao_tributaria" => "07"
//            ], [
//                "numero_item" => "15",
//                "codigo_produto" => "16",
//                "descricao" => "PCI de Juncao Montada",
//                "codigo_ncm" => "85340019",
//                "codigo_cest" => "0106400",
//                "cfop" => "5405",
//                "unidade_comercial" => "und",
//                "quantidade_comercial" => "1.0000",
//                "valor_unitario_comercial" => "582.8500000000",
//                "valor_bruto" => "582.85",
//                "unidade_tributavel" => "582.85",
//                "quantidade_tributavel" => "1.0000",
//                "valor_unitario_tributavel" => "582.8500000000",
//                "valor_desconto" => "77.76",
//                "inclui_no_total" => "1",
//                "icms_origem" => "0",
//                "icms_situacao_tributaria" => "500",
//                "pis_situacao_tributaria" => "07",
//                "cofins_situacao_tributaria" => "07"
//            ], [
//                "numero_item" => "16",
//                "codigo_produto" => "20",
//                "descricao" => "Cel Carga 1100kg \"Carcara\"",
//                "codigo_ncm" => "90318060",
//                "codigo_cest" => "0106400",
//                "cfop" => "5405",
//                "unidade_comercial" => "und",
//                "quantidade_comercial" => "2.0000",
//                "valor_unitario_comercial" => "1886.1800000000",
//                "valor_bruto" => "3772.36",
//                "unidade_tributavel" => "188618",
//                "quantidade_tributavel" => "2.0000",
//                "valor_unitario_tributavel" => "1886.1800000000",
//                "inclui_no_total" => "1",
//                "icms_origem" => "0",
//                "icms_situacao_tributaria" => "500",
//                "pis_situacao_tributaria" => "07",
//                "cofins_situacao_tributaria" => "07"
//            ], [
//                "numero_item" => "17",
//                "codigo_produto" => "18",
//                "descricao" => "TECLADO 9091 INOX AZUL",
//                "codigo_ncm" => "84733099",
//                "codigo_cest" => "0106400",
//                "cfop" => "5405",
//                "unidade_comercial" => "und",
//                "quantidade_comercial" => "1.0000",
//                "valor_unitario_comercial" => "159.0000000000",
//                "valor_bruto" => "159.00",
//                "unidade_tributavel" => "159.00",
//                "quantidade_tributavel" => "1.0000",
//                "valor_unitario_tributavel" => "159.0000000000",
//                "inclui_no_total" => "1",
//                "icms_origem" => "0",
//                "icms_situacao_tributaria" => "500",
//                "pis_situacao_tributaria" => "07",
//                "cofins_situacao_tributaria" => "07"
//            ], [
//                "numero_item" => "18",
//                "codigo_produto" => "06",
//                "descricao" => "CABO INTERL. PCI FONTE FILTRO PRIX 5 - 4 - DUE - UNO",
//                "codigo_ncm" => "85363000",
//                "codigo_cest" => "0106400",
//                "cfop" => "5405",
//                "unidade_comercial" => "und",
//                "quantidade_comercial" => "1.0000",
//                "valor_unitario_comercial" => "502.5400000000",
//                "valor_bruto" => "502.54",
//                "unidade_tributavel" => "502.54",
//                "quantidade_tributavel" => "1.0000",
//                "valor_unitario_tributavel" => "502.5400000000",
//                "inclui_no_total" => "1",
//                "icms_origem" => "0",
//                "icms_situacao_tributaria" => "500",
//                "pis_situacao_tributaria" => "07",
//                "cofins_situacao_tributaria" => "07"
//            ], [
//                "numero_item" => "19",
//                "codigo_produto" => "21",
//                "descricao" => "Placa principal com entrada bateria - 9098C REV",
//                "codigo_ncm" => "85340019",
//                "codigo_cest" => "0106400",
//                "cfop" => "5405",
//                "unidade_comercial" => "und",
//                "quantidade_comercial" => "1.0000",
//                "valor_unitario_comercial" => "920.0000000000",
//                "valor_bruto" => "920.00",
//                "unidade_tributavel" => "920.00",
//                "quantidade_tributavel" => "1.0000",
//                "valor_unitario_tributavel" => "920.0000000000",
//                "inclui_no_total" => "1",
//                "icms_origem" => "0",
//                "icms_situacao_tributaria" => "500",
//                "pis_situacao_tributaria" => "07",
//                "cofins_situacao_tributaria" => "07"
//            ], [
//                "numero_item" => "20",
//                "codigo_produto" => "07",
//                "descricao" => "TRANSFORMADOR PRIX3-BP-MF-DP",
//                "codigo_ncm" => "85043199",
//                "codigo_cest" => "0106400",
//                "cfop" => "5405",
//                "unidade_comercial" => "und",
//                "quantidade_comercial" => "1.0000",
//                "valor_unitario_comercial" => "127.5300000000",
//                "valor_bruto" => "127.53",
//                "unidade_tributavel" => "127.53",
//                "quantidade_tributavel" => "1.0000",
//                "valor_unitario_tributavel" => "127.5300000000",
//                "inclui_no_total" => "1",
//                "icms_origem" => "0",
//                "icms_situacao_tributaria" => "500",
//                "pis_situacao_tributaria" => "07",
//                "cofins_situacao_tributaria" => "07"
//            ], [
//                "numero_item" => "21",
//                "codigo_produto" => "23",
//                "descricao" => "Cabo Int. PCI Principal com cabe\u00e7ote PRIX5 PRIX6 PRIX DUE",
//                "codigo_ncm" => "85444200",
//                "codigo_cest" => "0106400",
//                "cfop" => "5405",
//                "unidade_comercial" => "und",
//                "quantidade_comercial" => "2.0000",
//                "valor_unitario_comercial" => "222.3000000000",
//                "valor_bruto" => "444.60",
//                "unidade_tributavel" => "222.30",
//                "quantidade_tributavel" => "2.0000",
//                "valor_unitario_tributavel" => "222.3000000000",
//                "inclui_no_total" => "1",
//                "icms_origem" => "0",
//                "icms_situacao_tributaria" => "500",
//                "pis_situacao_tributaria" => "07",
//                "cofins_situacao_tributaria" => "07"
//            ]);
//        return true;
//    }



}
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

    CONST _CEST_DEFAULT_ = '0106400';
    CONST _STATUS_AUTORIZADO_ = 'autorizado';//autorizado – Neste caso a consulta irá conter os demais dados da nota fiscal
    CONST _STATUS_PROCESSANDO_AUTORIZACAO_ = 'processando_autorizacao';//processando_autorizacao – A nota ainda está em processamento. Não será devolvido mais nenhum campo além do status
    CONST _STATUS_ERRO_AUTORIZACAO_ = 'erro_autorizacao';//erro_autorizacao – A nota foi enviada ao SEFAZ mas houve um erro no momento da autorização.O campo status_sefaz e mensagem_sefaz irão detalhar o erro ocorrido. O SEFAZ valida apenas um erro de cada vez.
    CONST _STATUS_ERRO_CANCELAMENTO_ = 'erro_cancelamento';//erro_cancelamento – Foi enviada uma tentativa de cancelamento que foi rejeitada pelo SEFAZ. Os campos status_sefaz_cancelamento e mensagem_sefaz_cancelamento irão detalhar o erro ocorrido. Perceba que a nota neste estado continua autorizada.
    CONST _STATUS_CANCELADO_ = 'cancelado';//cancelado – A nota foi cancelada. Além dos campos devolvidos quanto a nota é autorizada, é disponibilizado o campo caminho_xml_cancelamento que contém o protocolo de cancelamento. O campo caminho_danfe deixa de existir quando a nota é cancelada.

    public $_REF_;
    public $debug;
    public $NFe_params;
    protected $_SERVER_;
    protected $_NF_TYPE_;
    protected $_TOKEN_;

    static public function consultar($ref, $testing = true, $type = 'nfse')
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
        curl_setopt($ch, CURLOPT_URL, $_SERVER_ . "/" . $type . "/" . $ref . "?token=" . $_TOKEN_);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array());

        $retorno = [
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
            'url' => $_SERVER_,
            'body' => Yaml::parse(curl_exec($ch)),
            'status' => curl_getinfo($ch, CURLINFO_HTTP_CODE),
        ];
        curl_close($ch);
        return ($retorno);
    }

    public function emitir()
    {
        $ch = curl_init();
//        curl_setopt($ch, CURLOPT_URL, "http://homologacao.acrasnfe.acras.com.br/nfse?token=".$token."&ref=" . $ref);
        curl_setopt($ch, CURLOPT_URL, $this->_SERVER_ . "/" . $this->_NF_TYPE_ . "?token=" . $this->_TOKEN_ . ".&ref=" . $this->_REF_);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($this->NFe_params));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/plain'));

        $retorno = (object)[
            'body' => json_decode(curl_exec($ch)),
            'result' => curl_getinfo($ch, CURLINFO_HTTP_CODE),
        ];
        curl_close($ch);
        return ($retorno);
    }

}
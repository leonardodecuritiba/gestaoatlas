<?php

namespace App\Models\NotasFiscais;

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
 * @author Leonardo Zanin <silva.zanin@gmail.com>
 */
class NF
{
//  http://homologacao.acrasnfe.acras.com.br/panel/dashboard
//  https://api.focusnfe.com.br/panel/login

    CONST _URL_NFe_ = 'nfe2';
    CONST _URL_NFSe_ = 'nfse';
    CONST _URL_HOMOLOGACAO_ = 'http://homologacao.acrasnfe.acras.com.br';
    CONST _URL_PRODUCAO_ = 'https://api.focusnfe.com.br';
    CONST _TOKEN_HOMOLOGACAO_ = 'eR7XfUMdytg6J4nSkirtIf7jPMtc7vzK';
    CONST _TOKEN_PRODUCAO_ = 'QmPEhv5PrVGmkXpUtZIw7nvx0ZUOrDos';


    CONST _STATUS_AUTORIZADO_ = 'autorizado';//autorizado – Neste caso a consulta irá conter os demais dados da nota fiscal
    CONST _STATUS_PROCESSANDO_AUTORIZACAO_ = 'processando_autorizacao';//processando_autorizacao – A nota ainda está em processamento. Não será devolvido mais nenhum campo além do status
    CONST _STATUS_ERRO_AUTORIZACAO_ = 'erro_autorizacao';//erro_autorizacao – A nota foi enviada ao SEFAZ mas houve um erro no momento da autorização.O campo status_sefaz e mensagem_sefaz irão detalhar o erro ocorrido. O SEFAZ valida apenas um erro de cada vez.
    CONST _STATUS_ERRO_CANCELAMENTO_ = 'erro_cancelamento';//erro_cancelamento – Foi enviada uma tentativa de cancelamento que foi rejeitada pelo SEFAZ. Os campos status_sefaz_cancelamento e mensagem_sefaz_cancelamento irão detalhar o erro ocorrido. Perceba que a nota neste estado continua autorizada.
    CONST _STATUS_CANCELADO_ = 'cancelado';//cancelado – A nota foi cancelada. Além dos campos devolvidos quanto a nota é autorizada, é disponibilizado o campo caminho_xml_cancelamento que contém o protocolo de cancelamento. O campo caminho_danfe deixa de existir quando a nota é cancelada.

    public $debug;
    public $_REF_;
    public $_PARAMS_NF_;
    protected $_EMPRESA_;
    protected $_FATURAMENTO_;
    protected $_SERVER_;
    protected $_NF_TYPE_;
    protected $_TOKEN_;

    static public function consultar($ref, $debug, $type)
    {
        if ($debug) {
            $_SERVER_ = self::_URL_HOMOLOGACAO_;
            $_TOKEN_ = self::_TOKEN_HOMOLOGACAO_;
        } else {
            $_SERVER_ = self::_URL_PRODUCAO_;
            $_TOKEN_ = self::_TOKEN_PRODUCAO_;
        }

        if (!strcmp($type, 'nfe')) {
            $URL = $_SERVER_ . "/nfe2/consultar?ref=" . $ref . "&token=" . $_TOKEN_;
        } else {
            $URL = $_SERVER_ . "/nfse/" . $ref . ".json?token=" . $_TOKEN_;
        }
//        return $URL;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $URL);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array());


        $body = curl_exec($ch);
        $result = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        $body = Yaml::parse($body);

//        return $body;
//        erros: [
//{
//    codigo: "nfse_nao_encontrada",
//mensagem: "Nota fiscal não encontrada"
//}
//]
//{
//mensagem_sefaz: "Rejeição: Total do Produto / Serviço difere do somatório dos itens",
//status: "erro_autorizacao",
//status_sefaz: "564"
//}
//	    var TIPO_NF = json.type;
//	    var REF = json.ref;
//	    var BODY = json.body;
//	    var STATUS = BODY.status;
//	    var URL = json.url;
//	    var $parent = $($this).find('div.modal-body ul.listas_nf');

        if (isset($body['status'])) {
	        if ( $body['status'] == "erro_autorizacao" ) {
		        $body['mensagem'] = $body['mensagem_sefaz'];
	        } else if ( ( $body['status'] != 'processando_autorizacao' ) && ( ! isset( $body['uri'] ) ) ) {
                $body['uri'] = $_SERVER_ . $body['caminho_danfe'];
            }
        }

        $retorno = [
            'url_focus' => $URL,
            'profile' => ($debug) ? 'Homologação' : 'Produção',
            'ref' => $ref,
            'type' => $type,
            'url' => $_SERVER_,
            'body' => $body,
            'status' => $result,
        ];
        return ($retorno);
    }

    static public function cancelar($ref, $debug, $type, $params)
    {

        if ($debug) {
            $_SERVER_ = self::_URL_HOMOLOGACAO_;
            $_TOKEN_ = self::_TOKEN_HOMOLOGACAO_;
        } else {
            $_SERVER_ = self::_URL_PRODUCAO_;
            $_TOKEN_ = self::_TOKEN_PRODUCAO_;
        }


        $ch = curl_init();
        if (!strcmp($type, 'nfe')) {
            //https://api.focusnfe.com.br/nfe2/cancelar?token=TOKEN&amp;ref=REFERENCIA&amp;justificativa=Justificativa%20para%20o%20cancelamento
            $URL = $_SERVER_ . "/" . self::_URL_NFe_ . "/cancelar?token=" . $_TOKEN_ . "&ref=" . $ref;
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, Yaml::dump($params));
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/plain'));
        } else {
            //curl_setopt($ch, CURLOPT_URL, "http://homologacao.acrasnfe.acras.com.br/nfse/" . $ref . "?token=" . $token);
            $URL = $_SERVER_ . "/" . self::_URL_NFSe_ . "/" . $ref . "?token=" . $_TOKEN_;
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array());
//            curl_setopt($ch, CURLOPT_POSTFIELDS, Yaml::dump($params));
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        }

        curl_setopt($ch, CURLOPT_URL, $URL);

        $body = curl_exec($ch);
        $result = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        $retorno = [
            'type' => $type,
            'url' => $_SERVER_,
            'body' => Yaml::parse(curl_exec($ch)),
            'status' => curl_getinfo($ch, CURLINFO_HTTP_CODE),
        ];
        curl_close($ch);
        return ($retorno);
    }

    public function writeJson()
    {
        $fp = fopen('results_' . $this->_NF_TYPE_ . '.json', 'w');
        fwrite($fp, json_encode($this->_PARAMS_NF_));
        fclose($fp);
        return;
    }

    public function setLink($link)
    {
        return $this->_FATURAMENTO_->setUrl($this->_NF_TYPE_, $link);
    }

    public function emitir()
    {
        $URL = $this->_SERVER_ . "/" . $this->_NF_TYPE_;

        if (!strcmp($this->_NF_TYPE_, 'nfe2')) {
            $URL .= "/autorizar?ref=" . $this->_REF_ . "&token=" . $this->_TOKEN_;
        } else {
            $URL .= "?token=" . $this->_TOKEN_ . "&ref=" . $this->_REF_;
        }
        //http://homologacao.acrasnfe.acras.com.br/nfe2/autorizar?token=TOKEN&ref=1
        //http://homologacao.acrasnfe.acras.com.br/nfse?ref=REFERENCIA&amp;token=TOKEN

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $URL);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, Yaml::dump($this->_PARAMS_NF_));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/plain'));

        $body = curl_exec($ch);
        $result = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        $retorno = (object)[
            'url_focus' => $URL,
            'type' => $this->_NF_TYPE_,
            'body' => json_decode($body),
            'result' => $result,
        ];
        curl_close($ch);

        return ($retorno);

//        echo $URL.'<BR>';
//        print("STATUS: ".$result."\n");
//        print("BODY: ".$body."\n\n");
//        curl_close($ch);

    }

}
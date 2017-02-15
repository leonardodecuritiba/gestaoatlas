<?php

namespace App\Models;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7;

/**
 * LaravelFocusnfe
 *
 * @author Flávio H. Ferreira <flaviometalvale@gmail.com>
 * @author Jansen Felipe <jansen.felipe@gmail.com>
 */
class Nfe
{
    CONST _URL_HOMOLOGACAO_ = 'http://homologacao.acrasnfe.acras.com.br/nfe2';
    CONST _URL_PRODUCAO_ = 'http://homologacao.acrasnfe.acras.com.br/nfe2';
//    CONST _URL_PRODUCAO_ = 'https://api.focusnfe.com.br';
    CONST _TOKEN_HOMOLOGACAO_ = 'eR7XfUMdytg6J4nSkirtIf7jPMtc7vzK';
    CONST _TOKEN_PRODUCAO_ = 'eR7XfUMdytg6J4nSkirtIf7jPMtc7vzK';
//    CONST _TOKEN_PRODUCAO_ = 'QmPEhv5PrVGmkXpUtZIw7nvx0ZUOrDos';
    public $params;
    public $debug;
    private $SERVER;
    private $TOKEN;

    function __construct($debug)
    {
        $this->debug = $debug;
        if ($this->debug) {
            $this->SERVER = self::_URL_HOMOLOGACAO_;
            $this->TOKEN = self::_TOKEN_HOMOLOGACAO_;
            echo 'estamos em homologação<br><br>';
        } else {
            $this->SERVER = self::_URL_PRODUCAO_;
            $this->TOKEN = self::_TOKEN_PRODUCAO_;
            echo 'estamos em produção<br><br>';
        }
    }

    /**
     * Metodo para capturar o captcha e viewstate para enviar no metodo
     * de consulta
     *
     * @param  string $cnpj CNPJ
     * @throws Exception
     * @return array Link para ver o Captcha e Cookie
     */
    public static function getParams()
    {
        $client = new Client();
        //https://www.cadesp.fazenda.sp.gov.br/Pages/Cadastro/Consultas/ConsultaPublica/ConsultaPublica.aspx
        $crawler = $client->request('GET', 'http://pfeserv1.fazenda.sp.gov.br/sintegrapfe/consultaSintegraServlet');
        $response = $client->getResponse();
        $input = $crawler->filter('input[name="paramBot"]');
        $paramBot = trim($input->attr('value'));
        $headers = $response->getHeaders();
        $cookie = $headers['Set-Cookie'][0];
        $paramBotURL = urlencode($paramBot);
        $ch = curl_init("http://pfeserv1.fazenda.sp.gov.br/sintegrapfe/imageGenerator?keycheck=" . $paramBotURL);
        $options = array(
            CURLOPT_COOKIEJAR => 'cookiejar',
            CURLOPT_HTTPHEADER => array(
                "Pragma: no-cache",
                "Origin: http://pfeserv1.fazenda.sp.gov.br",
                "Host: pfeserv1.fazenda.sp.gov.br",
                "User-Agent: Mozilla/5.0 (Windows NT 6.1; rv:32.0) Gecko/20100101 Firefox/32.0",
                "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8",
                "Accept-Language: pt-BR,pt;q=0.8,en-US;q=0.5,en;q=0.3",
                "Accept-Encoding: gzip, deflate",
                "Referer: http://pfeserv1.fazenda.sp.gov.br/sintegrapfe/consultaSintegraServlet",
                "Cookie: flag=1; $cookie",
                "Connection: keep-alive"
            ),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_BINARYTRANSFER => true
        );
//            CURLOPT_FOLLOWLOCATION => true,
        curl_setopt_array($ch, $options);
        $img = curl_exec($ch);
        curl_close($ch);
        if (@imagecreatefromstring($img) == false) {
            throw new Exception('Não foi possível capturar o captcha');
        }
        return array(
            'cookie' => $cookie,
            'captchaBase64' => 'data:image/png;base64,' . base64_encode($img),
            'paramBot' => $paramBot
        );
    }

    /**
     * Metodo para realizar a consulta
     *
     * @param  string $cnpj CNPJ
     * @param  string $ie IE - Não Testado
     * @param  string $paramBot ParamBot parametro enviado para validação do captcha
     * @param  string $captcha CAPTCHA
     * @param  string $stringCookie COOKIE
     * @throws Exception
     * @return array  Dados da empresa
     */
    public static function consulta($cnpj, $ie, $paramBot, $captcha, $stringCookie)
    {
        $arrayCookie = explode(';', $stringCookie);
        if (!Utils::isCnpj($cnpj)) {
            $erro_msg = 'O CNPJ informado não é válido.';
            return (['status' => 0, 'response' => $erro_msg]);
//            throw new Exception('O CNPJ informado não é válido.');
        }
        $client = new Client();
        #$client->getClient()->setDefaultOption('timeout', 120);
//        $client->getClient()->setDefaultOption('config/curl/'.CURLOPT_TIMEOUT, 0);
//        $client->getClient()->setDefaultOption('config/curl/'.CURLOPT_TIMEOUT_MS, 0);
//        $client->getClient()->setDefaultOption('config/curl/'.CURLOPT_CONNECTTIMEOUT, 0);
//        $client->getClient()->setDefaultOption('config/curl/'.CURLOPT_RETURNTRANSFER, true);
        $client->setHeader('Host', 'pfeserv1.fazenda.sp.gov.br');
        $client->setHeader('User-Agent', 'Mozilla/5.0 (Windows NT 6.1; rv:32.0) Gecko/20100101 Firefox/32.0');
        $client->setHeader('Accept', 'text/html,application/xhtml+xml,application/xml;q=0.9, */* ;q=0.8');
        $client->setHeader('Accept-Language', 'pt-BR,pt;q=0.8,en-US;q=0.5,en;q=0.3');
        $client->setHeader('Accept-Encoding', 'gzip, deflate');
        $client->setHeader('Referer', 'http://www.sintegra.gov.br/new_bv.html');
        $client->setHeader('Cookie', $arrayCookie[0]);
        $client->setHeader('Connection', 'keep-alive');
        $servico = strlen($cnpj) > 0 ? 'cnpj' : 'ie';
        $consultaPor = strlen($cnpj) > 0 ? 'Consulta por CNPJ' : 'Consulta por IE';
        $param = array(
            'hidFlag' => '0',
            'cnpj' => Utils::unmask($cnpj),
            'ie' => Utils::unmask($ie),
            'paramBot' => $paramBot,
            'Key' => $captcha,
            'servico' => $servico,
            'botao' => $consultaPor
        );
        $crawler = $client->request('POST', 'http://pfeserv1.fazenda.sp.gov.br/sintegrapfe/sintegra', $param);
        $imageError = 'O valor da imagem esta incorreto ou expirou. Verifique novamente a imagem e digite exatamente os 5 caracteres exibidos.';
        $checkError = $crawler->filter('body > center')->eq(1)->count();
        if ($checkError && $imageError == trim($crawler->filter('body > center')->eq(1)->text())) {
            $erro_msg = $imageError;
            return (['status' => 0, 'response' => $erro_msg]);
//            throw new Exception($imageError, 99);
        }
        $center_ = $crawler->filter('body > center');
        if (count($center_) == 0) {
            $erro_msg = 'Serviço indisponível!. Tente novamente.';
            return (['status' => 0, 'response' => $erro_msg]);
//            throw new Exception('Serviço indisponível!. Tente novamente.', 99);
        }
        //self::saveFile($client);
        $html = self::parseContent($client->getResponse()->__toString());
        $crawler = new  \Symfony\Component\DomCrawler\Crawler($html);
        $data = self::parseSelectors($crawler);
        return $data;
    }

    public static function parseContent($content)
    {
        $content = utf8_encode($content);
        $content = str_replace("&nbsp;", "", $content);
        return $content;
    }

    public static function parseSelectors($crawler)
    {
        try {
            try {
                $cnpj = $crawler->filter('body > center')->eq(3)->filter('table > tr > td')->eq(1)->filter('font')->text();
            } catch (\Exception $e) {
                $cnpj = null;
            }
            try {
                $ie = $crawler->filter('body > center')->eq(3)->filter('table > tr > td')->eq(3)->filter('font')->text();
            } catch (\Exception $e) {
                $ie = null;
            }
            try {
                $razao_social = $crawler->filter('body > center')->eq(4)->filter('table > tr > td')->eq(1)->filter('font')->text();
            } catch (\Exception $e) {
                $razao_social = null;
            }
            try {
                $logradouro = $crawler->filter('body > center')->eq(6)->filter('table > tr > td')->eq(1)->filter('font')->text();
            } catch (\Exception $e) {
                $logradouro = null;
            }
            try {
                $numero = $crawler->filter('body > center')->eq(7)->filter('table > tr > td')->eq(1)->filter('font')->text();
            } catch (\Exception $e) {
                $numero = null;
            }
            try {
                $complemento = $crawler->filter('body > center')->eq(7)->filter('table > tr > td')->eq(3)->filter('font')->text();
            } catch (\Exception $e) {
                $complemento = null;
            }
            try {
                $bairro = $crawler->filter('body > center')->eq(8)->filter('table > tr > td')->eq(1)->filter('font')->text();
            } catch (\Exception $e) {
                $bairro = null;
            }
            try {
                $municipio = $crawler->filter('body > center')->eq(9)->filter('table > tr > td')->eq(1)->filter('font')->text();
            } catch (\Exception $e) {
                $municipio = null;
            }
            try {
                $uf = $crawler->filter('body > center')->eq(9)->filter('table > tr > td')->eq(3)->filter('font')->text();
            } catch (\Exception $e) {
                $uf = null;
            }
            try {
                $cep = $crawler->filter('body > center')->eq(10)->filter('table > tr > td')->eq(1)->filter('font')->text();
            } catch (\Exception $e) {
                $cep = null;
            }
            try {
                $atividade_economica = $crawler->filter('body > center')->eq(12)->filter('table > tr > td')->eq(1)->filter('font')->text();
            } catch (\Exception $e) {
                $atividade_economica = null;
            }
            try {
                $situacao_cadastral_vigente = $crawler->filter('body > center')->eq(13)->filter('table > tr > td')->eq(1)->filter('font')->text();
            } catch (\Exception $e) {
                $situacao_cadastral_vigente = null;
            }
            try {
                $situacao_cadastral_vigente .= ' - ' .
                    $crawler->filter('body > center')->eq(13)->filter('table > tr > td')->eq(2)->filter('font')->text();
            } catch (\Exception $e) {
                $situacao_cadastral_vigente = null;
            }
            try {
                $data_situacao_cadastral = $crawler->filter('body > center')->eq(14)->filter('table > tr > td')->eq(1)->filter('font')->text();
            } catch (\Exception $e) {
                $data_situacao_cadastral = null;
            }
            try {
                $regime_de_apuracao = $crawler->filter('body > center')->eq(15)->filter('table > tr > td')->eq(1)->filter('font')->text();
            } catch (\Exception $e) {
                $regime_de_apuracao = null;
            }
            try {
                $data_credenciamento_emissor_nfe = $crawler->filter('body > center')->eq(16)->filter('table > tr > td')->eq(1)->filter('font')->text();
            } catch (\Exception $e) {
                $data_credenciamento_emissor_nfe = null;
            }
            try {
                $indicador_obrigatoriedade_nfe = $crawler->filter('body > center')->eq(17)->filter('table > tr > td')->eq(1)->filter('font')->text();
            } catch (\Exception $e) {
                $indicador_obrigatoriedade_nfe = null;
            }
            try {
                $data_inicio_obrigatoriedade_nfe = $crawler->filter('body > center')->eq(18)->filter('table > tr > td')->eq(1)->filter('font')->text();
            } catch (\Exception $e) {
                $data_inicio_obrigatoriedade_nfe = null;
            }

            if ($data_inicio_obrigatoriedade_nfe == 'Acessar cadastro de outro Estado') {
                $data_inicio_obrigatoriedade_nfe = null;
            }

            $situacao_cadastral = explode(' - ', $situacao_cadastral_vigente);
            $result = [
                'status' => 1,
                'cnpj' => $cnpj,
                'ie' => $ie,
                'razao_social' => $razao_social,
                'ativ_economica' => $atividade_economica,
                'sit_cad_vigente' => $situacao_cadastral[0],
                'sit_cad_status' => $situacao_cadastral[1],
                'data_sit_cad' => $data_situacao_cadastral,
                'reg_apuracao' => $regime_de_apuracao,
                'data_credenciamento' => $data_credenciamento_emissor_nfe,
                'ind_obrigatoriedade' => $indicador_obrigatoriedade_nfe,
                'data_ini_obrigatoriedade' => $data_inicio_obrigatoriedade_nfe,

                'cep' => $cep,
                'estado' => $uf,
                'cidade' => $municipio,
                'bairro' => $bairro,
                'logradouro' => $logradouro,
                'numero' => $numero,
                'complemento' => $complemento
            ];

//            $result['status'] = 1;
//            $result['cnpj'] = $cnpj;
//            $result['ie'] = $ie;
//            $result['razao_social'] = $razao_social;
//            $result['atividade_economica'] = $atividade_economica;
//            $result['situacao_cadastral_vigente'] = $situacao_cadastral_vigente;
//            $result['data_situacao_cadastral'] = $data_situacao_cadastral;
//            $result['regime_de_apuracao'] = $regime_de_apuracao;
//            $result['data_credenciamento_emissor_nfe'] = $data_credenciamento_emissor_nfe;
//            $result['indicador_obrigatoriedade_nfe'] = $indicador_obrigatoriedade_nfe;
//            $result['data_inicio_obrigatoriedade_nfe'] = $data_inicio_obrigatoriedade_nfe;
//            $result['cep'] = $cep;
//            $result['uf'] = $uf;
//            $result['municipio'] = $municipio;
//            $result['bairro'] = $bairro;
//            $result['logradouro'] = $logradouro;
//            $result['numero'] = $numero;
//            $result['complemento'] = $complemento;

            foreach ($result as $key => $value) {
                if ($value != '' && $value != null) {
                    $result[$key] = utf8_decode($value);
                }
            }
            return $result;
        } catch (\Exception $e) {
//            throw new Exception($e->getMessage() . "Dados não encontrados/Serviço Indisponível.");
            $erro_msg = "Dados não encontrados/Serviço Indisponível.";
            return (['status' => 0, 'response' => $erro_msg]);
//            return (['status' => 0, 'response' => $erro_msg." ".$e->getMessage()]);
        }
    }

    public static function saveFile($client)
    {
        $file = fopen(getcwd() . "/crawler-sintegra.html", "w");
        fwrite($file, $client->getResponse()->__toString());
        fclose($file);
    }

    public function send()
    {
        return self::send_teste();
        $client = new Client(['base_uri' => $this->SERVER]);
        $ref = 1;
        try {
            $response = $client->request('POST', $this->SERVER . "/autorizar.json?token=" . $this->TOKEN . "&ref=" . $ref, ['json' => $this->params]);
            echo $client->getResponse();
            echo $client->getStatusCode(); // "200"
            echo $client->getHeader('content-type'); // 'application/json; charset=utf8'
            echo $client->getBody();
            exit;
        } catch (RequestException $e) {
            echo "RequestException: <br><br>";
            if ($e->hasResponse()) {
                echo Psr7\str($e->getResponse());
            }
        } catch (ClientException $e) {
            echo ">ClientException: <br><br>";
            echo Psr7\str($e->getRequest());
            echo Psr7\str($e->getResponse());
        }
        exit;
    }

    public function send_teste()
    {

        $ch = curl_init();
        // Substituir pela sua identificação interna da nota
        $ref = 1;
        // caso queira enviar usando o formato YAML, use a linha abaixo
        // curl_setopt($ch, CURLOPT_URL, $SERVER."/nfe2/autorizar?ref=" . $ref . "&token=" . $TOKEN);
        // formato JSON
        curl_setopt($ch, CURLOPT_URL, $this->SERVER . "/autorizar.json?ref=" . $ref . "&token=" . $this->TOKEN);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        // caso queira enviar usando o formato YAML, use a linha abaixo (necessário biblioteca PECL yaml)
        // curl_setopt($ch, CURLOPT_POSTFIELDS,     yaml_emit($nfe));
        // formato JSON
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($this->params));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/plain'));
        $body = curl_exec($ch);
        $result = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        //as três linhas abaixo imprimem as informações retornadas pela API, aqui o seu sistema deverá
        //interpretar e lidar com o retorno
        print("STATUS: " . $result . "<br>");
        print("BODY <br><br>");
        print(($body));

        curl_close($ch);
        exit;


    }

    public function setParams()
    {
        $this->params = array(
            "natureza_operacao" => 'Remessa de Produtos',
            "forma_pagamento" => 0,
            "data_emissao" => '2017-02-09T12:00:00-03:00',
            "tipo_documento" => 1,
            "finalidade_emissao" => 1,

            "cnpj_emitente" => '10555180000121',
            "nome_emitente" => 'MACEDO AUTOMAÇAO COMERCIAL LTDA',
            "nome_fantasia_emitente" => 'MACEDO AUTOMAÇAO COMERCIAL LTDA',
            "logradouro_emitente" => 'Rua Triunfo',
            "numero_emitente" => '400',
            "bairro_emitente" => 'Santa Cruz',
            "municipio_emitente" => 'Ribeirão Preto',
            "uf_emitente" => 'SP',
            "cep_emitente" => '14020670',
            "telefone_emitente" => '016 3011-8448',
            "inscricao_estadual_emitente" => '797146934117',
//            'n_autorizacao' => '10002180',

            "nome_destinatario" => 'NF-E EMITIDA EM AMBIENTE DE HOMOLOGACAO - SEM VALOR FISCAL',
            "cnpj_destinatario" => '10812933000137',
            "indicador_inscricao_estadual_destinatario" => 2,
//            "inscricao_estadual_destinatario" => 2,
            "telefone_destinatario" => '6132332933',
            "logradouro_destinatario" => 'SMAS 6580 PARKSHOPPING',
            "numero_destinatario" => '134',
            "bairro_destinatario" => 'Zona Industrial (Guará)',
            "municipio_destinatario" => 'Brasilia',
            "uf_destinatario" => 'DF',

            "pais_destinatario" => 'Brasil',
            "cep_destinatario" => '71219900',
            "icms_base_calculo" => '0',
            "icms_valor_total" => '0',
            "icms_base_calculo_st" => '0',
            "icms_valor_total_st" => '0',
            "icms_modalidade_base_calculo" => '0',
            "icms_valor" => '0',
            "valor_frete" => '0.0000',
            "valor_seguro" => '0',
            "valor_total" => '2241.66',
            "valor_produtos" => '2241.66',
            "valor_ipi" => '0',
            "modalidade_frete" => '0',
            "informacoes_adicionais_contribuinte" => 'Não Incidência ICMS conforme Decisão...',
            "nome_transportador" => 'BRASPRESS TRANSPORTES URGENTES LTDA SP',
            "cnpj_transportador" => '48740351000165',
            "endereco_transportador" => 'RUA CORONEL MARQUES RIBEIRO, 225',
            "municipio_transportador" => 'SÃO PAULO',
            "uf_transportador" => 'SP',
            "inscricao_estadual_transportador" => '116945108113',
            "items" => array(
                array(
                    "numero_item" => '1',
                    "codigo_produto" => '9999999',
                    "descricao" => 'Perfume Polo Black',
                    "cfop" => '6949',
                    "unidade_comercial" => 'un',
                    "quantidade_comercial" => '5000',
                    "valor_unitario_comercial" => '0.448332',
                    "valor_unitario_tributavel" => '0.448332',
                    "unidade_tributavel" => 'un',
                    "codigo_ncm" => '49111090',
                    "quantidade_tributavel" => '5000',
                    "valor_bruto" => '2241.66',
                    "icms_situacao_tributaria" => '41',
                    "icms_origem" => '0',
                    "pis_situacao_tributaria" => '07',
                    "cofins_situacao_tributaria" => '07',
                    "ipi_situacao_tributaria" => '53',
                    "ipi_codigo_enquadramento_legal" => '999'
                )
            ),
            "volumes" => array(
                array(
                    "quantidade" => '2',
                    "especie" => 'Volumes',
                    "marca" => '',
                    "numero" => '',
                    "peso_bruto" => '36',
                    "peso_liquido" => '36'
                )
            ),
            "duplicatas" => array(
                array(
                    "numero" => 'Pagamento a vista',
                    "valor" => '2241.66'
                )
            )
        );
    }
}
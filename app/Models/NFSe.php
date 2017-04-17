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
    public $servico_params_fixos = [

        //3. A aliquota não deve ser enviada para optantes do simples nacional
//        'aliquota' => 3.84,

        'item_lista_servico' => '14.01', //'14.01/14.01.11',
        'codigo_cnae' => '3314710', //'3314-7/10',

        //4. Faltava um dígido no codigo_tributacao_municipio
        'codigo_tributario_municipio' => '14.01.11 / 00140111', //'14.01',

        //1. Alterei a configuração para remover automaticamente os acentos
        'discriminacao' => 'SERVIÇOS PRESTADOS EM BALANÇAS, MÍDIAS, COLETORES DE DADOS, CFTV, SERVIDORES, FATIADORES, REDES DE DADOS E OUTROS EQUIPAMANTOS DE AUTOMAÇÃO COMERCIAL \ INDUSTRIAL',
//        'discriminacao' => 'SERVI\u00c7OS PRESTADOS EM BALAN\u00c7AS, M\u00cdDIAS, COLETORES DE DADOS, CFTV, SERVIDORES, FATIADORES, REDES DE DADOS E OUTROS EQUIPAMANTOS DE AUTOM\u00c7\u00c3O COMERCIAL \\ INDUSTRIA',
        'codigo_municipio' => '3543402', //cliente
    ];
    private $_EMPRESA_;
    private $_FECHAMENTO_;
    private $now;
    private $cabecalho;
    private $prestador;
    private $tomador;
    private $servico;

    function __construct($debug, Fechamento $fechamento)
    {
        $this->debug = $debug;
        if ($this->debug) {
            $this->_SERVER_ = parent::_URL_HOMOLOGACAO_;
            $this->_TOKEN_ = parent::_TOKEN_HOMOLOGACAO_;
            $this->_REF_ = $fechamento->idnfse_homologacao;
        } else {
            $this->_SERVER_ = parent::_URL_PRODUCAO_;
            $this->_TOKEN_ = parent::_TOKEN_PRODUCAO_;
            $this->_REF_ = $fechamento->idnfse_producao;
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
        $this->setServico();

        $this->_PARAMS_NF_ = array_merge(
            $this->cabecalho,
            ['prestador' => $this->prestador],
            ['tomador' => $this->tomador],
            ["servico" => $this->servico]
        );
        $fp = fopen('results_nfse.json', 'w');
        fwrite($fp, json_encode($this->_PARAMS_NF_));
        fclose($fp);
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
        if ($Contato->complemento != "") {
            $endereco["complemento"] = $Contato->complemento; //: Complemento do endereço. Tamanho: 60 caracteres.
        }
        $endereco["bairro"] = $Contato->bairro; //: Bairro. Tamanho: 60 caracteres.
        $endereco["codigo_municipio"] = $this->servico_params_fixos['codigo_municipio']; //: código IBGE do município.
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

        $valores = $this->_FECHAMENTO_->getValores();
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
//            "aliquota" => $this->servico_params_fixos['aliquota'],//aliquota: Aliquota do ISS.

            "desconto_incondicionado" => 0,//desconto_incondicionado: Valor do desconto incondicionado. Campo ignorado pelo município de São Paulo.
            "desconto_condicionado" => 0,//desconto_condicionado: Valor do desconto incondicionado. Campo ignorado pelo município de São Paulo.
            "item_lista_servico" => $this->servico_params_fixos['item_lista_servico'],//item_lista_servico (*): informar o código da lista de serviços, de acordo com a Lei Complementar 116/2003. Utilize outra tabela para o município de São Paulo.
            //2. Não deve ser enviado o código cnae para esta prefeitura
//            "codigo_cnae" => $this->servico_params_fixos['codigo_cnae'],//codigo_cnae: Informar o código CNAE. Campo ignorado pelo município de São Paulo.

            "codigo_tributario_municipio" => $this->servico_params_fixos['codigo_tributario_municipio'],//codigo_tributario_municipio: Informar o código tributário de acordo com a tabela de cada município (não há um padrão). Campo ignorado pelo município de São Paulo.
            "discriminacao" => $this->servico_params_fixos['discriminacao'],//discriminacao(*): Discriminação dos serviços. Tamanho: 2000 caracteres.
            "codigo_municipio" => $this->servico_params_fixos['codigo_municipio'],//codigo_municipio(*): Informar o código IBGE do município de prestação do serviço.
            "percentual_total_tributos" => 0,//percentual_total_tributos: Percentual aproximado de todos os impostos, de acordo com a Lei da Transparência. No momento disponível apenas para São Paulo.
//                    "fonte_total_tributos" =>0 ,//fonte_total_tributos: Fonte de onde foi retirada a informação de total de impostos, por exemplo, “IBPT”. No momento disponível apenas para São Paulo.
        ];

        return true;
//        echo json_encode($NfeItens);
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
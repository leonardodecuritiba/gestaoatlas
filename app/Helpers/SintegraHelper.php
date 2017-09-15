<?php

namespace App\Helpers;

use JansenFelipe\Utils\Utils;
use SintegraPHP\SP\SintegraSP;

use Exception;
use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;

class SintegraHelper {
	/**
	 * Metodo para capturar o captcha e viewstate para enviar no metodo
	 * de consulta
	 *
	 * @throws Exception
	 * @return array Link para ver o Captcha e Cookie
	 */
	public static function getParams() {
		return SintegraSP::getParams();
	}

	/**
	 * Metodo para realizar a consulta
	 *
	 * @param  string $cnpj CNPJ
	 * @param  string $paramBot ParamBot parametro enviado para validação do captcha
	 * @param  string $captcha CAPTCHA
	 * @param  string $stringCookie COOKIE
	 *
	 * @throws Exception
	 * @return array  Dados da empresa
	 */
	public static function consulta( $cnpj, $paramBot, $captcha, $stringCookie ) {
		$arrayCookie = explode( ';', $stringCookie );


		if ( ! Utils::isCnpj( $cnpj ) ) {
			throw new Exception( 'O CNPJ informado não é válido.' );
		}

		$client = new Client();
//
		// Create and use a guzzle client instance that will time out after 60 seconds
		$guzzleClient = new \GuzzleHttp\Client( array(
			'timeout'        => 0,
			'timeout_ms'     => 0,
			'connecttimeout' => 0,
			'returntransfer' => true,
		) );
		$client->setClient( $guzzleClient );

//		$client->getClient()->setDefaultOption( 'config/curl/' . CURLOPT_TIMEOUT, 0 );
//		$client->getClient()->setDefaultOption( 'config/curl/' . CURLOPT_TIMEOUT_MS, 0 );
//		$client->getClient()->setDefaultOption( 'config/curl/' . CURLOPT_CONNECTTIMEOUT, 0 );
//		$client->getClient()->setDefaultOption( 'config/curl/' . CURLOPT_RETURNTRANSFER, true );

		$client->setHeader( 'Accept', 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8' );
		$client->setHeader( 'Accept-Encoding', 'gzip, deflate' );
		$client->setHeader( 'Accept-Language', 'en-US,en;q=0.8,pt-BR;q=0.6,pt;q=0.4' );
		$client->setHeader( 'Cache-Control', 'max-age=0' );
		$client->setHeader( 'Connection', 'keep-alive' );
		$client->setHeader( 'Content-Type', 'application/x-www-form-urlencoded' );
		$client->setHeader( 'Cookie', $arrayCookie[0] );
		$client->setHeader( 'Host', 'pfeserv1.fazenda.sp.gov.br' );


		$client->setHeader( 'Origin', 'http://pfeserv1.fazenda.sp.gov.br' );
		$client->setHeader( 'Referer', 'http://pfeserv1.fazenda.sp.gov.br/sintegrapfe/consultaSintegraServlet' );
		$client->setHeader( 'Upgrade-Insecure-Requests', '1' );
		$client->setHeader( 'User-Agent', 'Mozilla/5.0 (Windows NT 6.1; rv:32.0) Gecko/20100101 Firefox/32.0' );


		$param = array(
			'hidFlag'  => '1',
			'cnpj'     => Utils::unmask( $cnpj ),
			'ie'       => '',
			'paramBot' => $paramBot,
			'Key'      => $captcha,
			'servico'  => 'cnpj',
			'botao'    => 'Consulta por CNPJ'
		);

		$crawler = $client->request( 'POST', 'http://pfeserv1.fazenda.sp.gov.br/sintegrapfe/sintegra', $param );

		$imageError = 'O valor da imagem esta incorreto ou expirou. Verifique novamente a imagem e digite exatamente os 5 caracteres exibidos.';
		$checkError = $crawler->filter( 'body > center' )->eq( 1 )->count();

		if ( $checkError && $imageError == trim( $crawler->filter( 'body > center' )->eq( 1 )->text() ) ) {
			throw new Exception( $imageError, 99 );
		}

		$center_ = $crawler->filter( 'body > center' );


		if ( count( $center_ ) == 0 ) {
			throw new Exception( 'Serviço indisponível!. Tente novamente.', 99 );
		}

		file_put_contents( 'resposta.html', $crawler );
	}

	/**
	 * Metodo para efetuar o parser
	 *
	 * @param  Crawler $crawler HTML
	 *
	 * @return array  Dados da empresa
	 */
	public static function parser( Crawler $crawler ) {
		return SintegraSP::parser( $crawler );

		return [
			'cnpj'                => (string) $crawler->filter( "input[name='cnpj.identificacaoFormatada']" )->attr( 'value' ),
			'inscricao_estadual'  => (string) $crawler->filter( "input[name='inscricaoEstadual.identificacaoFormatada']" )->attr( 'value' ),
			'razao_social'        => (string) $crawler->filter( "input[name='nomeEmpresarial']" )->attr( 'value' ),
			'cnae_principal'      => (string) $crawler->filter( "input[name='cnaefPrincipal.descricao']" )->attr( 'value' ),
			'data_inscricao'      => (string) $crawler->filter( "input[name='dataInicioInscricao']" )->attr( 'value' ),
			'situacao'            => (string) $crawler->filter( "input[name='situacaoContribuinte.descricao']" )->attr( 'value' ),
			'situacao_data'       => (string) $crawler->filter( "input[name='dataSituacao']" )->attr( 'value' ),
			'regime_recolhimento' => (string) $crawler->filter( "input[name='regimeRecolhimento.descricao']" )->attr( 'value' ),
			'motivo_suspensao'    => (string) $crawler->filter( "input[name='motivoSuspensao.descricao']" )->attr( 'value' ),
			'telefone'            => (string) $crawler->filter( "input[name='comunicacao.telefone']" )->attr( 'value' ),
			'endereco'            => [
				'cep'         => (string) $crawler->filter( "input[name='enderecoEstabelecimento.cep']" )->attr( 'value' ),
				'logradouro'  => (string) $crawler->filter( "input[name='enderecoEstabelecimento.nomeTipoLogradouro']" )->attr( 'value' ) . ' ' . (string) $crawler->filter( "input[name='enderecoEstabelecimento.nomeLogradouro']" )->attr( 'value' ),
				'numero'      => (string) $crawler->filter( "input[name='enderecoEstabelecimento.numero']" )->attr( 'value' ),
				'complemento' => (string) $crawler->filter( "input[name='txtComplemento']" )->attr( 'value' ),
				'bairro'      => (string) $crawler->filter( "input[name='enderecoEstabelecimento.nomeBairro']" )->attr( 'value' ),
				'cidade'      => (string) $crawler->filter( "input[name='enderecoEstabelecimento.nomeMunicipio']" )->attr( 'value' ),
				'distrito'    => (string) $crawler->filter( "input[name='enderecoEstabelecimento.nomePovoadoDistrito']" )->attr( 'value' ),
				'uf'          => (string) $crawler->filter( "input[name='enderecoEstabelecimento.sgUf_']" )->attr( 'value' ),
			]
		];
	}
}

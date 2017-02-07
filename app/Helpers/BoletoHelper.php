<?php

namespace App\Helpers;

use App\OrdemServico;
use Carbon\Carbon;
use Eduardokum\LaravelBoleto\Boleto\Banco\Santander;
use Eduardokum\LaravelBoleto\Boleto\Render\Html;
use Eduardokum\LaravelBoleto\Boleto\Render\Pdf;
use Eduardokum\LaravelBoleto\Pessoa;

class BoletoHelper
{
    private $boleto;
    private $ordemServico;

    function __construct(OrdemServico $ordemServico)
    {
        $this->ordemServico = $ordemServico;
        $this->set_boleto();
    }

    public function set_boleto()
    {
        $logo = asset('uploads/instuticional/logo_atlas.png'); // Logo da empresa
        $beneficiario = new Pessoa(
            [
                'nome' => 'ACME',
                'endereco' => 'Rua um, 123',
                'cep' => '99999-999',
                'uf' => 'UF',
                'cidade' => 'CIDADE',
                'documento' => '99.999.999/9999-99',
            ]
        );
        $pagador = new Pessoa(
            [
                'nome' => 'Cliente',
                'endereco' => 'Rua um, 123',
                'bairro' => 'Bairro',
                'cep' => '99999-999',
                'uf' => 'UF',
                'cidade' => 'CIDADE',
                'documento' => '999.999.999-99',
            ]
        );
        $boletoArray = [
            'logo' => public_path('uploads\institucional\logo_atlas_min.png'),
            'dataVencimento' => new Carbon(),
            'valor' => 100,
            'multa' => false,
            'juros' => false,
            'numero' => 1,
            'numeroDocumento' => 1,
            'pagador' => $pagador,
            'beneficiario' => $beneficiario,
            'carteira' => 101,
            'agencia' => 1111,
            'conta' => 99999999,
            'descricaoDemonstrativo' => ['demonstrativo 1', 'demonstrativo 2', 'demonstrativo 3'],
            'instrucoes' => ['instrucao 1', 'instrucao 2', 'instrucao 3'],
            'aceite' => 'S',
            'especieDoc' => 'DM',
        ];
        $this->boleto = new Santander($boletoArray);
    }

    public function gerar_PDF()
    {
        $pdf = new Pdf();
        $pdf->addBoleto($this->boleto);
        return $pdf->gerarBoleto();
//        $pdf->gerarBoleto(Pdf::OUTPUT_DOWNLOAD); // força o download pelo navegador.
//        return $pdf->gerarBoleto(Pdf::OUTPUT_SAVE, storage_path('app/boletos/meu_boleto.pdf')); // salva o boleto na pasta.
//        return $pdf->gerarBoleto(Pdf::OUTPUT_STANDARD, null, true); // executa o comportamento padrão do navedor, mostrando a janela de impressão.
//        $pdf_inline = $pdf->gerarBoleto(Pdf::OUTPUT_STRING); // retorna o boleto em formato string.
//        $pdf->gerarBoleto(Pdf::OUTPUT_DOWNLOAD); // força o download pelo navegador.
    }

    public function gerar_HTML($print)
    {
        $html = new Html($this->boleto->toArray());
        $html->gerarBoleto();
        return $html->gerarBoleto(true); // mostra a janela de impressão
    }

    public function teste()
    {

        $pdf = new \Eduardokum\LaravelBoleto\Boleto\Render\Pdf();
        $pdf->addBoleto($boleto);
        $pdf->gerarBoleto($pdf::OUTPUT_SAVE,
            __DIR__ . DIRECTORY_SEPARATOR . 'arquivos' . DIRECTORY_SEPARATOR . 'santander.pdf');

        return;
        $beneficiario = new \Eduardokum\LaravelBoleto\Pessoa([
            'nome' => 'MACEDO AUTOMACAO COMERCIAL LTDA ME',
            'endereco' => 'R TRIUNFO, 400 - SANTA CRUZ DO JOSE JACQUES',
            'cep' => '14020-670',
            'uf' => 'SP',
            'cidade' => 'RIBEIRAO PRETO',
            'documento' => '10.555.180/0001-21',

        ]);
        $pagador = new \Eduardokum\LaravelBoleto\Pessoa([
            'nome' => 'MACEDO AUTOMACAO COMERCIAL LTDA ME',
            'endereco' => 'R TRIUNFO, 400 - SANTA CRUZ DO JOSE JACQUES',
            'cep' => '14020-670',
            'uf' => 'SP',
            'cidade' => 'RIBEIRAO PRETO',
            'documento' => '10.555.180/0001-21',
        ]);

        $boletoArray = [
            'logo' => asset('uploads/instuticional/logo_atlas.png'), // Logo da empresa
            'dataVencimento' => new \Carbon\Carbon('2017-01-11'),
            'valor' => 638.00,
            'multa' => 1.00, // porcento
            'juros' => 2.00, // porcento ao mes
            'juros_apos' => 6.38, // juros e multa após
            'diasProtesto' => false, // protestar após, se for necessário
            'numero' => 1,
            'numeroDocumento' => 439,
            'especieDoc' => 'DMI',
            'aceite' => '--',
            'carteira' => 1, // BB, Bradesco, CEF, HSBC, Itáu, Santander
            'pagador' => $pagador, // Objeto PessoaContract
            'beneficiario' => $beneficiario, // Objeto PessoaContract
            'agencia' => 3966, // BB, Bradesco, CEF, HSBC, Itáu
//            'agenciaDv' => 9, // se possuir
            'conta' => 13002933, // BB, Bradesco, CEF, HSBC, Itáu, Santander
            'contaDv' => 3, // Bradesco, HSBC, Itáu
//            'convenio' => 9999999, // BB
//            'variacaoCarteira' => 99, // BB
//            'range' => 99999, // HSBC
            'codigoCliente' => 99999, // Bradesco, CEF, Santander
            'ios' => 0, // Santander
            'descricaoDemonstrativo' => ['msg1', 'msg2', 'msg3'], // máximo de 5
            'instrucoes' => ['inst1', 'inst2'], // máximo de 5
        ];

        $boleto = new \Eduardokum\LaravelBoleto\Boleto\Banco\Santander($boletoArray);
//        $boleto->renderPDF();
//        $boleto->renderHTML();
//
        $boleto->renderPDF(true); // imostra a janela de impressão
    }
}

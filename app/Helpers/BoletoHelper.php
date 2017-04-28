<?php

namespace App\Helpers;

use App\Models\Empresa;
use App\Models\Parcela;
use App\OrdemServico;
use Carbon\Carbon;
use \Eduardokum\LaravelBoleto\Boleto\Banco\Santander;
use \Eduardokum\LaravelBoleto\Boleto\Render\Html;
use \Eduardokum\LaravelBoleto\Boleto\Render\Pdf;
use \Eduardokum\LaravelBoleto\Pessoa;

class BoletoHelper
{
    private $_LOGO_PATH_;
    private $_EMPRESA_;
    private $boleto;
    private $beneficiario;
    private $pagador;

    function __construct()
    {
        $this->_LOGO_PATH_ = public_path('uploads' . DIRECTORY_SEPARATOR . 'institucional' . DIRECTORY_SEPARATOR . 'logo_atlas_min.png');
        $this->_EMPRESA_ = new Empresa();
        $this->set_beneficiario();
    }

    public function set_beneficiario()
    {
        $this->beneficiario = new Pessoa(
            [
                'nome' => $this->_EMPRESA_->razao_social,
                'endereco' => 'R TRIUNFO, 400 - SANTA CRUZ DO JOSE JACQUES',
                'cep' => $this->_EMPRESA_->cep,
                'uf' => $this->_EMPRESA_->estado,
                'cidade' => $this->_EMPRESA_->cidade,
                'documento' => $this->_EMPRESA_->cnpj,
            ]
        );
    }

    public function setBoletoParcela(Parcela $Parcela)
    {
        $Cliente = $Parcela->cliente;
        $DadosCliente = $Cliente->getType();
        $Contato = $Cliente->contato;

        $this->set_pagador([
            'nome' => $DadosCliente->nome_principal,
            'endereco' => $Contato->getRua(),
            'bairro' => $Contato->bairro,
            'cep' => $Contato->cep,
            'uf' => $Contato->estado,
            'cidade' => $Contato->cidade,
            'documento' => $DadosCliente->entidade,
        ]);

        $boletoArray = [
            'logo' => $this->_LOGO_PATH_,
            'dataVencimento' => $Parcela->getDataVencimentoBoleto(),
            'valor' => $Parcela->valor_parcela,
            'multa' => $this->_EMPRESA_->boleto['multa'],
            'juros' => $this->_EMPRESA_->boleto['juros'],
            'numero' => 1,
            'numeroDocumento' => 472,
            'pagador' => $this->pagador,
            'beneficiario' => $this->beneficiario,
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

    public function set_pagador($pagador)
    {
        $this->pagador = new Pessoa($pagador);
    }

    public function set_boleto()
    {
        $logo = asset('uploads/instuticional/logo_atlas.png'); // Logo da empresa
        $boletoArray = [
            'logo' => public_path('uploads\institucional\logo_atlas_min.png'),
            'dataVencimento' => new Carbon(),
            'valor' => 100,
            'multa' => false,
            'juros' => false,
            'numero' => 1,
            'numeroDocumento' => 1,
            'pagador' => $this->pagador,
            'beneficiario' => $this->beneficiario,
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

    public function gerarPDF()
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

    public function gerarHTML($print = true)
    {
        $html = new Html($this->boleto->toArray());
        $html->gerarBoleto();
        return $html->gerarBoleto($print); // mostra a janela de impressão
    }

    public function teste()
    {

        $pdf = new \Eduardokum\LaravelBoleto\Boleto\Render\Pdf();
        $pdf->addBoleto($this->boleto);
//        $filename = public_path('uploads' . DIRECTORY_SEPARATOR . 'boletos' . DIRECTORY_SEPARATOR . 'santander.pdf');
//        $filename = __DIR__ . DIRECTORY_SEPARATOR . 'arquivos' . DIRECTORY_SEPARATOR . 'santander.pdf');
//        $pdf->gerarBoleto($pdf::OUTPUT_SAVE,$filename);

        return $pdf->gerarBoleto();


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

        exit;
        $boleto = new \Eduardokum\LaravelBoleto\Boleto\Banco\Santander($boletoArray);
//        $boleto->renderPDF();
//        $boleto->renderHTML();
//
        $boleto->renderPDF(true); // imostra a janela de impressão
        return;
    }
}

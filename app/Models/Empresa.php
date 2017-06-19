<?php

namespace App\Models;

use App\Helpers\DataHelper;

class Empresa
{
    public $cnpj = '10555180000121';
    public $ie = '797146934117';
    public $im = '20033165';
    public $n_autorizacao = '10002180';
    public $slogan = 'Manutenção e venda de equipamentos de automação comercial';
    public $razao_social = 'MACEDO AUTOMACAO COMERCIAL LTDA ME';
    public $nome_fantasia = 'MACEDO AUTOMACAO COMERCIAL LTDA ME';
    public $cnae_fiscal = '0000465';
    public $regime_tributario = '1';

    public $logradouro = 'Av. Clovis Bevilaqua';
    public $numero = '1301';
    public $bairro = 'Jardim Bandeirantes';
    public $cidade = 'Ribeirão Preto';
    public $estado = 'SP';
    public $cep = '14090350';
    public $telefone = '1630118448';
    public $celular = '16982632900';
    public $email_os = 'os@atlastecnologia.com.br';

    public $modalidade_frete = '0';
    public $icms_servico = '0.00';
    public $icms_base_calculo = '0.00';
    public $icms_aliquota = '0.00';
    public $icms_valor = '0.00';
    public $icms_cfop = '5352';
    public $icms_codigo_municipio = '3543402';

    public $boleto = [
        'juros' => 3.00,
        'multa' => 1.00,
    ];
    public $regime_especial_tributacao = '6';
    public $optante_simples_nacional = true;
    public $incentivador_cultural = false;
    public $tributacao_rps = 'T';

    //nota de serviço

    public function cnpjFormatted()
    {
        return DataHelper::mask($this->cnpj, '##.###.###/####-##');
    }

    public function getFullAddress()
    {
        //Rua Triunfo, 400 - Santa Cruz - CEP: Santa Cruz
        return
            $this->logradouro . ', ' .
            $this->numero . ' - ' .
//            $this->bairro.' - ' .
            $this->cidade . ' - ' .
            $this->estado . ' - ';
    }

    public function ieFormatted()
    {
        return DataHelper::mask($this->ie, '###.###.###.###');
    }

    public function getCellPhone()
    {
        return DataHelper::mask($this->celular, '(##)####-####');
    }
}
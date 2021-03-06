<?php

namespace App\Models;

use App\Helpers\DataHelper;

class Empresa
{
// (16)9826-3040/(16)9826-3260

    public $aliquota = 2.5;//3. A aliquota não deve ser enviada para optantes do simples nacional
    public $porcentagem_tributos_float = 11.31;
    public $porcentagem_tributos_real = '11,31%';
    public $item_lista_servico = '14.01';//'14.01/14.01.11',
    public $codigo_cnae = '3314710';//'3314-7/10',

	//4. Faltava um dígido no codigo_tributacao_municipio
    public $codigo_tributario_municipio = '14.01.11 / 00140111';//'14.01',

	//1. Alterei a configuração para remover automaticamente os acentos
    public $discriminacao = 'SERVIÇOS PRESTADOS EM BALANÇAS, MÍDIAS, COLETORES DE DADOS, CFTV, SERVIDORES, FATIADORES, REDES DE DADOS E OUTROS EQUIPAMANTOS DE AUTOMAÇÃO COMERCIAL \ INDUSTRIAL.\n\nVALOR APROXIMADO DOS TRIBUTOS';
    public $codigo_municipio = '3543402';//cliente

    public $cnpj = '10555180000121';
    public $ie = '797146934117';
    public $im = '20033165';
    public $n_autorizacao = '10002180';
    public $slogan = 'Manutenção e venda de equipamentos de automação comercial';
    public $razao_social = 'MACEDO AUTOMACAO COMERCIAL LTDA ME';
    public $nome_fantasia = 'MACEDO AUTOMACAO COMERCIAL LTDA ME';
    public $cnae_fiscal = '0000465';
    public $regime_tributario = '1';

    public $logradouro = 'R. Antonio Leite Penteado';
    public $numero = '38';
    public $bairro = 'Jardim Veronica';
    public $cidade = 'Ribeirão Preto';
    public $estado = 'SP';
    public $cep = '14021499';
//    public $telefone = '1630118448';
    public $telefone = '16982630403';
    public $celular = '16982632600';
    public $email_os = 'chamado@atlastecnologia.com.br';

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
    public function getPhoneAndCellPhone()
    {
        return DataHelper::mask($this->telefone, '(##)#####-####') . '/' . DataHelper::mask($this->celular, '(##)#####-####');
    }
}
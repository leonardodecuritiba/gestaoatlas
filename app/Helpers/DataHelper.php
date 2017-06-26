<?php

namespace App\Helpers;
use Carbon\Carbon;
use Jenssegers\Date\Date;
use Illuminate\Http\Request;

class DataHelper
{
    // ******************** FUNCTIONS ******************************
    static public function getVectorKeyFloatToReal($values)
    {
        foreach ($values as $key => $value) {
            $values[$key] = floatval($value);
            $nkey = substr($key, 0, strlen($key) - 6);
            $values[$nkey] = self::getFloat2RealMoeda($values[$key]);
        }
        return $values;
    }

    static public function getFloat2RealMoeda($value)
    {
        return 'R$ ' . number_format($value, 2, ',', '.');
    }

    static public function getFloat2Real($value)
    {
        return number_format($value,2,',','.');
    }

    static public function getFloat2Percent($value)
    {
        return number_format($value, 2, ',', '.');
    }

    static public function getFullPrettyDateTime($value)
    {
        return ($value != NULL) ? Date::createFromFormat('Y-m-d H:i:s', $value)->format('d/m/Y H:i:s') : $value;
    }
    static public function getPrettyDateTime($value)
    {
        return ($value != NULL) ? Date::createFromFormat('Y-m-d H:i:s', $value)->format('H:i - d/m/Y') : $value;
    }

    static public function getPrettyDateTimeToMonth($value)
    {
        Date::setLocale('pt_BR');
        return ($value != NULL) ? Date::createFromFormat('Y-m-d H:i:s', $value)->format('F/Y') : $value;
    }

    static public function getPrettyDate($value)
    {
        return ($value != NULL) ? Date::createFromFormat('Y-m-d', $value)->format('d/m/Y') : $value;
    }

    static public function getPrettyToCorrectDate($value)
    {
        return ($value != NULL) ? Date::createFromFormat('d/m/Y', $value)->format('Y-m-d') : $value;
    }

    static public function getPrettyToCorrectDateTime($value)
    {
        return ($value != NULL) ? Date::createFromFormat('d/m/Y', $value)->format('Y-m-d H:i:s') : $value;
    }

    static public function setDate($value)
    {
        return (($value != NULL) && ($value != '')) ? Date::createFromFormat('dmY', self::getOnlyNumbers($value))->format('Y-m-d') : NULL;
    }

    static public function getOnlyNumbers($value)
    {
        return ($value != NULL) ? preg_replace("/[^0-9]/", "", $value) : $value;
    }

    static public function getOnlyNumbersLetters($value)
    {
        return ($value != NULL) ? preg_replace("/[^a-zA-Z0-9-]/", "", $value) : $value;
    }

    static public function getShortName($value)
    {
        $value = explode(' ', $value);
        return (count($value) > 1) ? ($value[0] . " " . end($value)) : $value[0];
    }

    static public function mask($val, $mask)
    {
        if ($val != NULL || $val != "") {
            $maskared = '';
            $k = 0;
            for ($i = 0; $i <= strlen($mask) - 1; $i++) {
                if ($mask[$i] == '#') {
                    if (isset($val[$k])) $maskared .= $val[$k++];
                } else {
                    if (isset($mask[$i])) $maskared .= $mask[$i];
                }
            }
        } else {
            $maskared = NULL;
        }
        return $maskared;
    }

    static public function storePriceTable($id, $dados, $Tabelas_preco)
    {
        $valor = self::getPercent2Float($dados['valor']);
        $margens = $dados['margens'];
        $margem_minimos = $dados['margem_minimo'];

        foreach ($Tabelas_preco as $tabela_preco) {
            $margem = self::getPercent2Float($margens[$tabela_preco->idtabela_preco]);
            $margem_minimo = self::getPercent2Float($margem_minimos[$tabela_preco->idtabela_preco]);
            $data[] = [
                'idtabela_preco' => $tabela_preco->idtabela_preco,
                key($id) => $id[key($id)],
                'preco' => $valor + ($valor * $margem) / 100,
                'margem' => $margem,
                'preco_minimo' => $valor + ($valor * $margem_minimo) / 100,
                'margem_minimo' => $margem_minimo,
            ];
        }
        return $data;
    }

    static public function getPercent2Float($value)
    {
        return floatval(str_replace(',', '.', $value));
    }

    static public function updatePriceTable($dados, $Tabelas_preco)
    {
        $valor = $dados['valor'];
        $margens = $dados['margens'];
        $margem_minimos = $dados['margem_minimo'];

        foreach ($Tabelas_preco as $tabela_preco) {
            $margem = self::getReal2Float($margens[$tabela_preco->idtabela_preco]);
            $margem_minimo = self::getReal2Float($margem_minimos[$tabela_preco->idtabela_preco]);

            $dataUpd = [
                'preco' => $valor + ($valor * $margem) / 100,
                'margem' => $margem,
                'preco_minimo' => $valor + ($valor * $margem_minimo) / 100,
                'margem_minimo' => $margem_minimo,
            ];
            $tabela_preco->update($dataUpd);
        }
    }


    // CRIAÇÃO/ATUALIZAÇÃO DAS TABELAS DE PREÇOS

    static public function getReal2Float($value)
    {
        return floatval(str_replace(',', '.', str_replace('.', '', $value)));
    }

    static public function calculateModulo11($value)
    {
        $dv = NULL;
        if ($value != NULL) {
            $sz = strlen($value);
            if ($sz > 0) {
                $sum = 0;
                foreach (range($sz + 1, 2) as $i => $number) {
                    $calc = ($value[$i] * $number);
                    $sum += $calc;
                }
                $dv = ($sum % 11); //dígito verificador
                if ($dv > 10) $dv = 0;
            }
        }
//        dd($dv);
        return ($dv >= 10) ? 0 : $dv;

    }
}
